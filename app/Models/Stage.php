<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stage extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'status',
        'description',
        'order'
    ];
    
    /**
     * The activities that belong to this stage.
     */
    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'activity_stage')
                    ->withTimestamps();
    }
    
    /**
     * The levels that belong to this stage.
     */
    public function levels(): HasMany
    {
        return $this->hasMany(Level::class)->orderBy('level_number');
    }
    
    /**
     * The students at this stage.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
