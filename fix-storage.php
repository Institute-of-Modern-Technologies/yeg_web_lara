<?php

/**
 * Script to diagnose and fix Laravel storage issues for school logos
 */

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

echo "==== School Logo Storage Diagnostics ====\n\n";

// Check if storage link exists
echo "Checking storage symlink...\n";
$storageLink = public_path('storage');
$storageTarget = storage_path('app/public');

if (file_exists($storageLink)) {
    echo "✓ Storage link exists at: {$storageLink}\n";
    
    // On Windows, we need to check if it's pointing to the correct location
    echo "Target should be: {$storageTarget}\n";
    
    // Try recreating the symlink
    echo "Recreating symlink to ensure it's correct...\n";
    
    // On Windows, we need to use directory junction
    if (PHP_OS_FAMILY === 'Windows') {
        // Remove existing link first
        if (is_dir($storageLink)) {
            // Use rmdir for symlinks on Windows
            rmdir($storageLink);
            echo "Removed existing storage link\n";
        }
        
        // Create the directory junction
        exec('mklink /J "' . str_replace('/', '\\', $storageLink) . '" "' . 
             str_replace('/', '\\', $storageTarget) . '"', $output, $returnCode);
        
        if ($returnCode === 0) {
            echo "✓ Successfully created directory junction\n";
            foreach ($output as $line) {
                echo "  {$line}\n";
            }
        } else {
            echo "✗ Failed to create directory junction, error code: {$returnCode}\n";
        }
    } else {
        // For Linux/Mac
        @unlink($storageLink);
        symlink($storageTarget, $storageLink);
        echo "✓ Recreated symlink\n";
    }
} else {
    echo "✗ Storage link does not exist at: {$storageLink}\n";
    echo "Creating storage link...\n";
    
    if (PHP_OS_FAMILY === 'Windows') {
        exec('mklink /J "' . str_replace('/', '\\', $storageLink) . '" "' . 
             str_replace('/', '\\', $storageTarget) . '"');
    } else {
        symlink($storageTarget, $storageLink);
    }
    
    if (file_exists($storageLink)) {
        echo "✓ Created storage link successfully\n";
    } else {
        echo "✗ Failed to create storage link\n";
    }
}

// Check if school_logos directory exists in both locations
$storageSchoolLogosDir = storage_path('app/public/school_logos');
$publicSchoolLogosDir = public_path('storage/school_logos');

echo "\nChecking school_logos directories...\n";
echo "Storage path: {$storageSchoolLogosDir}\n";
if (!is_dir($storageSchoolLogosDir)) {
    echo "✗ school_logos directory does not exist in storage, creating...\n";
    mkdir($storageSchoolLogosDir, 0755, true);
    echo "✓ Created directory\n";
} else {
    echo "✓ school_logos directory exists in storage\n";
}

echo "Public path: {$publicSchoolLogosDir}\n";
if (file_exists($publicSchoolLogosDir)) {
    echo "✓ school_logos directory exists in public storage\n";
} else {
    echo "✗ school_logos directory does not exist in public storage\n";
    echo "  This should be fixed now that the storage link has been recreated\n";
}

// List files in storage/app/public/school_logos
echo "\nListing files in storage/app/public/school_logos:\n";
$files = Storage::disk('public')->files('school_logos');
if (count($files) > 0) {
    foreach ($files as $file) {
        $size = Storage::disk('public')->size($file);
        echo "- {$file} ({$size} bytes)\n";
        
        // Check if file exists in public storage
        $publicFile = public_path('storage/' . $file);
        if (file_exists($publicFile)) {
            echo "  ✓ File also exists in public storage\n";
        } else {
            echo "  ✗ File does not exist in public storage\n";
        }
    }
} else {
    echo "No files found\n";
}

// Check and list database records
echo "\nChecking database records for school logos:\n";
$logos = \App\Models\SchoolLogo::all();
if ($logos->count() > 0) {
    foreach ($logos as $logo) {
        echo "- {$logo->name} (ID: {$logo->id})\n";
        echo "  Logo path: {$logo->logo_path}\n";
        
        if ($logo->logo_path) {
            // Check if file exists in storage
            if (Storage::disk('public')->exists($logo->logo_path)) {
                echo "  ✓ File exists in storage\n";
            } else {
                echo "  ✗ File does not exist in storage\n";
            }
            
            // URL that should work
            $url = url('storage/' . $logo->logo_path);
            echo "  URL: {$url}\n";
        } else {
            echo "  ✗ No logo path defined\n";
        }
    }
} else {
    echo "No school logo records found in database\n";
}

echo "\n==== Diagnostics Complete ====\n";
echo "If issues persist, please check Laravel's filesystem configuration in config/filesystems.php\n";
