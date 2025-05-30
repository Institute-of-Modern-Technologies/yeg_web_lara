<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Student;

class Payment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'student_id',
        'amount',
        'discount',
        'final_amount',
        'reference_number',
        'status',
        'payment_method',
        'notes'
    ];
    
    /**
     * Get the student that owns the payment.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
