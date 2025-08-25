<?php
/**
 * Production Image Fix Script
 * 
 * This script will:
 * 1. Create necessary image directories
 * 2. Copy images from storage/app/public to public/images
 * 3. Fix permissions on the directories
 * 
 * Run this script on production after deploying code changes.
 */

// Bootstrap the Laravel application
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "======================================================\n";
echo "PRODUCTION IMAGE FIX SCRIPT\n";
echo "======================================================\n";

// Define directories that need to be created and checked
$directories = [
    'public/images/hero-sections',
    'public/images/login',
    'public/images/testimonials',
    'public/images/events',
    'public/images/happenings'
];

// Create directories if they don't exist
echo "\nChecking and creating directories...\n";
foreach ($directories as $dir) {
    $fullPath = base_path($dir);
    if (!file_exists($fullPath)) {
        if (mkdir($fullPath, 0755, true)) {
            echo "Created directory: {$dir}\n";
        } else {
            echo "FAILED to create directory: {$dir}\n";
        }
    } else {
        echo "Directory exists: {$dir}\n";
    }
}

// Set permissions
echo "\nSetting directory permissions...\n";
foreach ($directories as $dir) {
    $fullPath = base_path($dir);
    if (file_exists($fullPath)) {
        if (chmod($fullPath, 0755)) {
            echo "Set permissions on: {$dir}\n";
        } else {
            echo "FAILED to set permissions on: {$dir}\n";
        }
        
        // Add write permission for group
        if (shell_exec("chmod g+w " . escapeshellarg($fullPath))) {
            echo "Added group write permission to: {$dir}\n";
        } else {
            echo "FAILED to add group write permission to: {$dir}\n";
        }
    }
}

// Copy images from storage to public
echo "\nCopying images from storage to public directories...\n";

// Map of storage directories to public directories
$directoryMappings = [
    'storage/app/public/hero-sections' => 'public/images/hero-sections',
    'storage/app/public/login' => 'public/images/login',
    'storage/app/public/testimonials' => 'public/images/testimonials',
    'storage/app/public/events' => 'public/images/events',
    'storage/app/public/happenings' => 'public/images/happenings'
];

foreach ($directoryMappings as $sourceDir => $targetDir) {
    $sourcePath = base_path($sourceDir);
    $targetPath = base_path($targetDir);
    
    if (file_exists($sourcePath)) {
        echo "Copying files from {$sourceDir} to {$targetDir}...\n";
        
        // Get all files in the source directory
        $files = glob("{$sourcePath}/*");
        
        if (empty($files)) {
            echo "No files found in {$sourceDir}\n";
            continue;
        }
        
        foreach ($files as $file) {
            $filename = basename($file);
            $destination = "{$targetPath}/{$filename}";
            
            if (copy($file, $destination)) {
                echo "Copied: {$filename}\n";
            } else {
                echo "FAILED to copy: {$filename}\n";
            }
        }
    } else {
        echo "Source directory does not exist: {$sourceDir}\n";
    }
}

// Check database records
echo "\nChecking database records for image paths...\n";

// Check hero sections
$heroSections = App\Models\HeroSection::all();
echo "\nChecking {$heroSections->count()} hero sections:\n";

foreach ($heroSections as $heroSection) {
    echo "Hero Section #{$heroSection->id}: {$heroSection->title}\n";
    echo "Image path in DB: {$heroSection->image_path}\n";
    
    // Check if the path format is correct (should be 'images/hero-sections/filename.ext')
    if (strpos($heroSection->image_path, 'images/hero-sections') !== 0) {
        echo "Incorrect path format. Would you like to fix it? (y/n): ";
        $handle = fopen("php://stdin", "r");
        $line = trim(fgets($handle));
        
        if ($line == 'y') {
            // Extract filename
            $filename = basename($heroSection->image_path);
            // Create new path
            $newPath = "images/hero-sections/{$filename}";
            
            // Update record
            $heroSection->image_path = $newPath;
            $heroSection->save();
            
            echo "Updated path to: {$newPath}\n";
        }
    } else {
        echo "Path format is correct.\n";
    }
    
    echo "------------------------------------------------------\n";
}

// Check testimonials if they exist
if (class_exists('App\Models\Testimonial')) {
    $testimonials = App\Models\Testimonial::all();
    echo "\nChecking {$testimonials->count()} testimonials:\n";
    
    foreach ($testimonials as $testimonial) {
        if (empty($testimonial->image_path)) {
            echo "Testimonial #{$testimonial->id}: {$testimonial->name} - No image\n";
            continue;
        }
        
        echo "Testimonial #{$testimonial->id}: {$testimonial->name}\n";
        echo "Image path in DB: {$testimonial->image_path}\n";
        
        // Check if the path format is correct
        if (strpos($testimonial->image_path, 'images/testimonials') !== 0) {
            echo "Incorrect path format. Would you like to fix it? (y/n): ";
            $handle = fopen("php://stdin", "r");
            $line = trim(fgets($handle));
            
            if ($line == 'y') {
                // Extract filename
                $filename = basename($testimonial->image_path);
                // Create new path
                $newPath = "images/testimonials/{$filename}";
                
                // Update record
                $testimonial->image_path = $newPath;
                $testimonial->save();
                
                echo "Updated path to: {$newPath}\n";
            }
        } else {
            echo "Path format is correct.\n";
        }
        
        echo "------------------------------------------------------\n";
    }
}

echo "\nImage fix script completed!\n";
echo "======================================================\n";
echo "Remember to check if images are displaying correctly on the website.\n";
echo "If issues persist, check server error logs for more details.\n";
