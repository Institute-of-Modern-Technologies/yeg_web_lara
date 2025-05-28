<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'media_type',
        'media_path',
        'duration',
        'level',
        'level_color',
        'is_active',
        'display_order'
    ];
    
    /**
     * Scope a query to only include active events.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * Scope a query to order events by display_order.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc');
    }
    
    /**
     * Check if the event media is a video.
     *
     * @return bool
     */
    public function isVideo()
    {
        return $this->media_type === 'video';
    }
    
    /**
     * Get truncated description.
     *
     * @param int $length
     * @return string
     */
    public function getShortDescription($length = 100)
    {
        return strlen($this->description) > $length
            ? substr($this->description, 0, $length) . '...'
            : $this->description;
    }
}
