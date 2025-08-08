<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallengeResult extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'challenge_id',
        'winner_id',
        'challenger_score',
        'opponent_score',
        'challenger_time',
        'opponent_time',
        'result',
    ];
    
    /**
     * Get the challenge this result belongs to.
     */
    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }
    
    /**
     * Get the student who won this challenge.
     */
    public function winner()
    {
        return $this->belongsTo(Student::class, 'winner_id');
    }
}
