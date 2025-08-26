<?php
/**
 * Fix Partner School Images Script
 * 
 * This script:
 * 1. Finds all partner school images with 'storage/' in the path
 * 2. Copies them from storage/partner-schools to public/images/partner-schools
 * 3. Updates database records to use the correct image/partner-schools path format
 */

// Bootstrap the Laravel application
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "======================================================\n";
echo "FIX PARTNER SCHOOL IMAGES\n";
echo "======================================================\n";

// Create the destination directory if it doesn't exist
$destinationDir = public_path('images/partner-schools');
if (!file_exists($destinationDir)) {
    if (mkdir($destinationDir, 0755, true)) {
        echo "Created directory: images/partner-schools\n";
    } else {
        echo "FAILED to create directory: images/partner-schools\n";
        exit(1);
    }
}

// Get all partner schools
$partnerSchools = DB::table('partner_schools')->get();
echo "Found " . $partnerSchools->count() . " partner schools\n\n";

$fixedCount = 0;
$errorCount = 0;

foreach ($partnerSchools as $school) {
    echo "Processing: {$school->name} (ID: {$school->id})\n";
    
    // Skip if no image path
    if (empty($school->image_path)) {
        echo "  No image path, skipping\n";
        continue;
    }
    
    echo "  Current image path: {$school->image_path}\n";
    
    // Check if path is already correct
    if (strpos($school->image_path, 'images/partner-schools/') === 0) {
        echo "  Path already in correct format\n";
        continue;
    }
    
    // Get the filename from the path
    $filename = basename($school->image_path);
    
    // Define source and destination paths
    $sourcePath = null;
    
    // Check if it's a storage path
    if (strpos($school->image_path, 'storage/') === 0) {
        $relativePath = str_replace('storage/', '', $school->image_path);
        $sourcePath = public_path('storage/' . $relativePath);
    } else if (strpos($school->image_path, 'partner-schools/') === 0) {
        // It's a direct partner-schools path
        $sourcePath = public_path('storage/' . $school->image_path);
    } else {
        // Try all possible locations
        $possiblePaths = [
            public_path('storage/' . $school->image_path),
            public_path('storage/partner-schools/' . $filename),
            public_path($school->image_path),
        ];
        
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                $sourcePath = $path;
                break;
            }
        }
    }
    
    // If we couldn't find the source file
    if (!$sourcePath || !file_exists($sourcePath)) {
        echo "  ERROR: Source file not found. Tried all possible paths.\n";
        $errorCount++;
        continue;
    }
    
    $destinationPath = $destinationDir . '/' . $filename;
    
    // Copy the file
    if (copy($sourcePath, $destinationPath)) {
        echo "  Copied file to: images/partner-schools/{$filename}\n";
        
        // Update database record
        $newPath = 'images/partner-schools/' . $filename;
        DB::table('partner_schools')
            ->where('id', $school->id)
            ->update(['image_path' => $newPath]);
        
        echo "  Updated database record\n";
        $fixedCount++;
    } else {
        echo "  ERROR: Failed to copy file\n";
        $errorCount++;
    }
    
    echo "----------------------------------------\n";
}

echo "\n======================================================\n";
echo "SUMMARY\n";
echo "======================================================\n";
echo "Total partner schools: " . $partnerSchools->count() . "\n";
echo "Fixed: {$fixedCount}\n";
echo "Errors: {$errorCount}\n";

if ($errorCount > 0) {
    echo "\nThere were some errors. Please check the output above.\n";
} else {
    echo "\nAll partner school images have been fixed successfully!\n";
}

echo "Remember to run this script on your production server to fix the images there as well.\n";
