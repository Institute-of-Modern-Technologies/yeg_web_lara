<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallengeQuestion extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'question_text',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
        'explanation',
        'difficulty_level',
        'is_active',
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
     * Get the category that owns the question.
     */
    public function category()
    {
        return $this->belongsTo(ChallengeCategory::class, 'category_id');
    }
    
    /**
     * Get the challenges that include this question.
     */
    public function challenges()
    {
        return $this->belongsToMany(Challenge::class, 'challenge_question')
                    ->withPivot('question_order')
                    ->withTimestamps();
    }
    
    /**
     * Get the responses for this question.
     */
    public function responses()
    {
        return $this->hasMany(ChallengeResponse::class, 'question_id');
    }
}
