<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\ProgramType;
use App\Models\School;

class Fee extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'program_type_id',
        'school_id',
        'amount',
        'partner_discount',
        'school_commission',
        'imt_commission',
        'is_active'
    ];
    
    /**
     * Get the program type that owns the fee.
     */
    public function programType(): BelongsTo
    {
        return $this->belongsTo(ProgramType::class);
    }
    
    /**
     * Get the school that owns the fee.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
