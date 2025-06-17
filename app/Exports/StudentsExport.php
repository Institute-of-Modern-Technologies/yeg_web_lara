<?php

namespace App\Exports;

use App\Models\Student;
use League\Csv\Writer;
use SplTempFileObject;

class StudentsExport
{
    /**
     * Export students to a CSV file.
     *
     * @param array $filters
     * @return string
     */
    public function export($filters = [])
    {
        // Create a CSV writer
        $csv = Writer::createFromFileObject(new SplTempFileObject());
        
        // Set the CSV header
        $csv->insertOne([
            'Registration Number',
            'Full Name',
            'Age',
            'Phone',
            'Email',
            'Parent Contact',
            'City',
            'School',
            'Program Type',
            'Payment Status',
            'Status',
            'Registration Date'
        ]);
        
        // Query students based on filters
        $query = Student::query()->with(['school', 'programType']);
        
        // Apply filters if provided
        if (isset($filters['school_id']) && $filters['school_id']) {
            $query->where('school_id', $filters['school_id']);
        }
        
        if (isset($filters['program_type_id']) && $filters['program_type_id']) {
            $query->where('program_type_id', $filters['program_type_id']);
        }
        
        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }
        
        // Get all students (no pagination)
        $students = $query->get();
        
        // Add students to the CSV
        foreach ($students as $student) {
            $csv->insertOne([
                $student->registration_number,
                $student->full_name,
                $student->age,
                $student->phone,
                $student->email,
                $student->parent_contact,
                $student->city,
                $student->school ? $student->school->name : 'N/A',
                $student->programType ? $student->programType->name : 'N/A',
                $student->payment_status,
                $student->status,
                $student->created_at->format('Y-m-d')
            ]);
        }
        
        return $csv->toString();
    }
}
