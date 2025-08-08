<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallengeCategory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'is_active',
        'display_order',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    /**
     * Get the questions that belong to this category.
     */
    public function questions()
    {
        return $this->hasMany(ChallengeQuestion::class, 'category_id');
    }
    
    /**
     * Get the challenges that belong to this category.
     */
    public function challenges()
    {
        return $this->hasMany(Challenge::class, 'category_id');
    }
}
