<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ImagePathService
{
    /**
     * Resolve image path to handle both storage and public paths consistently
     *
     * @param string $path
     * @return string
     */
    public function resolveImagePath($path)
    {
        // Strip quotes if they exist in the path
        $path = trim($path, "'\"");
        
        // Check if path is a storage path
        if (strpos($path, 'storage/') === 0) {
            // This is a storage path without the public/ prefix
            $path = substr($path, 8); // Remove 'storage/'
            
            // Check if the file exists in public storage
            if (File::exists(public_path('storage/' . $path))) {
                return asset('storage/' . $path);
            }
            
            // If not in public storage, check if it's in the direct public directory
            if (File::exists(public_path($path))) {
                return asset($path);
            }
            
            // Final fallback - return storage path and hope it exists
            return asset('storage/' . $path);
        }
        
        // Check if this is a direct public path
        if (strpos($path, 'images/') === 0 || strpos($path, 'uploads/') === 0) {
            if (File::exists(public_path($path))) {
                return asset($path);
            }
            
            // Check if the file exists in storage/app/public
            $storagePath = str_replace('images/', '', $path);
            $storagePath = str_replace('uploads/', '', $storagePath);
            if (Storage::disk('public')->exists($storagePath)) {
                return asset('storage/' . $storagePath);
            }
            
            // Return original path as fallback
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
