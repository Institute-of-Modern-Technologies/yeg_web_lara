<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

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
        
        // DIRECT STORAGE ACCESS APPROACH:
        // If path starts with storage/, we'll serve it directly from the storage directory
        if (str_starts_with($path, 'storage/')) {
            $storagePath = str_replace('storage/', '', $path);
            
            // Check if file exists in storage directory
            if (Storage::disk('public')->exists($storagePath)) {
                // On production, serve directly from /storage URL
                return url($path);
            }
        }
        
        // Check if the file exists in the public folder
        if (file_exists(public_path($path))) {
            return asset($path);
        }
        
        // Handle profile photos (direct public path)
        if (strpos($path, 'profile-photos/') !== false || strpos($path, 'profile_photos/') !== false) {
            if (File::exists(public_path('uploads/' . $path))) {
                return asset('uploads/' . $path);
            } else if (File::exists(public_path($path))) {
                return asset($path);
            } else {
                // Check storage as fallback
                if (Storage::disk('public')->exists($path)) {
                    return asset('storage/' . $path);
                }
                return asset($path); // Return as is as last resort
            }
        }
        
        // Handle relative paths that don't specify images/ or uploads/
        if (!strpos($path, '/')) {
            // Check in public/images
            if (File::exists(public_path('images/' . $path))) {
                return asset('images/' . $path);
            }
            
            // Check in storage/app/public
            if (Storage::disk('public')->exists($path)) {
                return asset('storage/' . $path);
            }
        }
        
        // For any other path format, just return as asset
        return asset($path);
    }
}
