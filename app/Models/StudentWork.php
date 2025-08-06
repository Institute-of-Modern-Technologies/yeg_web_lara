<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class StudentWork extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'title',
        'description',
        'type',
        'file_path',
        'website_url',
        'thumbnail',
        'approved'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'approved' => 'boolean',
    ];
    
    /**
     * Get the student that owns the work.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    /**
     * Get the URL for the work item based on its type
     * 
     * @return string
     */
    public function getViewUrl()
    {
        switch($this->type) {
            case 'website':
                return $this->website_url;
            case 'image':
            case 'video':
            case 'book':
                // Use our custom file serving route to bypass 403 errors
                if ($this->file_path) {
                    // Extract the type and filename from file_path
                    // Expects format like 'student-works/images/filename.jpg'
                    $parts = explode('/', $this->file_path);
                    if (count($parts) >= 3) {
                        $type = basename($parts[1]); // 'images', 'videos', etc.
                        $filename = $parts[2]; // The actual filename
                        // Remove 's' from the end of type (images -> image, etc.)
                        $type = rtrim($type, 's');
                        return route('serve.file', ['type' => $type, 'filename' => $filename]);
                    }
                    
                    // Fallback to standard asset URL if format is unexpected
                    return asset('storage/' . $this->file_path);
                }
                // Fallback to default
                return '#';
            default:
                return '#';
        }
    }
    
    /**
     * Get the thumbnail URL for the work item
     * 
     * @return string
     */
    public function getThumbnailUrl()
    {
        // If we have a specific thumbnail, use our custom route
        if ($this->thumbnail) {
            // Extract the type and filename from thumbnail path
            $parts = explode('/', $this->thumbnail);
            if (count($parts) >= 3) {
                $type = basename($parts[1]); // 'images', 'videos', etc.
                $filename = $parts[2]; // The actual filename
                // Remove 's' from the end of type (images -> image, etc.)
                $type = rtrim($type, 's');
                return route('serve.file', ['type' => $type, 'filename' => $filename]);
            }
            
            // Fallback to standard asset URL if format is unexpected
            return asset('storage/' . $this->thumbnail);
        }
        
        // For image type, try to use the image itself as thumbnail
        if ($this->type === 'image' && $this->file_path) {
            return $this->getViewUrl();
        }
        
        // Default thumbnails based on type
        switch($this->type) {
            case 'website':
                // Use data URI for guaranteed display
                return 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiB2aWV3Qm94PSIwIDAgMTAwIDEwMCI+PHJlY3Qgd2lkdGg9IjEwMCIgaGVpZ2h0PSIxMDAiIGZpbGw9IiNlMmUyZTIiLz48dGV4dCB4PSI1MCIgeT0iNTAiIGZvbnQtc2l6ZT0iMTQiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGFsaWdubWVudC1iYXNlbGluZT0ibWlkZGxlIiBmb250LWZhbWlseT0ic2Fucy1zZXJpZiIgZmlsbD0iIzk5OTk5OSI+V2Vic2l0ZTwvdGV4dD48L3N2Zz4=';
                
            case 'video':
                return 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiB2aWV3Qm94PSIwIDAgMTAwIDEwMCI+PHJlY3Qgd2lkdGg9IjEwMCIgaGVpZ2h0PSIxMDAiIGZpbGw9IiNlMmUyZTIiLz48dGV4dCB4PSI1MCIgeT0iNTAiIGZvbnQtc2l6ZT0iMTQiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGFsaWdubWVudC1iYXNlbGluZT0ibWlkZGxlIiBmb250LWZhbWlseT0ic2Fucy1zZXJpZiIgZmlsbD0iIzk5OTk5OSI+VmlkZW88L3RleHQ+PC9zdmc+';
                
            case 'book':
                return 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiB2aWV3Qm94PSIwIDAgMTAwIDEwMCI+PHJlY3Qgd2lkdGg9IjEwMCIgaGVpZ2h0PSIxMDAiIGZpbGw9IiNlMmUyZTIiLz48dGV4dCB4PSI1MCIgeT0iNTAiIGZvbnQtc2l6ZT0iMTQiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGFsaWdubWVudC1iYXNlbGluZT0ibWlkZGxlIiBmb250LWZhbWlseT0ic2Fucy1zZXJpZiIgZmlsbD0iIzk5OTk5OSI+Qm9vazwvdGV4dD48L3N2Zz4=';
                
            default:
                return 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiB2aWV3Qm94PSIwIDAgMTAwIDEwMCI+PHJlY3Qgd2lkdGg9IjEwMCIgaGVpZ2h0PSIxMDAiIGZpbGw9IiNlMmUyZTIiLz48dGV4dCB4PSI1MCIgeT0iNTAiIGZvbnQtc2l6ZT0iMTQiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGFsaWdubWVudC1iYXNlbGluZT0ibWlkZGxlIiBmb250LWZhbWlseT0ic2Fucy1zZXJpZiIgZmlsbD0iIzk5OTk5OSI+SW1hZ2U8L3RleHQ+PC9zdmc+';
        }
    }
}
