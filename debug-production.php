<?php
/**
 * Debug Production Controller Script
 * 
 * This script checks if the production server is using the updated controller code
 */

// Bootstrap the Laravel application
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "======================================================\n";
echo "PRODUCTION CONTROLLER DEBUG\n";
echo "======================================================\n";
echo "Running on: " . gethostname() . "\n";
echo "Environment: " . app()->environment() . "\n";
echo "------------------------------------------------------\n\n";

// Check if ImageUploadHelper exists
echo "1. Checking ImageUploadHelper class...\n";
if (class_exists('App\Helpers\ImageUploadHelper')) {
    echo "   ✓ ImageUploadHelper class exists\n";
    
    // Check if the method exists
    if (method_exists('App\Helpers\ImageUploadHelper', 'uploadImageToPublic')) {
        echo "   ✓ uploadImageToPublic method exists\n";
    } else {
        echo "   ✗ uploadImageToPublic method NOT found\n";
    }
} else {
    echo "   ✗ ImageUploadHelper class NOT found\n";
}

// Check the PartnerSchoolController store method
echo "\n2. Checking PartnerSchoolController store method...\n";
$controllerPath = app_path('Http/Controllers/PartnerSchoolController.php');
if (file_exists($controllerPath)) {
    echo "   ✓ PartnerSchoolController file exists\n";
    
    $controllerContent = file_get_contents($controllerPath);
    
    // Check for ImageUploadHelper usage
    if (strpos($controllerContent, 'ImageUploadHelper::uploadImageToPublic') !== false) {
        echo "   ✓ Controller uses ImageUploadHelper::uploadImageToPublic\n";
    } else {
        echo "   ✗ Controller does NOT use ImageUploadHelper::uploadImageToPublic\n";
        
        // Check what it's using instead
        if (strpos($controllerContent, '$request->file(\'image\')->store(') !== false) {
            echo "   ! Controller is using old Laravel storage method\n";
        }
    }
    
    // Check for use statement
    if (strpos($controllerContent, 'use App\Helpers\ImageUploadHelper;') !== false) {
        echo "   ✓ Controller imports ImageUploadHelper\n";
    } else {
        echo "   ✗ Controller does NOT import ImageUploadHelper\n";
    }
} else {
    echo "   ✗ PartnerSchoolController file NOT found\n";
}

// Check git status
echo "\n3. Checking git status...\n";
$gitStatus = shell_exec('git status --porcelain 2>&1');
if (empty(trim($gitStatus))) {
    echo "   ✓ Working directory is clean\n";
} else {
    echo "   ! Working directory has changes:\n";
    echo "   " . $gitStatus . "\n";
}

// Check last commit
echo "\n4. Checking last commit...\n";
$lastCommit = shell_exec('git log --oneline -1 2>&1');
echo "   Last commit: " . trim($lastCommit) . "\n";

// Check if we're on the right branch
$currentBranch = shell_exec('git branch --show-current 2>&1');
echo "   Current branch: " . trim($currentBranch) . "\n";

echo "\n======================================================\n";
echo "DEBUG COMPLETE\n";
echo "======================================================\n";
echo "If the controller is not using ImageUploadHelper, run:\n";
echo "git pull origin main\n";
echo "php artisan cache:clear\n";
echo "======================================================\n";
