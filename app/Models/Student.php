<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use App\Models\ProgramType;
use App\Models\School;
use App\Models\Payment;
use App\Models\Stage;

class Student extends Model
{
    use HasFactory, Notifiable;
    
    protected $fillable = [
        'full_name',
        'first_name',
        'last_name',
        'age',
        'phone',
        'email',
        'parent_contact',
        'school_id',
        'school_name',
        'city',
        'program_type_id',
        'registration_number',
        'payment_reference',
        'payment_status',
        'status',
        'payer_type',
        'class',
        'gender',
        'date_of_birth',
        'address',
        'region',
        'state'
    ];
    
    /**
     * Get the first name from the full_name
     */
    public function getFirstNameAttribute()
    {
        // Split the full name and return the first part
        $nameParts = explode(' ', $this->full_name);
        return $nameParts[0] ?? '';
    }
    
    /**
     * Get the last name from the full_name
     */
    public function getLastNameAttribute()
    {
        // Split the full name and return everything after the first name
        $nameParts = explode(' ', $this->full_name);
        array_shift($nameParts); // Remove the first element (first name)
        return implode(' ', $nameParts); // Return the rest as the last name
    }
    
    /**
     * Get the program type associated with the student.
     */
    public function programType()
    {
        return $this->belongsTo(ProgramType::class);
    }
    
    /**
     * Get the school associated with the student.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }
    
    /**
     * Get the display school name (either from relationship or manually entered)
     */
    public function getDisplaySchoolNameAttribute()
    {
        if (!empty($this->school_name)) {
            return $this->school_name; // Return manually entered name
        } elseif ($this->school) {
            return $this->school->name; // Return related school name
        }
        return 'Not specified'; // Fallback
    }
    
    /**
     * Get the payments for the student.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    
    /**
     * Get the stage associated with the student.
     */
    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }
    
    /**
     * Get the challenges initiated by this student.
     */
    public function challengesInitiated()
    {
        return $this->hasMany(Challenge::class, 'challenger_id');
    }
    
    /**
     * Get the challenges received by this student.
     */
    public function challengesReceived()
    {
        return $this->hasMany(Challenge::class, 'opponent_id');
    }
    
    /**
     * Get all challenge responses by this student.
     */
    public function challengeResponses()
    {
        return $this->hasMany(ChallengeResponse::class);
    }
    
    /**
     * Get the challenge stats for this student.
     */
    public function challengeStats()
    {
        return $this->hasOne(StudentChallengeStat::class);
    }
}
