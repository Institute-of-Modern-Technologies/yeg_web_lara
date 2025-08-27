<?php
/**
 * Production Partner Schools Image Fix Script (Final Version)
 * 
 * This script:
 * 1. Identifies partner school images with incorrect paths
 * 2. Creates proper directory structure (images/partner-schools)
 * 3. Copies images to the correct location
 * 4. Updates database records with correct paths
 * 
 * Compatible with PHP 5.x and PHP 7+
 * Run on production server after deploying code changes.
 */

// Bootstrap the Laravel application
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "======================================================\n";
echo "PRODUCTION PARTNER SCHOOLS IMAGE FIX (FINAL VERSION)\n";
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

// Check each partner school image
$updatedCount = 0;
$failedCount = 0;
$alreadyCorrectCount = 0;

foreach ($partnerSchools as $school) {
    echo "Partner School #{$school->id}: {$school->name}\n";
    
    // Skip if no image path
    if (empty($school->image_path)) {
        echo "  No logo path found, skipping\n";
        continue;
    }
    
    $oldPath = $school->image_path;
    echo "  Current logo path: {$oldPath}\n";
    
    // Check if the path is already correct (starts with images/partner-schools)
    if (strpos($oldPath, 'images/partner-schools') === 0) {
        echo "  ✓ Path already in correct format\n";
        $alreadyCorrectCount++;
        continue;
    }
    
    // Determine correct path format
    $fileName = basename($oldPath);
    $newPath = 'images/partner-schools/' . $fileName;
    
    // Try to find the source file in various locations
    $possibleSourcePaths = [
        public_path($oldPath),                      // public/partner-schools/file.jpg
        public_path('storage/' . $oldPath),         // public/storage/partner-schools/file.jpg
        storage_path('app/public/' . $oldPath),     // storage/app/public/partner-schools/file.jpg
        base_path('storage/app/public/' . $oldPath) // /path/to/project/storage/app/public/partner-schools/file.jpg
    ];
    
    $sourceFile = null;
    foreach ($possibleSourcePaths as $possiblePath) {
        echo "  Checking for file at: " . $possiblePath . "\n";
        if (file_exists($possiblePath)) {
            $sourceFile = $possiblePath;
            echo "  ✓ Found file at: {$sourceFile}\n";
            break;
        }
    }
    
    if ($sourceFile === null) {
        echo "  ✗ Could not find source file in any location\n";
        $failedCount++;
        continue;
    }
    
    // Copy the file to the new location
    $destinationFile = public_path($newPath);
    echo "  Copying to: {$destinationFile}\n";
    
    if (copy($sourceFile, $destinationFile)) {
        echo "  ✓ File copied successfully\n";
        
        // Update the database record
        DB::table('partnered_schools')
            ->where('id', $school->id)
            ->update(['image_path' => $newPath]);
        
        echo "  ✓ Database record updated\n";
        $updatedCount++;
    } else {
        echo "  ✗ Failed to copy file - check permissions\n";
        $failedCount++;
    }
    
    echo "----------------------------------------\n";
}

// Set permissions on the directory
echo "\nSetting directory permissions...\n";
chmod($destinationDir, 0755);
echo "Set permissions on: images/partner-schools\n";

// Check blade templates for incorrect paths
echo "\nChecking blade templates for partner-schools path references...\n";
$bladeFiles = glob(base_path('resources/views/*/*.blade.php'));
$fileFixCount = 0;

foreach ($bladeFiles as $file) {
    $content = file_get_contents($file);
    $originalContent = $content;
    
    // Replace asset paths - storage/partner-schools/ to images/partner-schools/
    $content = str_replace("asset('storage/partner-schools/", "asset('images/partner-schools/", $content);
    $content = str_replace("asset(\"/storage/partner-schools/", "asset(\"/images/partner-schools/", $content);
    $content = str_replace("asset('/storage/partner-schools/", "asset('/images/partner-schools/", $content);
    
    // Replace asset paths - partner-schools/ to images/partner-schools/
    $content = str_replace("asset('partner-schools/", "asset('images/partner-schools/", $content);
    $content = str_replace("asset(\"/partner-schools/", "asset(\"/images/partner-schools/", $content);
    $content = str_replace("asset('/partner-schools/", "asset('/images/partner-schools/", $content);
    
    // Replace file_exists checks
    $content = str_replace("file_exists(public_path('storage/partner-schools/", "file_exists(public_path('images/partner-schools/", $content);
    $content = str_replace("file_exists(public_path('partner-schools/", "file_exists(public_path('images/partner-schools/", $content);
    
    // Save the file if modified
    if ($content != $originalContent) {
        file_put_contents($file, $content);
        echo "  Fixed path references in: " . basename($file) . "\n";
        $fileFixCount++;
    }
}

echo "\n======================================================\n";
echo "SUMMARY\n";
echo "======================================================\n";
echo "Total partner schools: " . $partnerSchools->count() . "\n";
echo "Already correct: " . $alreadyCorrectCount . "\n";
echo "Successfully updated: " . $updatedCount . "\n";
echo "Failed to update: " . $failedCount . "\n";
echo "Fixed blade template files: " . $fileFixCount . "\n";
echo "======================================================\n";
echo "\nScript execution complete.\n";
echo "If images are still not showing, check permissions and try a hard refresh (Ctrl+F5).\n";
