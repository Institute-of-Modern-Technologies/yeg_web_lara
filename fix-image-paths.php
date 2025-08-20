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
echo "You can delete the old storage/app/public files once you've verified everything works correctly.\n";
