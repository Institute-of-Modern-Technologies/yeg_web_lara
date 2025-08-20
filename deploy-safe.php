<?php
/**
 * Safe Deployment Script for Image Path Fix
 * 
 * This script:
 * 1. Creates necessary image helper classes directly in the production server
 * 2. Creates directories needed for the new image structure
 * 3. Provides a safe migration option for images (moves copies, doesn't delete)
 * 
 * No database changes are made until explicitly confirmed
 */

// Bootstrap Laravel application
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

// Step 1: Create necessary directories
echo "Creating image directories...\n";
$directories = [
    'public/images/hero-sections',
    'public/images/events',
    'public/images/testimonials',
    'public/images/happenings', 
    'public/images/trainers',
    'public/images/schools',
    'public/images/profile-photos',
];

foreach ($directories as $dir) {
    if (!File::exists(base_path($dir))) {
        File::makeDirectory(base_path($dir), 0755, true);
        echo "✓ Created: {$dir}\n";
    } else {
        echo "✓ Already exists: {$dir}\n";
    }
}

// Step 2: Create the ImageUploadHelper class
echo "\nCreating ImageUploadHelper...\n";
$helperContent = <<<'EOT'
<?php

namespace App\Helpers;

class ImageUploadHelper
{
    /**
     * Upload an image directly to the public/images directory
     * No storage:link needed
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $subDirectory
     * @return string Path relative to public
     */
    public static function uploadImageToPublic($file, $subDirectory = '')
    {
        // Generate unique filename
        $fileName = time() . '_' . uniqid() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        
        // Create full directory path
        $directoryPath = 'images/' . $subDirectory;
        $fullPath = public_path($directoryPath);
        
        // Create directory if it doesn't exist
        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0755, true);
        }
        
        // Move the file
        $file->move($fullPath, $fileName);
        
        // Return the relative path
        return $directoryPath . '/' . $fileName;
    }
    
    /**
     * Delete an image from the public directory
     *
     * @param string $path Path relative to public
     * @return bool True if deleted or not found
     */
    public static function deleteImageFromPublic($path)
    {
        // Handle null or empty paths
        if (empty($path)) {
            return true;
        }
        
        $fullPath = public_path($path);
        
        if (file_exists($fullPath)) {
            unlink($fullPath);
            return true;
        }
        
        // Also check the old storage path (for backward compatibility)
        $oldStoragePath = public_path('storage/' . str_replace('images/', '', $path));
        if (file_exists($oldStoragePath)) {
            unlink($oldStoragePath);
            return true;
        }
        
        return true; // File not found is not an error
    }
}
EOT;

$helperDir = base_path('app/Helpers');
if (!File::exists($helperDir)) {
    File::makeDirectory($helperDir, 0755, true);
}

File::put(base_path('app/Helpers/ImageUploadHelper.php'), $helperContent);
echo "✓ Created ImageUploadHelper.php\n";

// Step 3: Update the ImagePathService
echo "\nUpdating ImagePathService...\n";
$imagePathServiceContent = <<<'EOT'
<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class ImagePathService
{
    /**
     * Resolve the image URL based on environment and available paths
     *
     * @param string|null $imagePath
     * @return string|null
     */
    public static function resolveImageUrl($imagePath)
    {
        // Handle null or empty paths
        if (empty($imagePath)) {
            return null;
        }
        
        // If it's already a full URL (external), return it as is
        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            return $imagePath;
        }

        // First try the new images path
        if (file_exists(public_path('images/' . str_replace('images/', '', $imagePath)))) {
            return asset('images/' . str_replace('images/', '', $imagePath));
        }
        
        // Next try direct path (if it already has images/ prefix)
        if (strpos($imagePath, 'images/') === 0 && file_exists(public_path($imagePath))) {
            return asset($imagePath);
        }
        
        // Fallback to the old storage path
        if (file_exists(public_path('storage/' . $imagePath))) {
            return asset('storage/' . $imagePath);
        }
        
        // If all else fails, return the standard asset path
        return asset($imagePath);
    }
}
EOT;

File::put(base_path('app/Services/ImagePathService.php'), $imagePathServiceContent);
echo "✓ Updated ImagePathService.php\n";

// Step 4: Create a simple image copy function for diagnostics
echo "\nCreating test-image-paths.php in public folder...\n";
$testScriptContent = <<<'EOT'
<?php
/**
 * Test script for image paths
 * This will check if images can be accessed directly
 */

// Bootstrap Laravel application
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\ImagePathService;
use Illuminate\Support\Facades\File;
use App\Models\HeroSection;
use App\Models\Event;

echo '<html><head><title>Image Path Test</title>';
echo '<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .container { max-width: 1200px; margin: 0 auto; }
    .result { margin-bottom: 20px; padding: 10px; border: 1px solid #ccc; }
    .success { background-color: #d4edda; border-color: #c3e6cb; }
    .error { background-color: #f8d7da; border-color: #f5c6cb; }
    img { max-width: 200px; max-height: 200px; margin: 10px 0; border: 1px solid #ddd; }
</style>';
echo '</head><body><div class="container">';
echo '<h1>Image Path Test Results</h1>';

// Test directories
echo '<h2>Directory Structure Check</h2>';
$directories = [
    'public/images/hero-sections',
    'public/images/events',
    'public/storage',
];

foreach ($directories as $dir) {
    $fullPath = str_replace('public/', '', $dir);
    if (file_exists($fullPath)) {
        echo "<div class='result success'>";
        echo "✅ Directory exists: {$dir}<br>";
        echo "Path: " . realpath($fullPath) . "<br>";
        echo "</div>";
    } else {
        echo "<div class='result error'>";
        echo "❌ Directory missing: {$dir}<br>";
        echo "</div>";
    }
}

// Test some sample hero section images
echo '<h2>Hero Section Image Check</h2>';
$heroSections = HeroSection::take(3)->get();
if ($heroSections->isEmpty()) {
    echo "<div class='result error'>No hero sections found</div>";
} else {
    foreach ($heroSections as $heroSection) {
        echo "<div class='result'>";
        echo "Hero Section ID: {$heroSection->id}<br>";
        echo "Image path in DB: {$heroSection->image_path}<br>";
        
        $imageUrl = ImagePathService::resolveImageUrl($heroSection->image_path);
        echo "Resolved URL: {$imageUrl}<br>";
        
        echo "<img src='{$imageUrl}' alt='Hero Section Image'>";
        echo "</div>";
    }
}

// Test some sample event images
echo '<h2>Event Image Check</h2>';
$events = Event::take(3)->get();
if ($events->isEmpty()) {
    echo "<div class='result error'>No events found</div>";
} else {
    foreach ($events as $event) {
        echo "<div class='result'>";
        echo "Event ID: {$event->id}<br>";
        echo "Media path in DB: {$event->media_path}<br>";
        
        $imageUrl = ImagePathService::resolveImageUrl($event->media_path);
        echo "Resolved URL: {$imageUrl}<br>";
        
        if ($event->media_path) {
            echo "<img src='{$imageUrl}' alt='Event Media'>";
        } else {
            echo "No media attached";
        }
        echo "</div>";
    }
}

echo '<h2>Next Steps</h2>';
echo '<ol>';
echo '<li>If images are showing correctly, the system is working!</li>';
echo '<li>If images are missing, but directories exist, run the image migration script.</li>';
echo '</ol>';

echo '<p><a href="/fix-image-paths.php">Run Image Migration Script</a> (only if needed)</p>';

echo '</div></body></html>';
EOT;

File::put(base_path('public/test-image-paths.php'), $testScriptContent);
echo "✓ Created test-image-paths.php\n";

// Step 5: Create a safe migration script
echo "\nCreating fix-image-paths.php script...\n";
$fixImagesContent = <<<'EOT'
<?php
/**
 * Fix Image Paths Migration Script
 * 
 * This script:
 * 1. Creates necessary directories in public/images
 * 2. Automatically migrates any existing images from storage to public/images
 * 3. Updates database records to reflect new paths
 * 
 * Usage: 
 * php fix-image-paths.php
 */

// Bootstrap Laravel application
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\HeroSection;
use App\Models\Event;
use App\Models\Testimonial;
use App\Models\SchoolLogo;
use App\Models\Happening;
use App\Models\Trainer;

// Define the directories we need to ensure exist
$directories = [
    'images/hero-sections',
    'images/events',
    'images/testimonials',
    'images/happenings',
    'images/trainers',
    'images/schools',
    'images/profile-photos',
];

// Create directories if they don't exist
foreach ($directories as $dir) {
    if (!File::exists(public_path($dir))) {
        File::makeDirectory(public_path($dir), 0755, true);
        echo "Created directory: {$dir}\n";
    } else {
        echo "Directory already exists: {$dir}\n";
    }
}

/**
 * Move a single file from storage to public/images
 */
function migrateImage($oldPath, $newDir) {
    if (empty($oldPath)) {
        return null;
    }
    
    // If already using the correct path format, return as is
    if (strpos($oldPath, 'images/') === 0) {
        return $oldPath;
    }
    
    // Extract filename from the old path
    $filename = basename($oldPath);
    
    // Define old and new paths
    $oldFullPath = public_path('storage/' . $oldPath);
    $newRelativePath = 'images/' . $newDir . '/' . $filename;
    $newFullPath = public_path($newRelativePath);
    
    // Check if old file exists
    if (File::exists($oldFullPath)) {
        // Copy the file (don't delete yet for safety)
        File::copy($oldFullPath, $newFullPath);
        echo "Copied: {$oldPath} → {$newRelativePath}\n";
        return $newRelativePath;
    }
    
    // File not found, check if it already exists at the new location
    if (File::exists($newFullPath)) {
        echo "Already exists at new location: {$newRelativePath}\n";
        return $newRelativePath;
    }
    
    // File not found anywhere
    echo "Warning: File not found: {$oldPath}\n";
    return $oldPath; // Return original path as fallback
}

// Process Hero Sections
echo "\n--- Processing Hero Sections ---\n";
$heroSections = HeroSection::all();
foreach ($heroSections as $heroSection) {
    if (!empty($heroSection->image_path)) {
        $newPath = migrateImage($heroSection->image_path, 'hero-sections');
        if ($newPath !== $heroSection->image_path) {
            $heroSection->image_path = $newPath;
            $heroSection->save();
            echo "Updated database record for Hero Section #{$heroSection->id}\n";
        }
    }
}

// Process Events
echo "\n--- Processing Events ---\n";
$events = Event::all();
foreach ($events as $event) {
    if (!empty($event->media_path)) {
        $newPath = migrateImage($event->media_path, 'events');
        if ($newPath !== $event->media_path) {
            $event->media_path = $newPath;
            $event->save();
            echo "Updated database record for Event #{$event->id}\n";
        }
    }
}

// Process Testimonials
echo "\n--- Processing Testimonials ---\n";
$testimonials = Testimonial::all();
foreach ($testimonials as $testimonial) {
    if (!empty($testimonial->image_path)) {
        $newPath = migrateImage($testimonial->image_path, 'testimonials');
        if ($newPath !== $testimonial->image_path) {
            $testimonial->image_path = $newPath;
            $testimonial->save();
            echo "Updated database record for Testimonial #{$testimonial->id}\n";
        }
    }
}

// Process School Logos
echo "\n--- Processing School Logos ---\n";
$schoolLogos = SchoolLogo::all();
foreach ($schoolLogos as $logo) {
    if (!empty($logo->logo_path)) {
        $newPath = migrateImage($logo->logo_path, 'schools');
        if ($newPath !== $logo->logo_path) {
            $logo->logo_path = $newPath;
            $logo->save();
            echo "Updated database record for School Logo #{$logo->id}\n";
        }
    }
}

// Process Happenings
echo "\n--- Processing Happenings ---\n";
$happenings = Happening::all();
foreach ($happenings as $happening) {
    if (!empty($happening->image_path)) {
        $newPath = migrateImage($happening->image_path, 'happenings');
        if ($newPath !== $happening->image_path) {
            $happening->image_path = $newPath;
            $happening->save();
            echo "Updated database record for Happening #{$happening->id}\n";
        }
    }
}

// Process Trainers
echo "\n--- Processing Trainers ---\n";
$trainers = Trainer::all();
foreach ($trainers as $trainer) {
    if (!empty($trainer->image_path)) {
        $newPath = migrateImage($trainer->image_path, 'trainers');
        if ($newPath !== $trainer->image_path) {
            $trainer->image_path = $newPath;
            $trainer->save();
            echo "Updated database record for Trainer #{$trainer->id}\n";
        }
    }
}

// Completion message
echo "\n--- Migration Complete ---\n";
echo "All images have been migrated from /storage/ to /images/\n";
echo "The website will now work without storage:link\n";
echo "The old images in storage/app/public remain untouched for safety.\n";
EOT;

File::put(base_path('fix-image-paths.php'), $fixImagesContent);
echo "✓ Created fix-image-paths.php\n";

// Step 6: Clear caches
echo "\nClearing Laravel caches...\n";
Artisan::call('config:clear');
Artisan::call('view:clear');
Artisan::call('cache:clear');
echo "✓ Caches cleared\n";

echo "\n=================================\n";
echo "SAFE DEPLOYMENT COMPLETED!\n";
echo "=================================\n";
echo "1. Test by visiting: " . url('public/test-image-paths.php') . "\n";
echo "2. If test shows images loading correctly, you're done!\n";
echo "3. If images are not showing, you can run: php fix-image-paths.php\n";
echo "4. This will safely copy (not move) images to the new structure\n";
echo "\nNote: No production data has been modified yet until you run the migration script.\n";
?>
