<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'phone',
        'email',
        'location',
        'surrounding_areas',
        'educational_background',
        'relevant_experience',
        'expertise_areas',
        'other_expertise',
        'program_applied',
        'preferred_locations',
        'other_location',
        'experience_teaching_kids',
        'cv_status',
        'why_instructor',
        'video_introduction',
        'confirmation_agreement',
        'status'
    ];
    
    protected $casts = [
        'expertise_areas' => 'array',
        'preferred_locations' => 'array',
        'experience_teaching_kids' => 'boolean',
        'confirmation_agreement' => 'boolean',
    ];
}
