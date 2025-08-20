<?php

/**
 * Image Path Test Script
 * This script tests whether images can be correctly resolved using our new ImagePathService
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Get the ImagePathService from the container
$imagePathService = $app->make(\App\Services\ImagePathService::class);

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Image Path Test</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 20px; }
        h1, h2 { color: #950713; }
        .test-section { margin-bottom: 30px; border: 1px solid #ddd; padding: 15px; border-radius: 8px; }
        .test-images { display: flex; flex-wrap: wrap; gap: 20px; margin-top: 20px; }
        .image-test { display: flex; flex-direction: column; align-items: center; width: 220px; }
        .image-container { width: 200px; height: 200px; display: flex; align-items: center; justify-content: center; border: 1px solid #eee; margin-bottom: 10px; }
        .image-container img { max-width: 100%; max-height: 100%; object-fit: contain; }
        .success { color: green; font-weight: bold; }
        .fail { color: red; font-weight: bold; }
        .path-info { font-size: 12px; word-break: break-all; text-align: center; height: 40px; overflow: auto; }
    </style>
</head>
<body>
    <h1>Image Path Resolution Test</h1>
    <p>This page tests the new image path resolution system to ensure images are displayed correctly.</p>";

echo "<div class='test-section'>
        <h2>Test 1: Public Images</h2>
        <p>Testing images stored in the public/images directory:</p>
        <div class='test-images'>";

// Test several public images
$publicImages = [
    'images/Hero picture 3.png',
    'images/coding.png',
    'images/digital-marketing.png',
    'images/graphic-designing.png',
    'images/Enterpreneurship.png',
    'images/Artificial Intelligence.png'
];

foreach ($publicImages as $image) {
    $resolvedPath = $imagePathService->resolveImagePath($image);
    $fileExists = file_exists(public_path(str_replace(url('/'), '', $resolvedPath)));
    
    echo "<div class='image-test'>
            <div class='image-container'>
                <img src='{$resolvedPath}' alt='Test image' onerror=\"this.onerror=null;this.src='images/placeholder-school.svg';this.nextElementSibling.innerHTML='Failed to load';this.nextElementSibling.className='fail';\">
            </div>
            <div class='" . ($fileExists ? 'success' : 'fail') . "'>" . ($fileExists ? 'File exists' : 'File not found') . "</div>
            <div class='path-info'>Original: {$image}<br>Resolved: {$resolvedPath}</div>
          </div>";
}

echo "</div></div>";

echo "<div class='test-section'>
        <h2>Test 2: Storage Images</h2>
        <p>Testing images stored in storage/app/public directory:</p>
        <div class='test-images'>";

// Test several storage images (this is just a guess at some paths, you'll need to adjust)
$storageImages = [
    'storage/schools/logo.png',
    'storage/events/featured.jpg',
    'storage/hero-sections/hero.png',
    'storage/schools/default.png'
];

foreach ($storageImages as $image) {
    $resolvedPath = $imagePathService->resolveImagePath($image);
    $storageExists = \Illuminate\Support\Facades\Storage::exists(str_replace('storage/', '', $image));
    
    echo "<div class='image-test'>
            <div class='image-container'>
                <img src='{$resolvedPath}' alt='Test image' onerror=\"this.onerror=null;this.src='images/placeholder-school.svg';this.nextElementSibling.innerHTML='Failed to load';this.nextElementSibling.className='fail';\">
            </div>
            <div class='" . ($storageExists ? 'success' : 'fail') . "'>" . ($storageExists ? 'File exists' : 'File not found') . "</div>
            <div class='path-info'>Original: {$image}<br>Resolved: {$resolvedPath}</div>
          </div>";
}

echo "</div></div>";

echo "<div class='test-section'>
        <h2>Test 3: Profile Photos</h2>
        <p>Testing profile photo images:</p>
        <div class='test-images'>";

// Test profile photos
$profileImages = [
    'uploads/profile-photos/default.jpg'
];

foreach ($profileImages as $image) {
    $resolvedPath = $imagePathService->resolveImagePath($image);
    $fileExists = file_exists(public_path(str_replace(url('/'), '', $resolvedPath)));
    
    echo "<div class='image-test'>
            <div class='image-container'>
                <img src='{$resolvedPath}' alt='Test image' onerror=\"this.onerror=null;this.src='images/placeholder-school.svg';this.nextElementSibling.innerHTML='Failed to load';this.nextElementSibling.className='fail';\">
            </div>
            <div class='" . ($fileExists ? 'success' : 'fail') . "'>" . ($fileExists ? 'File exists' : 'File not found') . "</div>
            <div class='path-info'>Original: {$image}<br>Resolved: {$resolvedPath}</div>
          </div>";
}

echo "</div></div>";

echo "<div class='test-section'>
        <h2>Summary</h2>
        <p>The ImagePathService is designed to resolve image paths in the following order:</p>
        <ol>
            <li>Check if the image exists in the public directory</li>
            <li>Check if the image exists in the storage/app/public directory</li>
            <li>Return the best available path or a fallback placeholder</li>
        </ol>
        <p>If images are not displaying correctly, make sure:</p>
        <ol>
            <li>The images actually exist in one of these locations</li>
            <li>The paths used in your code match the actual file locations</li>
            <li>The &lt;x-image&gt; component is being used with proper src attributes</li>
        </ol>
        <p><strong>Note:</strong> With this solution, the storage:link command should no longer be required as the ImagePathService will correctly resolve paths regardless of symlink presence.</p>
      </div>";

echo "</body></html>";
