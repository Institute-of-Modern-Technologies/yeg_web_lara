<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallengeResponse extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'challenge_id',
        'student_id',
        'question_id',
        'selected_answer',
        'is_correct',
        'time_taken',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_correct' => 'boolean',
    ];
    
    /**
     * Get the challenge this response belongs to.
     */
    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }
    
    /**
     * Get the student who provided this response.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    /**
     * Get the question this response is for.
     */
    public function question()
    {
        return $this->belongsTo(ChallengeQuestion::class, 'question_id');
    }
}
