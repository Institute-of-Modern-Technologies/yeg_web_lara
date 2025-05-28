<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Happening extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'content',
        'author_name',
        'published_date',
        'category',
        'media_type',
        'media_path',
        'is_active',
        'display_order'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'published_date' => 'date',
        'is_active' => 'boolean'
    ];
    
    /**
     * Scope a query to only include active happenings.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * Scope a query to order happenings by display_order.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc');
    }
    
    /**
     * Scope a query to order happenings by published_date (most recent first).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('published_date', 'desc');
    }
    
    /**
     * Check if the happening media is a video.
     *
     * @return bool
     */
    public function isVideo()
    {
        return $this->media_type === 'video';
    }
    
    /**
     * Get truncated content.
     *
     * @param int $length
     * @return string
     */
    public function getShortContent($length = 100)
    {
        return strlen($this->content) > $length
            ? substr($this->content, 0, $length) . '...'
            : $this->content;
    }
    
    /**
     * Get formatted published date.
     *
     * @param string $format
     * @return string
     */
    public function getFormattedDate($format = 'F d, Y')
    {
        return $this->published_date->format($format);
    }
}
