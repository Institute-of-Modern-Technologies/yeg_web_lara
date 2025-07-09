<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use App\Models\ProgramType;
use App\Models\School;
use App\Models\Payment;

class Student extends Model
{
    use HasFactory, Notifiable;
    
    protected $fillable = [
        'full_name',
        'age',
        'phone',
        'email',
        'parent_contact',
        'school_id',
        'city',
        'program_type_id',
        'registration_number',
        'payment_reference',
        'payment_status',
        'status',
        'payer_type',
        'class'
    ];
    
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
     * Get the payments for the student.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
