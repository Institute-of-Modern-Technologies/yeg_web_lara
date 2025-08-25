<?php
/**
 * Script to check and fix image paths in database
 * This script helps verify that images referenced in database exist in the filesystem
 * and can help identify mismatches.
 */

// Bootstrap the Laravel application
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "======================================================\n";
echo "IMAGE PATH CHECKER AND FIXER\n";
echo "======================================================\n";

// Check hero sections
$heroSections = App\Models\HeroSection::all();
echo "\nChecking {$heroSections->count()} hero sections:\n";
echo "------------------------------------------------------\n";

foreach ($heroSections as $heroSection) {
    echo "Hero Section #{$heroSection->id}: {$heroSection->title}\n";
    echo "Image path in DB: {$heroSection->image_path}\n";
    
    // Check if image exists in the expected location
    $fullPath = public_path($heroSection->image_path);
    $exists = file_exists($fullPath);
    
    echo "Image exists: " . ($exists ? "YES" : "NO") . "\n";
    
    if (!$exists) {
        // Try to locate it in storage path
        $storagePath = public_path('storage/' . str_replace('images/', '', $heroSection->image_path));
        $existsInStorage = file_exists($storagePath);
        
        echo "Found in storage path: " . ($existsInStorage ? "YES" : "NO") . "\n";
        
        if ($existsInStorage) {
            echo "Would you like to copy this image to the correct location? (y/n): ";
            $handle = fopen("php://stdin", "r");
            $line = trim(fgets($handle));
            if ($line == 'y') {
                // Create directory if it doesn't exist
                $directory = dirname($fullPath);
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                    echo "Created directory: {$directory}\n";
                }
                
                // Copy the file
                copy($storagePath, $fullPath);
                echo "Copied from: {$storagePath}\n";
                echo "To: {$fullPath}\n";
            }
        }
    }
    
    echo "------------------------------------------------------\n";
}

// Check testimonial images
if (class_exists('App\Models\Testimonial')) {
    $testimonials = App\Models\Testimonial::all();
    echo "\nChecking {$testimonials->count()} testimonials:\n";
    echo "------------------------------------------------------\n";
    
    foreach ($testimonials as $testimonial) {
        if (empty($testimonial->image_path)) {
            echo "Testimonial #{$testimonial->id}: {$testimonial->name} - No image\n";
            continue;
        }
        
        echo "Testimonial #{$testimonial->id}: {$testimonial->name}\n";
        echo "Image path in DB: {$testimonial->image_path}\n";
        
        // Check if image exists in the expected location
        $fullPath = public_path($testimonial->image_path);
        $exists = file_exists($fullPath);
        
        echo "Image exists: " . ($exists ? "YES" : "NO") . "\n";
        
        if (!$exists) {
            // Try to locate it in storage path
            $storagePath = public_path('storage/' . str_replace('images/', '', $testimonial->image_path));
            $existsInStorage = file_exists($storagePath);
            
            echo "Found in storage path: " . ($existsInStorage ? "YES" : "NO") . "\n";
            
            if ($existsInStorage) {
                echo "Would you like to copy this image to the correct location? (y/n): ";
                $handle = fopen("php://stdin", "r");
                $line = trim(fgets($handle));
                if ($line == 'y') {
                    // Create directory if it doesn't exist
                    $directory = dirname($fullPath);
                    if (!file_exists($directory)) {
                        mkdir($directory, 0755, true);
                        echo "Created directory: {$directory}\n";
                    }
                    
                    // Copy the file
                    copy($storagePath, $fullPath);
                    echo "Copied from: {$storagePath}\n";
                    echo "To: {$fullPath}\n";
                }
            }
        }
        
        echo "------------------------------------------------------\n";
    }
}

// Add more image checks for other models as needed

echo "\nImage check completed!\n";
