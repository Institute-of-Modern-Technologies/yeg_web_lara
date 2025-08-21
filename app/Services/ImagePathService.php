<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ImagePathService
{
    /**
     * Resolve the image path based on environment and image location.
     * NO NEED FOR STORAGE:LINK - Direct file access approach
     *
     * @param string $path The image path to resolve
     * @return string The resolved path
     */
    public function resolveImagePath($path)
    {
        // Keep track of the original path for debugging
        $originalPath = $path;
        
        // Skip processing for external URLs
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        
        // Handle empty paths
        if (empty($path)) {
            return asset('images/placeholder-school.svg');
        }
        
        // Remove leading slashes if present
        $path = ltrim($path, '/');
        
        // Check if the path starts with images/ and the file exists
        if (str_starts_with($path, 'images/')) {
            $fullPath = public_path($path);
            if (file_exists($fullPath)) {
                $resolvedPath = asset($path);
                Log::debug("Image resolved (direct public path): {$originalPath} → {$resolvedPath}");
                return $resolvedPath;
            }
        }
        
        // If path starts with storage/, check both locations
        if (str_starts_with($path, 'storage/')) {
            // First check if it exists directly in public/storage (symlinked)
            if (file_exists(public_path($path))) {
                $resolvedPath = asset($path);
                Log::debug("Image resolved (public storage symlink): {$originalPath} → {$resolvedPath}");
                return $resolvedPath;
            }
            
            // Then check if it exists in storage/app/public
            $storagePath = str_replace('storage/', '', $path);
            if (Storage::disk('public')->exists($storagePath)) {
                $resolvedPath = url($path); // Direct URL without asset() function
                Log::debug("Image resolved (storage disk): {$originalPath} → {$resolvedPath}");
                return $resolvedPath;
            }
        }
        
        // If the path doesn't have images/ or storage/ prefix, check various locations
        if (!str_starts_with($path, 'images/') && !str_starts_with($path, 'storage/')) {
            // 1. Check if file exists directly in public folder
            if (file_exists(public_path($path))) {
                $resolvedPath = asset($path);
                Log::debug("Image resolved (direct public file): {$originalPath} → {$resolvedPath}");
                return $resolvedPath;
            }
            
            // 2. Try with images/ prefix
            $imagesPath = 'images/' . $path;
            if (file_exists(public_path($imagesPath))) {
                $resolvedPath = asset($imagesPath);
                Log::debug("Image resolved (public/images): {$originalPath} → {$resolvedPath}");
                return $resolvedPath;
            }
            
            // 3. Try with storage/ prefix (for older paths)
            $storagePath = 'storage/' . $path;
            if (file_exists(public_path($storagePath))) {
                $resolvedPath = asset($storagePath);
                Log::debug("Image resolved (public/storage): {$originalPath} → {$resolvedPath}");
                return $resolvedPath;
            }
            
            // 4. Check in storage disk directly
            if (Storage::disk('public')->exists($path)) {
                $resolvedPath = asset('storage/' . $path);
                Log::debug("Image resolved (storage disk without prefix): {$originalPath} → {$resolvedPath}");
                return $resolvedPath;
            }
        }
        
        // Handle profile photos (direct public path) - special case
        if (strpos($path, 'profile-photos/') !== false || strpos($path, 'profile_photos/') !== false) {
            // Check in uploads directory
            if (File::exists(public_path('uploads/' . $path))) {
                $resolvedPath = asset('uploads/' . $path);
                Log::debug("Profile photo resolved (uploads): {$originalPath} → {$resolvedPath}");
                return $resolvedPath;
            } 
            
            // Check in public directly
            if (File::exists(public_path($path))) {
                $resolvedPath = asset($path);
                Log::debug("Profile photo resolved (public): {$originalPath} → {$resolvedPath}");
                return $resolvedPath;
            } 
            
            // Check in storage
            if (Storage::disk('public')->exists($path)) {
                $resolvedPath = asset('storage/' . $path);
                Log::debug("Profile photo resolved (storage): {$originalPath} → {$resolvedPath}");
                return $resolvedPath;
            }
        }
        
        // Last resort: Just return the asset path and log the failure
        $resolvedPath = asset($path);
        Log::warning("Image path resolution failed, returning as-is: {$originalPath} → {$resolvedPath}");
        return $resolvedPath;
    }
}
