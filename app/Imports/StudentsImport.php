<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\School;
use App\Models\ProgramType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;
use Illuminate\Support\Facades\Log;

class StudentsImport
{
    protected $headerRow;
    protected $columnMap = [];
    // All fields are now optional for import
    protected $schoolCache = [];
    protected $programTypeCache = [];
    protected $processedCount = 0;
    protected $skippedCount = 0;
    protected $errorMessages = [];

    /**
     * Process the imported CSV file.
     *
     * @param string $filePath
     * @param array $columnMap
     * @return array
     */
    public function import($filePath, $columnMap = [])
    {
        $this->columnMap = $columnMap;
        $this->processedCount = 0;
        $this->skippedCount = 0;
        $this->errorMessages = [];
        
        // Load CSV
        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0);
        
        // Cache schools and program types for faster lookups
        $this->cacheSchools();
        $this->cacheProgramTypes();
        
        // Get header row and validate
        $this->headerRow = $csv->getHeader();
        
        // Process records
        $records = $csv->getRecords();
        
        foreach ($records as $offset => $record) {
            $this->processRecord($record, $offset + 1);
        }
        
        return [
            'processed' => $this->processedCount,
            'skipped' => $this->skippedCount,
            'errors' => $this->errorMessages
        ];
    }
    
    /**
     * Process a single record from the CSV.
     *
     * @param array $record
     * @param int $rowNumber
     * @return void
     */
    protected function processRecord($record, $rowNumber)
    {
        // Map CSV columns to database fields
        $studentData = $this->mapRecordFields($record);
        
        // Validate required fields
        $validator = $this->validateStudentData($studentData, $rowNumber);
        
        if ($validator->fails()) {
            $this->skippedCount++;
            $this->errorMessages[] = "Row {$rowNumber}: " . implode(', ', $validator->errors()->all());
            return;
        }
        
        // Process school_id and program_type_id (might be names in the import file)
        $studentData = $this->resolveRelationships($studentData);
        
        // Generate a unique registration number - ensure it's unique for each student
        $lastStudent = Student::orderBy('id', 'desc')->first();
        $nextNumber = $lastStudent ? (intval(substr($lastStudent->registration_number, -5)) + 1) : 1;
        $registrationNumber = 'YEG' . date('y') . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        $studentData['registration_number'] = $registrationNumber;
        
        // Set default values for required fields
        $studentData['age'] = $studentData['age'] ?? 0;
        $studentData['parent_contact'] = $studentData['parent_contact'] ?? 'Not provided';
        $studentData['city'] = $studentData['city'] ?? 'Not specified';
        $studentData['status'] = $studentData['status'] ?? 'active';
        $studentData['payment_status'] = $studentData['payment_status'] ?? 'pending';
        
        // Create the student
        try {
            Student::create($studentData);
            $this->processedCount++;
        } catch (\Exception $e) {
            $this->skippedCount++;
            $this->errorMessages[] = "Row {$rowNumber}: Database error - " . $e->getMessage();
            Log::error('Student import error: ' . $e->getMessage(), [
                'row' => $rowNumber,
                'data' => $studentData
            ]);
        }
    }
    
    /**
     * Map CSV columns to database fields based on column mapping.
     *
     * @param array $record
     * @return array
     */
    protected function mapRecordFields($record)
    {
        $data = [];
        
        // If no mapping provided, try to match column headers directly
        if (empty($this->columnMap)) {
            foreach ($record as $column => $value) {
                $fieldName = $this->normalizeColumnName($column);
                // Only add non-empty values
                if (!empty($value) || $value === '0') {
                    $data[$fieldName] = $value;
                }
            }
        } else {
            // Use provided column mapping
            foreach ($this->columnMap as $csvColumn => $dbField) {
                if (isset($record[$csvColumn])) {
                    $value = $record[$csvColumn];
                    // Only add non-empty values
                    if (!empty($value) || $value === '0') {
                        $data[$dbField] = $value;
                    }
                }
            }
        }
        
        return $data;
    }
    
    /**
     * Normalize column name to match database field.
     *
     * @param string $columnName
     * @return string
     */
    protected function normalizeColumnName($columnName)
    {
        // Convert spaces and special chars to underscores
        $normalized = strtolower(trim($columnName));
        $normalized = preg_replace('/[^a-z0-9]+/', '_', $normalized);
        $normalized = preg_replace('/_+/', '_', $normalized);
        $normalized = trim($normalized, '_');
        
        // Map common variations to our field names
        $mappings = [
            'name' => 'full_name',
            'full_name' => 'full_name',
            'fullname' => 'full_name',
            'student_name' => 'full_name',
            'studentname' => 'full_name',
            'parent_phone' => 'parent_contact',
            'parent_number' => 'parent_contact',
            'parent_mobile' => 'parent_contact',
            'guardian_contact' => 'parent_contact',
            'school' => 'school_id',
            'program' => 'program_type_id',
            'program_type' => 'program_type_id',
        ];
        
        return $mappings[$normalized] ?? $normalized;
    }
    
    /**
     * Validate student data.
     *
     * @param array $data
     * @param int $rowNumber
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validateStudentData($data, $rowNumber)
    {
        $rules = [
            'full_name' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:1|max:100',
            'parent_contact' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:255',
            'school_id' => 'nullable',
            'program_type_id' => 'nullable',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'status' => 'nullable|string|max:20',
            'payment_status' => 'nullable|string|max:20',
        ];
        
        return Validator::make($data, $rules);
    }
    
    /**
     * Resolve relationships like school and program type.
     *
     * @param array $data
     * @return array
     */
    protected function resolveRelationships($data)
    {
        // Resolve school_id (might be a name in the import)
        if (isset($data['school_id']) && !is_numeric($data['school_id'])) {
            $schoolName = trim($data['school_id']);
            $data['school_id'] = $this->findSchoolIdByName($schoolName);
        }
        
        // If school_id is missing or couldn't be resolved, set a default
        if (empty($data['school_id'])) {
            // Get the first school as default
            $firstSchool = array_values($this->schoolCache);
            $data['school_id'] = $firstSchool[0] ?? null;
        }
        
        // Resolve program_type_id (might be a name in the import)
        if (isset($data['program_type_id']) && !is_numeric($data['program_type_id'])) {
            $programTypeName = trim($data['program_type_id']);
            $data['program_type_id'] = $this->findProgramTypeIdByName($programTypeName);
        }
        
        // If program_type_id is missing or couldn't be resolved, set a default
        if (empty($data['program_type_id'])) {
            // Get the first program type as default
            $firstProgramType = array_values($this->programTypeCache);
            $data['program_type_id'] = $firstProgramType[0] ?? null;
        }
        
        return $data;
    }
    
    /**
     * Cache schools for faster lookups.
     *
     * @return void
     */
    protected function cacheSchools()
    {
        $schools = School::select('id', 'name')->get();
        foreach ($schools as $school) {
            $this->schoolCache[strtolower($school->name)] = $school->id;
        }
    }
    
    /**
     * Cache program types for faster lookups.
     *
     * @return void
     */
    protected function cacheProgramTypes()
    {
        $programTypes = ProgramType::select('id', 'name')->get();
        foreach ($programTypes as $programType) {
            $this->programTypeCache[strtolower($programType->name)] = $programType->id;
        }
    }
    
    /**
     * Find school ID by name.
     *
     * @param string $name
     * @return int|null
     */
    protected function findSchoolIdByName($name)
    {
        return $this->schoolCache[strtolower($name)] ?? null;
    }
    
    /**
     * Find program type ID by name.
     *
     * @param string $name
     * @return int|null
     */
    protected function findProgramTypeIdByName($name)
    {
        return $this->programTypeCache[strtolower($name)] ?? null;
    }
}
