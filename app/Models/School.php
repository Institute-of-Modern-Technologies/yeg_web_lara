<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class School extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'location',
        'gps_coordinates',
        'owner_name',
        'avg_students',
        'logo',
        'status', // pending, approved, rejected
    ];
    
    /**
     * Get the students associated with the school.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }
    
    /**
     * Get the status label with appropriate color
     * 
     * @return array
     */
    public function getStatusAttributes()
    {
        $statusMap = [
            'pending' => [
                'label' => 'Pending Approval',
                'color' => 'bg-yellow-100 text-yellow-800',
                'icon' => 'fa-clock'
            ],
            'approved' => [
                'label' => 'Approved',
                'color' => 'bg-green-100 text-green-800',
                'icon' => 'fa-check-circle'
            ],
            'rejected' => [
                'label' => 'Rejected',
                'color' => 'bg-red-100 text-red-800',
                'icon' => 'fa-times-circle'
            ],
        ];
        
        return $statusMap[$this->status] ?? $statusMap['pending'];
    }
}
