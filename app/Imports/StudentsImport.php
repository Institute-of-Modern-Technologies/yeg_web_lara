<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\School;
use App\Models\ProgramType;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Notifications\StudentApprovalNotification;

class StudentsImport
{
    protected $headerRow;
    protected $columnMap = [];
    protected $schoolMapping = [];
    // All fields are now optional for import
    protected $schoolCache = [];
    protected $programTypeCache = [];
    protected $processedCount = 0;
    protected $skippedCount = 0;
    protected $errorMessages = [];
    protected $warningMessages = [];

    /**
     * Extract unique school names from the CSV file for matching.
     *
     * @param string $filePath
     * @param array $columnMapping Optional column mapping
     * @return array Unique school names and preview data
     */
    public function extractUniqueSchools($filePath, $columnMapping = [])
    {
        // Store column mapping
        $this->columnMap = $columnMapping;
        
        try {
            // Read CSV file
            $csv = Reader::createFromPath($filePath, 'r');
            $csv->setHeaderOffset(0);
            $records = $csv->getRecords();
            
            $uniqueSchools = [];
            $preview = [];
            $rowCount = 0;
            
            // Process each record to extract school names
            foreach ($records as $record) {
                $rowCount++;
                
                // Convert record to student data format
                $studentData = $this->mapRecordFields($record);
                
                // Get school name
                if (!empty($studentData['school_id']) && !is_numeric($studentData['school_id'])) {
                    $schoolName = trim($studentData['school_id']);
                    if (!empty($schoolName) && !in_array($schoolName, $uniqueSchools)) {
                        $uniqueSchools[] = $schoolName;
                    }
                }
                
                // Add to preview (only first 5 rows)
                if ($rowCount <= 5) {
                    $preview[] = $studentData;
                }
                
                // Limit to first 100 rows for efficiency
                if ($rowCount >= 100) {
                    break;
                }
            }
            
            return [
                'schools' => $uniqueSchools,
                'preview' => $preview
            ];
            
        } catch (\Exception $e) {
            Log::error('CSV school extraction error: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Process the imported CSV file.
     *
     * @param string $filePath
     * @param array $columnMap
     * @param array $schoolMapping Optional mapping of school names to IDs
     * @return array
     */
    public function import($filePath, $columnMap = [], $schoolMapping = [])
    {
        $this->columnMap = $columnMap;
        $this->schoolMapping = $schoolMapping;
        $this->processedCount = 0;
        $this->skippedCount = 0;
        $this->errorMessages = [];
        $this->warningMessages = [];
        
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
            'errors' => $this->errorMessages,
            'warnings' => $this->warningMessages
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
        // Debug the raw record
        Log::info("Processing row {$rowNumber}", ['raw_record' => $record]);
        
        // Convert record to student data format
        $studentData = $this->mapRecordFields($record);
        Log::info("After mapping", ['mapped_data' => $studentData]);
        
        // Validate data
        $validator = $this->validateStudentData($studentData, $rowNumber);
        
        if ($validator->fails()) {
            $this->skippedCount++;
            $this->errorMessages[] = "Row {$rowNumber}: " . implode(', ', $validator->errors()->all());
            return;
        }
        
        // Explicitly check for parent_contact mapping issues
        // If we have any field that might be a contact but wasn't mapped correctly
        foreach ($record as $key => $value) {
            $keyLower = strtolower($key);
            if ((str_contains($keyLower, 'parent') || str_contains($keyLower, 'contact') || 
                str_contains($keyLower, 'phone') || str_contains($keyLower, 'tel') || 
                str_contains($keyLower, 'mobile') || str_contains($keyLower, 'guardian')) && 
                !empty($value) && !isset($studentData['parent_contact'])) {
                $studentData['parent_contact'] = $value;
                Log::info("Found potential parent contact in column: {$key}", ['value' => $value]);
            }
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
            $student = Student::create($studentData);
            $this->processedCount++;
            
            // Create user account for the student if they have an email address
            if (!empty($student->email)) {
                $this->createStudentUser($student);
            }
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
            'parent_contact' => 'parent_contact',
            'parent_phone' => 'parent_contact',
            'parent_number' => 'parent_contact',
            'parent_mobile' => 'parent_contact',
            'parent' => 'parent_contact',
            'contact' => 'parent_contact',
            'guardian_contact' => 'parent_contact',
            'guardian' => 'parent_contact',
            'phone' => 'phone',
            'telephone' => 'phone',
            'mobile' => 'phone',
            'student_phone' => 'phone',
            'student_mobile' => 'phone',
            'student_telephone' => 'phone',
            'cell' => 'phone',
            'cellphone' => 'phone',
            'phone_number' => 'phone',
            'contact_number' => 'phone',
            'school' => 'school_id',
            'school_name' => 'school_id',
            'school_id' => 'school_id',
            'program' => 'program_type_id',
            'program_type' => 'program_type_id',
            'program_name' => 'program_type_id',
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
        // Debug what's coming in
        Log::info("Resolving relationships for data", $data);
        
        // Resolve school_id (might be a name in the import)
        if (isset($data['school_id']) && !is_numeric($data['school_id'])) {
            $schoolName = trim($data['school_id']);
            Log::info("Attempting to resolve school name: '{$schoolName}'");
            
            // First check if we have a mapping for this school name from the dropdown selection
            if (!empty($this->schoolMapping) && isset($this->schoolMapping[$schoolName])) {
                $schoolId = $this->schoolMapping[$schoolName];
                Log::info("Using user-selected school mapping for '{$schoolName}': ID {$schoolId}");
                $data['school_id'] = $schoolId;
            } else {
                // No mapping provided, try to find by exact name first
                $school = School::where('name', 'like', $schoolName)->first();
                
                if ($school) {
                    Log::info("Found exact school match: {$school->name} (ID: {$school->id})");
                    $data['school_id'] = $school->id;
                } else {
                    // Try fuzzy match as a fallback
                    $schoolId = $this->findSchoolIdByName($schoolName);
                    
                    if ($schoolId) {
                        Log::info("Found fuzzy school match with ID: {$schoolId}");
                        $data['school_id'] = $schoolId;
                    } else {
                        // At this point we couldn't match the school
                        Log::info("Could not match school: '{$schoolName}'");
                        $this->warningMessages[] = "School name not found: '{$schoolName}'. Creating a new school record.";
                        
                        // Create a new school with this name
                        try {
                            $newSchool = School::create([
                                'name' => $schoolName,
                                'status' => 'active'
                            ]);
                            $data['school_id'] = $newSchool->id;
                            Log::info("Created new school with name '{$schoolName}' and ID {$newSchool->id}");
                        } catch (\Exception $e) {
                            Log::error("Failed to create new school: {$e->getMessage()}");
                            // Fall back to default school
                            $firstSchool = School::first();
                            if ($firstSchool) {
                                $data['school_id'] = $firstSchool->id;
                                $this->warningMessages[] = "Could not create new school '{$schoolName}'. Using {$firstSchool->name} as default."; 
                            }
                        }
                    }
                }
            }
        }
        
        // If school_id is still missing after all attempts, set a default
        if (empty($data['school_id'])) {
            $firstSchool = School::first();
            if ($firstSchool) {
                $data['school_id'] = $firstSchool->id;
                Log::info("Using default school: {$firstSchool->name} (ID: {$firstSchool->id})");
            }
        }
        
        // Resolve program_type_id (might be a name in the import)
        if (isset($data['program_type_id']) && !is_numeric($data['program_type_id'])) {
            $programTypeName = trim($data['program_type_id']);
            $programType = ProgramType::where('name', 'like', $programTypeName)->first();
            
            if ($programType) {
                $data['program_type_id'] = $programType->id;
            } else {
                // Try fuzzy match
                $programTypeId = $this->findProgramTypeIdByName($programTypeName);
                if ($programTypeId) {
                    $data['program_type_id'] = $programTypeId;
                }
            }
        }
        
        // If program_type_id is missing or couldn't be resolved, set a default
        if (empty($data['program_type_id'])) {
            $firstProgramType = ProgramType::first();
            if ($firstProgramType) {
                $data['program_type_id'] = $firstProgramType->id;
                Log::info("Using default program type: {$firstProgramType->name}");
            }
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
        $this->schoolCache = [];
        $schools = School::select('id', 'name')->get();
        
        foreach ($schools as $school) {
            // Store with lowercase for case-insensitive matching
            $this->schoolCache[strtolower($school->name)] = $school->id;
        }
        
        // Sort the school cache by key length (descending) to ensure longer names are matched first
        // This improves matching for schools with similar names
        uksort($this->schoolCache, function($a, $b) {
            return strlen($b) - strlen($a);
        });
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
     * Find school ID by name with fuzzy matching.
     *
     * @param string $name
     * @return int|null
     */
    protected function findSchoolIdByName($name)
    {
        if (empty($name)) {
            return null;
        }

        // Print to debug log to see what's being attempted to match
        Log::info("Trying to match school name: '{$name}'");
        Log::info("Available schools: ", $this->schoolCache);

        // Exact match first (case insensitive)
        $nameLower = strtolower(trim($name));
        if (isset($this->schoolCache[$nameLower])) {
            Log::info("Found exact match for school: {$name}");
            return $this->schoolCache[$nameLower];
        }
        
        // Try partial matches
        foreach ($this->schoolCache as $schoolName => $id) {
            // Check for partial matches in either direction
            if (str_contains($nameLower, $schoolName) || str_contains($schoolName, $nameLower)) {
                Log::info("Found partial match for school '{$name}': '{$schoolName}'");
                return $id;
            }
        }
        
        // Try more aggressive fuzzy matching - using similar_text for approximate matching
        $bestMatch = null;
        $highestPercent = 0;
        
        foreach ($this->schoolCache as $schoolName => $id) {
            similar_text($nameLower, $schoolName, $percent);
            if ($percent > 80 && $percent > $highestPercent) { // At least 80% similarity
                $highestPercent = $percent;
                $bestMatch = $id;
            }
        }
        
        if ($bestMatch) {
            Log::info("Found fuzzy match for school '{$name}' with {$highestPercent}% similarity");
            return $bestMatch;
        }
        
        // If no match found, log this for debugging
        Log::info("School name not found: '{$name}'", ['available_schools' => array_keys($this->schoolCache)]);
        
        return null;
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
    
    /**
     * Create a user account for a student
     * 
     * @param  \App\Models\Student  $student
     * @return \App\Models\User|null
     */
    protected function createStudentUser($student)
    {
        // Don't create user account if email is missing
        if (empty($student->email)) {
            return null;
        }
        
        // Check if user already exists with this email
        $existingUser = User::where('email', $student->email)->first();
        if ($existingUser) {
            Log::info('User already exists for imported student: ' . $student->id);
            return $existingUser;
        }
        
        try {
            // Get or create student user type
            $studentUserType = UserType::firstOrCreate(
                ['slug' => 'student'],
                ['name' => 'Student', 'description' => 'Regular student account']
            );
            
            // Create username from first name or full name
            $nameParts = explode(' ', $student->full_name);
            $firstName = $nameParts[0] ?? '';
            $baseUsername = strtolower($firstName ?: $student->full_name);
            $username = $baseUsername;
            $counter = 1;
            
            // Check if username exists
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . $counter++;
            }
            
            // Generate a random password
            $password = Str::random(8);
            
            // Create user account
            $user = User::create([
                'name' => $student->full_name,
                'email' => $student->email,
                'username' => $username,
                'password' => Hash::make($password),
                'user_type_id' => $studentUserType->id
            ]);
            
            // Store the plaintext password temporarily to include in notification
            $user->temp_password = $password;
            
            // Send notification with login credentials
            $student->notify(new StudentApprovalNotification($student, $username, $password));
            
            Log::info('User account created for imported student: ' . $student->id);
            return $user;
            
        } catch (\Exception $e) {
            Log::error('Failed to create user for imported student: ' . $e->getMessage());
            return null;
        }
    }
}
