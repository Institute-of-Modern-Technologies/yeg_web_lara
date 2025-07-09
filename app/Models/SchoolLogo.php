<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SchoolLogo extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'logo_path',
        'display_order',
        'is_active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    /**
     * Get the logo URL attribute
     * 
     * @return string
     */
    public function getLogoUrlAttribute()
    {
        if (!$this->logo_path) {
            return asset('images/placeholder-school.svg');
        }
        
        // Extract just the filename from the path
        $pathParts = explode('/', $this->logo_path);
        $filename = end($pathParts);
        
        // Check if the file exists in public/images
        if (file_exists(public_path('images/' . $filename))) {
            return asset('images/' . $filename);
        }
        
        // Fallback to storage (original path) or placeholder if not found
        if (Storage::disk('public')->exists($this->logo_path)) {
            return url('storage/' . $this->logo_path);
        }
        
        return asset('images/placeholder-school.svg');
    }
}
