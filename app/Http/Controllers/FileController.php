<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class FileController extends Controller
{
    /**
     * Serve a file directly from storage
     *
     * @param string $type The file type (image, video, book)
     * @param string $filename The filename
     * @return Response
     */
    public function serveFile($type, $filename)
    {
        // Add 's' to type to match directory structure (image -> images)
        $typeDir = $type . 's';
        
        // Full path to the file in storage
        $storagePath = "student-works/{$typeDir}/{$filename}";
        
        // Check if file exists
        if (!Storage::disk('public')->exists($storagePath)) {
            // Try direct file path as a fallback
            $publicPath = public_path("storage/{$storagePath}");
            if (file_exists($publicPath)) {
                // Return file directly from public path
                return response()->file($publicPath);
            }
            
            abort(404, 'File not found');
        }
        
        // Get file content
        $fileContent = Storage::disk('public')->get($storagePath);
        
        // Get file MIME type
        $mimeType = Storage::disk('public')->mimeType($storagePath);
        
        // Return file as response
        return response($fileContent)
            ->header('Content-Type', $mimeType)
            ->header('Cache-Control', 'public, max-age=86400');
    }
}
