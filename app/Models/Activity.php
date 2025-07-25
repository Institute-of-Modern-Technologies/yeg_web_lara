<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Activity extends Model
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
    ];
    
    /**
     * The levels that this activity belongs to.
     */
    public function levels(): BelongsToMany
    {
        return $this->belongsToMany(Level::class, 'activity_level')
                    ->withTimestamps();
    }
    
    /**
     * The stages that this activity belongs to.
     */
    public function stages(): BelongsToMany
    {
        return $this->belongsToMany(Stage::class, 'activity_stage')
                    ->withTimestamps();
    }
}
