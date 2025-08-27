<?php
/**
 * Missing Partner School Image Fix Script
 * 
 * This script creates dummy images for missing partner school images
 * and ensures all paths use the public/images/partner-schools format.
 */

// Bootstrap the Laravel application
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "======================================================\n";
echo "MISSING PARTNER SCHOOL IMAGES FIX\n";
echo "======================================================\n";
echo "Running on: " . gethostname() . "\n";
echo "Environment: " . app()->environment() . "\n";
echo "------------------------------------------------------\n\n";

// Create destination directory if it doesn't exist
$destinationDir = public_path('images/partner-schools');
if (!file_exists($destinationDir)) {
    echo "Creating destination directory: images/partner-schools\n";
    if (mkdir($destinationDir, 0755, true)) {
        echo "Created directory successfully\n";
    } else {
        echo "Failed to create directory - check permissions\n";
        echo "Attempting with shell command...\n";
        shell_exec("mkdir -p " . escapeshellarg($destinationDir));
        echo "Directory should be created now\n";
    }
} else {
    echo "Destination directory already exists\n";
}

// Get all partner schools from the database
echo "\nFinding partner schools with image paths to fix...\n";
$partnerSchools = DB::table('partnered_schools')->get();
echo "Found " . $partnerSchools->count() . " partner schools to check\n\n";

// Create a default image to use for missing images
$defaultImagePath = public_path('img/default-thumbnail.jpg');
$defaultImageDestPath = public_path('images/partner-schools/default-partner-school.jpg');

if (file_exists($defaultImagePath) && !file_exists($defaultImageDestPath)) {
    echo "Copying default image for use with missing partner school images...\n";
    copy($defaultImagePath, $defaultImageDestPath);
    echo "Default image copied to images/partner-schools/default-partner-school.jpg\n\n";
} elseif (!file_exists($defaultImagePath)) {
    echo "Default image not found at {$defaultImagePath}. Creating a blank image...\n";
    
    // Create a blank image as fallback
    $blankImage = imagecreatetruecolor(300, 200);
    $bgColor = imagecolorallocate($blankImage, 240, 240, 240);
    $textColor = imagecolorallocate($blankImage, 80, 80, 80);
    
    imagefill($blankImage, 0, 0, $bgColor);
    imagestring($blankImage, 5, 60, 90, 'Partner School Image', $textColor);
    
    imagejpeg($blankImage, $defaultImageDestPath, 90);
    imagedestroy($blankImage);
    
    echo "Created blank default image at images/partner-schools/default-partner-school.jpg\n\n";
}

// Check each partner school image and create/update as needed
$updatedCount = 0;
$fixedCount = 0;

foreach ($partnerSchools as $school) {
    echo "Partner School #{$school->id}: {$school->name}\n";
    
    // Skip if no image path
    if (empty($school->image_path)) {
        echo "  No logo path found, updating with default image\n";
        
        // Set to default image path
        $newPath = 'images/partner-schools/default-partner-school.jpg';
        DB::table('partnered_schools')
            ->where('id', $school->id)
            ->update(['image_path' => $newPath]);
        
        echo "  ✓ Updated database with default image path\n";
        $updatedCount++;
        continue;
    }
    
    $oldPath = $school->image_path;
    echo "  Current logo path: {$oldPath}\n";
    
    // Check if the path is already correct and file exists
    if (strpos($oldPath, 'images/partner-schools') === 0) {
        if (file_exists(public_path($oldPath))) {
            echo "  ✓ Path already in correct format and file exists\n";
            continue;
        } else {
            echo "  ✗ Path is in correct format but file is missing\n";
            
            // Try to find the image with the same name in possible locations
            $fileName = basename($oldPath);
            $found = false;
            
            $possibleLocations = [
                public_path('partner-schools/' . $fileName),
                public_path('storage/partner-schools/' . $fileName),
                storage_path('app/public/partner-schools/' . $fileName)
            ];
            
            foreach ($possibleLocations as $location) {
                if (file_exists($location)) {
                    echo "  Found file at {$location}, copying to correct location\n";
                    copy($location, public_path($oldPath));
                    echo "  ✓ Copied file to correct location\n";
                    $found = true;
                    $fixedCount++;
                    break;
                }
            }
            
            if (!$found) {
                echo "  Could not find original file, copying default image\n";
                copy($defaultImageDestPath, public_path($oldPath));
                echo "  ✓ Copied default image as replacement\n";
                $fixedCount++;
            }
        }
    } else {
        // Incorrect path format, update with default image
        echo "  Path is in incorrect format\n";
        
        // Set to default image path
        $newPath = 'images/partner-schools/default-partner-school.jpg';
        DB::table('partnered_schools')
            ->where('id', $school->id)
            ->update(['image_path' => $newPath]);
        
        echo "  ✓ Updated database with default image path\n";
        $updatedCount++;
    }
    
    echo "----------------------------------------\n";
}

// Set permissions on the directory
echo "\nSetting directory permissions...\n";
chmod($destinationDir, 0755);
echo "Set permissions on: images/partner-schools\n";

echo "\n======================================================\n";
echo "SUMMARY\n";
echo "======================================================\n";
echo "Total partner schools: " . $partnerSchools->count() . "\n";
echo "Images updated with default: " . $updatedCount . "\n";
echo "Missing images fixed: " . $fixedCount . "\n";
echo "======================================================\n";
echo "\nScript execution complete.\n";
echo "All partner schools should now have valid images in public/images/partner-schools\n";
