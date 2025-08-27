<?php
/**
 * Production Partner Schools Image Fix Script
 * 
 * This script:
 * 1. Identifies partner school images with incorrect paths
 * 2. Creates proper directory structure (images/partner-schools)
 * 3. Copies images to the correct location
 * 4. Updates database records with correct paths
 * 
 * Run on production server after deploying code changes.
 */

// Bootstrap the Laravel application
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

echo "======================================================\n";
echo "PRODUCTION PARTNER SCHOOLS IMAGE FIX\n";
echo "======================================================\n";
echo "Running on: " . gethostname() . "\n";
echo "Environment: " . app()->environment() . "\n";
echo "------------------------------------------------------\n\n";

// Create destination directory if it doesn't exist
$destinationDir = public_path('images/partner-schools');
if (!file_exists($destinationDir)) {
    if (mkdir($destinationDir, 0755, true)) {
        echo "Created directory: images/partner-schools\n";
    } else {
        echo "FAILED to create directory: images/partner-schools\n";
        exit(1);
    }
    chmod($destinationDir, 0755);
}

// Get all partner schools
$partnerSchools = DB::table('partner_schools')->get();
echo "Found " . $partnerSchools->count() . " partner schools\n\n";

$fixedCount = 0;
$errorCount = 0;
$alreadyFixedCount = 0;
$noImageCount = 0;

foreach ($partnerSchools as $school) {
    echo "Processing: {$school->name} (ID: {$school->id})\n";
    
    // Skip if no image path
    if (empty($school->image_path)) {
        echo "  No image path, skipping\n";
        $noImageCount++;
        continue;
    }
    
    echo "  Current image path: {$school->image_path}\n";
    
    // Check if path is already correct
    if (strpos($school->image_path, 'images/partner-schools/') === 0) {
        echo "  Path already in correct format\n";
        $alreadyFixedCount++;
        continue;
    }
    
    // Define possible source paths
    $possibleSourcePaths = [
        // Direct path in production root
        public_path($school->image_path),
        
        // Path in storage/app/public
        storage_path('app/public/' . $school->image_path),
        
        // Legacy storage paths
        public_path('storage/' . $school->image_path),
        
        // Other common patterns
        storage_path('app/public/partner-schools/' . basename($school->image_path)),
        public_path('partner-schools/' . basename($school->image_path))
    ];
    
    // Find the source file
    $sourcePath = null;
    foreach ($possibleSourcePaths as $path) {
        if (file_exists($path)) {
            $sourcePath = $path;
            echo "  Found image at: {$path}\n";
            break;
        }
    }
    
    // If we couldn't find the source file
    if (!$sourcePath) {
        echo "  ERROR: Source file not found. Tried all possible paths.\n";
        $errorCount++;
        continue;
    }
    
    // Get filename from path
    $filename = basename($school->image_path);
    
    // Generate new filename if needed (keep the original if it's a reasonable name)
    if (strlen($filename) > 100 || !preg_match('/\.(jpg|jpeg|png|gif|svg)$/i', $filename)) {
        $extension = pathinfo($sourcePath, PATHINFO_EXTENSION);
        if (empty($extension)) {
            $extension = 'jpg'; // Default extension
        }
        $filename = time() . '_' . uniqid() . '_' . preg_replace('/[^A-Za-z0-9\-\.]/', '', str_replace(' ', '-', $school->name)) . '.' . $extension;
    }
    
    // Define the destination path
    $destinationPath = $destinationDir . '/' . $filename;
    
    // Copy the file
    if (copy($sourcePath, $destinationPath)) {
        echo "  Copied file to: images/partner-schools/{$filename}\n";
        
        // Update database record
        $newPath = 'images/partner-schools/' . $filename;
        DB::table('partner_schools')
            ->where('id', $school->id)
            ->update(['image_path' => $newPath]);
        
        echo "  Updated database record with new path: {$newPath}\n";
        $fixedCount++;
    } else {
        echo "  ERROR: Failed to copy file\n";
        $errorCount++;
    }
    
    echo "----------------------------------------\n";
}

// Set permissions on the directory
echo "\nSetting directory permissions...\n";
chmod($destinationDir, 0755);
echo "Set permissions on: images/partner-schools\n";

// Check blade templates for incorrect paths
echo "\nChecking blade templates for partner-schools path references...\n";
$bladeFiles = glob(base_path('resources/views/**/*.blade.php'), GLOB_BRACE);
$assetStoragePatterns = [
    'asset(\'storage/partner-schools/',
    'asset("/storage/partner-schools/',
    'asset(\'/storage/partner-schools/',
    'asset(\'partner-schools/',
    'asset("/partner-schools/',
    'asset(\'/partner-schools/'
];

$fileFixCount = 0;

foreach ($bladeFiles as $file) {
    $content = file_get_contents($file);
    $originalContent = $content;
    $modified = false;
    
    foreach ($assetStoragePatterns as $pattern) {
        // Replace with the correct path format
        $content = str_replace($pattern, 'asset(\'/images/partner-schools/', $content);
        
        // For file_exists checks
        if (strpos($content, 'file_exists(public_path(\'storage/partner-schools/')) !== false) {
            $content = str_replace(
                'file_exists(public_path(\'storage/partner-schools/',
                'file_exists(public_path(\'/images/partner-schools/',
                $content
            );
            $modified = true;
        }
        
        if (strpos($content, 'file_exists(public_path(\'partner-schools/')) !== false) {
            $content = str_replace(
                'file_exists(public_path(\'partner-schools/',
                'file_exists(public_path(\'/images/partner-schools/',
                $content
            );
            $modified = true;
        }
    }
    
    // Save the file if modified
    if ($content !== $originalContent) {
        file_put_contents($file, $content);
        echo "  Fixed path references in: " . basename($file) . "\n";
        $fileFixCount++;
    }
}

echo "\n======================================================\n";
echo "SUMMARY\n";
echo "======================================================\n";
echo "Total partner schools: " . $partnerSchools->count() . "\n";
echo "Fixed: {$fixedCount}\n";
echo "Already correct: {$alreadyFixedCount}\n";
echo "No image: {$noImageCount}\n";
echo "Errors: {$errorCount}\n";
echo "Blade templates fixed: {$fileFixCount}\n";

if ($errorCount > 0) {
    echo "\nWarning: There were some errors. Please check the output above.\n";
} else {
    echo "\nAll partner school images have been fixed successfully!\n";
}

echo "\nNext steps:\n";
echo "1. Ensure PHP has write permissions to the public/images directory\n";
echo "2. Make sure the controller is using ImageUploadHelper for new uploads\n";
echo "3. Test uploading a new partner school image\n";
echo "======================================================\n";
