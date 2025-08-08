<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentChallengeStat extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'xp_points',
        'challenges_won',
        'challenges_lost',
        'challenges_drawn',
        'challenges_initiated',
        'challenges_received',
        'questions_answered',
        'correct_answers',
        'total_time_spent',
    ];
    
    /**
     * Get the student these stats belong to.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    /**
     * Get the student's rank based on XP points.
     * 
     * @return int
     */
    public function getRankAttribute()
    {
        return static::where('xp_points', '>', $this->xp_points)->count() + 1;
    }
}
