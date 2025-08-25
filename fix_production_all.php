<?php
/**
 * Comprehensive Production Fix Script
 * This script handles both image issues and user profile reset in one go
 */

// Bootstrap the Laravel application
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "==================================================\n";
echo "COMPREHENSIVE PRODUCTION FIX SCRIPT\n";
echo "==================================================\n";

// Function to check and create directories
function checkAndCreateDirectories() {
    echo "\n[1/5] CHECKING IMAGE DIRECTORIES\n";
    echo "--------------------------------------------------\n";
    
    $directories = [
        'public/images',
        'public/images/hero-sections',
        'public/images/login',
        'public/images/testimonials',
        'public/images/events',
        'public/images/happenings'
    ];
    
    foreach ($directories as $dir) {
        $fullPath = base_path($dir);
        if (!is_dir($fullPath)) {
            echo "Creating: {$dir}... ";
            if (mkdir($fullPath, 0777, true)) {
                echo "SUCCESS\n";
            } else {
                echo "FAILED - Permission denied\n";
            }
        } else {
            echo "{$dir} already exists\n";
        }
    }
}

// Function to set correct permissions
function setPermissions() {
    echo "\n[2/5] SETTING DIRECTORY PERMISSIONS\n";
    echo "--------------------------------------------------\n";
    
    $imageDirs = glob(base_path('public/images') . '/*', GLOB_ONLYDIR);
    array_unshift($imageDirs, base_path('public/images'));
    
    foreach ($imageDirs as $dir) {
        echo "Setting permissions for " . basename($dir) . "... ";
        
        // Try with chmod
        if (@chmod($dir, 0777)) {
            echo "SUCCESS\n";
        } else {
            echo "FAILED with PHP chmod - trying shell commands\n";
            
            // Try with shell command
            $command = "chmod -R 777 " . escapeshellarg($dir);
            echo "Running: {$command}\n";
            $output = shell_exec($command);
            echo "Result: " . ($output ?: 'No output') . "\n";
        }
    }
}

// Function to copy images from storage to public
function copyImages() {
    echo "\n[3/5] COPYING IMAGES FROM STORAGE TO PUBLIC\n";
    echo "--------------------------------------------------\n";
    
    $mappings = [
        'storage/app/public/hero-sections' => 'public/images/hero-sections',
        'storage/app/public/login' => 'public/images/login',
        'storage/app/public/testimonials' => 'public/images/testimonials',
        'storage/app/public/events' => 'public/images/events',
        'storage/app/public/happenings' => 'public/images/happenings'
    ];
    
    foreach ($mappings as $source => $destination) {
        $sourcePath = base_path($source);
        $destinationPath = base_path($destination);
        
        if (!is_dir($sourcePath)) {
            echo "Source {$source} not found, skipping\n";
            continue;
        }
        
        echo "Copying from {$source} to {$destination}...\n";
        
        $files = glob("{$sourcePath}/*");
        if (empty($files)) {
            echo "No files found in {$source}\n";
            continue;
        }
        
        foreach ($files as $file) {
            $filename = basename($file);
            $targetFile = "{$destinationPath}/{$filename}";
            
            if (@copy($file, $targetFile)) {
                echo "  ✓ {$filename}\n";
            } else {
                echo "  ✗ Failed to copy {$filename}\n";
            }
        }
    }
}

// Function to update database image references
function updateImagePathsInDatabase() {
    echo "\n[4/5] UPDATING DATABASE IMAGE PATHS\n";
    echo "--------------------------------------------------\n";
    
    // Check for hero sections table
    if (Schema::hasTable('hero_sections')) {
        $heroSections = DB::table('hero_sections')->get();
        echo "Found {$heroSections->count()} hero sections\n";
        
        foreach ($heroSections as $section) {
            echo "HeroSection #{$section->id}: ";
            
            if (!empty($section->image_path)) {
                $oldPath = $section->image_path;
                // If path starts with 'storage/', update it
                if (strpos($oldPath, 'storage/') === 0) {
                    $newPath = str_replace('storage/', 'images/', $oldPath);
                    
                    DB::table('hero_sections')
                        ->where('id', $section->id)
                        ->update(['image_path' => $newPath]);
                        
                    echo "Updated path from {$oldPath} to {$newPath}\n";
                } else {
                    echo "Path already correct: {$oldPath}\n";
                }
            } else {
                echo "No image path to update\n";
            }
        }
    } else {
        echo "hero_sections table not found\n";
    }
    
    // Check for testimonials table
    if (Schema::hasTable('testimonials')) {
        $testimonials = DB::table('testimonials')->get();
        echo "Found {$testimonials->count()} testimonials\n";
        
        foreach ($testimonials as $testimonial) {
            echo "Testimonial #{$testimonial->id}: ";
            
            if (!empty($testimonial->image_path)) {
                $oldPath = $testimonial->image_path;
                // If path starts with 'storage/', update it
                if (strpos($oldPath, 'storage/') === 0) {
                    $newPath = str_replace('storage/', 'images/', $oldPath);
                    
                    DB::table('testimonials')
                        ->where('id', $testimonial->id)
                        ->update(['image_path' => $newPath]);
                        
                    echo "Updated path from {$oldPath} to {$newPath}\n";
                } else {
                    echo "Path already correct: {$oldPath}\n";
                }
            } else {
                echo "No image path to update\n";
            }
        }
    } else {
        echo "testimonials table not found\n";
    }
}

// Function to reset Anthony's user profile
function resetAnthonyProfile() {
    echo "\n[5/5] RESETTING ANTHONY'S PROFILE\n";
    echo "--------------------------------------------------\n";
    
    // Get all users named Anthony
    $anthonyUsers = DB::table('users')->where('name', 'like', '%Anthony%')->get();
    
    if ($anthonyUsers->isEmpty()) {
        echo "No users found with name 'Anthony'\n";
        return;
    }
    
    echo "Found " . $anthonyUsers->count() . " users matching 'Anthony':\n";
    
    foreach ($anthonyUsers as $index => $user) {
        echo "#{$index}: ID {$user->id} | {$user->name} | {$user->email}\n";
    }
    
    echo "\nWhich Anthony would you like to reset? (Enter the # from above): ";
    $handle = fopen("php://stdin", "r");
    $line = trim(fgets($handle));
    $index = (int)$line;
    
    if (!isset($anthonyUsers[$index])) {
        echo "Invalid selection.\n";
        return;
    }
    
    $selectedUser = $anthonyUsers[$index];
    
    echo "\nResetting user {$selectedUser->name} (ID: {$selectedUser->id})\n";
    
    // Ask for new values
    echo "Enter new name (or press enter to keep '{$selectedUser->name}'): ";
    $handle = fopen("php://stdin", "r");
    $newName = trim(fgets($handle));
    $newName = empty($newName) ? $selectedUser->name : $newName;
    
    echo "Enter new email (or press enter to keep '{$selectedUser->email}'): ";
    $handle = fopen("php://stdin", "r");
    $newEmail = trim(fgets($handle));
    $newEmail = empty($newEmail) ? $selectedUser->email : $newEmail;
    
    echo "Reset profile photo? (y/n): ";
    $handle = fopen("php://stdin", "r");
    $resetPhoto = trim(fgets($handle));
    $profilePhoto = (strtolower($resetPhoto) === 'y') ? null : $selectedUser->profile_photo;
    
    // Update the user
    $updated = DB::table('users')
        ->where('id', $selectedUser->id)
        ->update([
            'name' => $newName,
            'email' => $newEmail,
            'profile_photo' => $profilePhoto,
            'updated_at' => now()
        ]);
    
    if ($updated) {
        echo "✓ User profile updated successfully!\n";
    } else {
        echo "✗ Failed to update user profile\n";
    }
}

// Main execution
try {
    echo "Starting comprehensive fix process...\n";
    
    // Step 1: Check and create directories
    checkAndCreateDirectories();
    
    // Step 2: Set permissions
    setPermissions();
    
    // Step 3: Copy images
    copyImages();
    
    // Step 4: Update database
    updateImagePathsInDatabase();
    
    // Step 5: Reset Anthony's profile if needed
    echo "\nDo you want to reset Anthony's profile? (y/n): ";
    $handle = fopen("php://stdin", "r");
    $resetAnthony = trim(fgets($handle));
    
    if (strtolower($resetAnthony) === 'y') {
        resetAnthonyProfile();
    } else {
        echo "Skipping Anthony's profile reset.\n";
    }
    
    echo "\n==================================================\n";
    echo "FIX PROCESS COMPLETED!\n";
    echo "==================================================\n";
    echo "If images are still not displaying, check:\n";
    echo "1. Browser cache (try hard refresh with Ctrl+F5)\n";
    echo "2. Web server file permissions\n";
    echo "3. Web server error logs\n";
    echo "4. Image file paths in browser inspector\n";
    
} catch (Exception $e) {
    echo "\nERROR: " . $e->getMessage() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
