<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'challenger_id',
        'opponent_id',
        'category_id',
        'status',
        'is_random_opponent',
        'expires_at',
        'completed_at',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_random_opponent' => 'boolean',
        'expires_at' => 'datetime',
        'completed_at' => 'datetime',
    ];
    
    /**
     * Get the challenger student.
     */
    public function challenger()
    {
        return $this->belongsTo(Student::class, 'challenger_id');
    }
    
    /**
     * Get the opponent student.
     */
    public function opponent()
    {
        return $this->belongsTo(Student::class, 'opponent_id');
    }
    
    /**
     * Get the category of this challenge.
     */
    public function category()
    {
        return $this->belongsTo(ChallengeCategory::class, 'category_id');
    }
    
    /**
     * Get the questions for this challenge.
     */
    public function questions()
    {
        return $this->belongsToMany(ChallengeQuestion::class, 'challenge_question')
                    ->withPivot('question_order')
                    ->withTimestamps();
    }
    
    /**
     * Get the responses for this challenge.
     */
    public function responses()
    {
        return $this->hasMany(ChallengeResponse::class);
    }
    
    /**
     * Get the result of this challenge.
     */
    public function result()
    {
        return $this->hasOne(ChallengeResult::class);
    }
    
    /**
     * Scope a query to only include active challenges.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
    /**
     * Scope a query to only include pending challenges.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    /**
     * Scope a query to only include completed challenges.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
    
    /**
     * Scope a query to only include expired challenges.
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }
}
