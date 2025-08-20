<?php
// Test script for direct image access without storage:link

// Include Laravel bootstrap
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Import our service
use App\Services\ImagePathService;

// Create instance of our service
$imageService = new ImagePathService();

// Sample paths to test
$testPaths = [
    'storage/hero-sections/1755682681_68a59779523d6_yeg_boyy.jpg',
    'images/placeholder-school.svg',
    'img/default-thumbnail.jpg'
];

// HTML output
echo '<!DOCTYPE html>
<html>
<head>
    <title>Direct Image Access Test</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 20px; }
        .image-test { border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        img { max-width: 300px; height: auto; display: block; margin-top: 10px; }
        pre { background: #f4f4f4; padding: 10px; overflow: auto; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Direct Image Access Test</h1>
    <p>This test verifies that images can be accessed directly without storage:link</p>
    
    <h2>Test Results:</h2>';

foreach ($testPaths as $path) {
    $resolvedPath = $imageService->resolveImagePath($path);
    
    echo '<div class="image-test">';
    echo '<h3>Testing Path: <code>' . htmlspecialchars($path) . '</code></h3>';
    echo '<p><strong>Resolved to:</strong> <code>' . htmlspecialchars($resolvedPath) . '</code></p>';
    
    echo '<p><strong>Image Preview:</strong></p>';
    echo '<img src="' . $resolvedPath . '" onerror="this.onerror=null; this.src=\'\'; this.alt=\'Image failed to load\'; this.classList.add(\'error\'); document.getElementById(\'status-' . md5($path) . '\').innerHTML=\'❌ Failed\';" onload="document.getElementById(\'status-' . md5($path) . '\').innerHTML=\'✅ Success\';" />';
    
    echo '<p><strong>Status:</strong> <span id="status-' . md5($path) . '">Checking...</span></p>';
    echo '</div>';
}

echo '</body></html>';
