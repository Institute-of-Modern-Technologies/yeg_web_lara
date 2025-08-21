<?php
/**
 * Test and Create Public Image Directories
 * 
 * This script verifies and creates necessary image directories in public/images
 * while checking permissions and reporting issues
 */

// Bootstrap Laravel
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

// Define required directories
$directories = [
    'images',
    'images/hero-sections',
    'images/events',
    'images/testimonials',
    'images/happenings',
    'images/trainers',
    'images/schools',
    'images/profile-photos'
];

echo "<h1>Public Directory Status Check</h1>";
echo "<p>This script checks for required image directories and creates them if missing.</p>";

echo "<h2>Directory Status:</h2>";
echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
echo "<tr style='background-color: #eee;'><th>Path</th><th>Status</th><th>Permissions</th><th>Writable</th></tr>";

// PHP process user
echo "<p><strong>PHP running as user:</strong> " . exec('whoami') . "</p>";
echo "<p><strong>Document root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</p>";

$hasIssues = false;

foreach ($directories as $dir) {
    $fullPath = public_path($dir);
    $relativePath = $dir;
    $exists = File::exists($fullPath);
    
    if (!$exists) {
        // Try to create directory
        try {
            File::makeDirectory($fullPath, 0755, true);
            $created = true;
            $perms = substr(sprintf('%o', fileperms($fullPath)), -4);
        } catch (\Exception $e) {
            $created = false;
            $perms = "N/A";
            $hasIssues = true;
        }
        
        $isWritable = $created ? is_writable($fullPath) : false;
        
        $status = $created ? 
            "<span style='color:green;'>Created</span>" : 
            "<span style='color:red;'>Failed to create</span>";
    } else {
        $perms = substr(sprintf('%o', fileperms($fullPath)), -4);
        $isWritable = is_writable($fullPath);
        $status = "<span style='color:green;'>Exists</span>";
        
        if (!$isWritable) {
            $hasIssues = true;
        }
    }
    
    $writableStatus = $isWritable ? 
        "<span style='color:green;'>Yes</span>" : 
        "<span style='color:red;'>No</span>";
    
    echo "<tr>
        <td>{$relativePath}</td>
        <td>{$status}</td>
        <td>{$perms}</td>
        <td>{$writableStatus}</td>
    </tr>";
}

echo "</table>";

// Test creating a temporary file to verify write permissions
echo "<h2>Write Permission Test:</h2>";

$testDir = public_path('images');
if (File::exists($testDir)) {
    $testFile = $testDir . '/test_write_' . time() . '.txt';
    $writeSuccess = @file_put_contents($testFile, 'Test write permissions');
    
    if ($writeSuccess !== false) {
        echo "<p style='color:green;'>✓ Successfully created test file in images directory</p>";
        // Clean up
        @unlink($testFile);
        echo "<p>Test file removed.</p>";
    } else {
        echo "<p style='color:red;'>✗ Failed to create test file in images directory</p>";
        echo "<p>Error: " . error_get_last()['message'] . "</p>";
        $hasIssues = true;
    }
}

if ($hasIssues) {
    echo "<h2 style='color:red;'>⚠️ Issues Detected</h2>";
    echo "<p>Please fix the permission issues highlighted above. The web server user needs write access to these directories.</p>";
    
    echo "<h3>Recommended Fix:</h3>";
    echo "<pre>sudo chown -R www-data:www-data " . public_path('images') . "
sudo chmod -R 755 " . public_path('images') . "</pre>";
} else {
    echo "<h2 style='color:green;'>✓ All Directories Ready</h2>";
    echo "<p>Your image directories are properly configured and writable.</p>";
}
