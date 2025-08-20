<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImageUploadHelper
{
    /**
     * Upload an image directly to the public/images directory
     * No storage:link needed
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $subDirectory
     * @return string Path relative to public
     */
    public static function uploadImageToPublic($file, $subDirectory = '')
    {
        try {
            // Generate unique filename
            $fileName = time() . '_' . uniqid() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            
            // Create full directory path
            $directoryPath = 'images/' . $subDirectory;
            $fullPath = public_path($directoryPath);
            
            // Create directory if it doesn't exist
            if (!file_exists($fullPath)) {
                if (!mkdir($fullPath, 0755, true)) {
                    // Log error and fall back to storage if directory creation fails
                    \Illuminate\Support\Facades\Log::error("Failed to create directory: {$fullPath}");
                    return self::fallbackToStorage($file, $subDirectory);
                }
                chmod($fullPath, 0755); // Ensure proper permissions
            }
            
            // Move the file
            if (!$file->move($fullPath, $fileName)) {
                // Log error and fall back if move fails
                \Illuminate\Support\Facades\Log::error("Failed to move uploaded file to {$fullPath}/{$fileName}");
                return self::fallbackToStorage($file, $subDirectory);
            }
            
            // Return the relative path
            return $directoryPath . '/' . $fileName;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Image upload error: " . $e->getMessage());
            return self::fallbackToStorage($file, $subDirectory);
        }
    }
    
    /**
     * Delete an image from the public directory
     *
     * @param string $path Path relative to public
     * @return bool True if deleted or not found
     */
    public static function deleteImageFromPublic($path)
    {
        // Handle null or empty paths
        if (empty($path)) {
            return true;
        }
        
        $fullPath = public_path($path);
        
        if (file_exists($fullPath)) {
            unlink($fullPath);
            return true;
        }
        
        // Also check the old storage path (for backward compatibility)
        $oldStoragePath = public_path('storage/' . str_replace('images/', '', $path));
        if (file_exists($oldStoragePath)) {
            unlink($oldStoragePath);
            return true;
        }
        
        return true; // File not found is not an error
    }
    
    /**
     * Fallback to storing in storage/app/public if direct upload to public directory fails
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $subDirectory
     * @return string Path relative to storage
     */
    private static function fallbackToStorage($file, $subDirectory = '')
    {
        try {
            // Store via Laravel's storage facade
            $path = $file->store('public/' . $subDirectory);
            
            // Convert storage path to relative path
            if (strpos($path, 'public/') === 0) {
                $path = str_replace('public/', '', $path);
            }
            
            // Log that we're falling back
            \Illuminate\Support\Facades\Log::info("Using storage fallback for image upload: {$path}");
            
            return $path;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Storage fallback failed: " . $e->getMessage());
            return null;
        }
    }
}
