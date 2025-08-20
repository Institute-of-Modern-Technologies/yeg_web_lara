<?php

namespace App\Helpers;

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
        // Generate unique filename
        $fileName = time() . '_' . uniqid() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        
        // Create full directory path
        $directoryPath = 'images/' . $subDirectory;
        $fullPath = public_path($directoryPath);
        
        // Create directory if it doesn't exist
        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0755, true);
        }
        
        // Move the file
        $file->move($fullPath, $fileName);
        
        // Return the relative path
        return $directoryPath . '/' . $fileName;
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
}
