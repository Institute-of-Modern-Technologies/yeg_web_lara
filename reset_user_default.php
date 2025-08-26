<?php
/**
 * Complete User Reset Script
 * 
 * This script resets a user account to default values, 
 * including all related data in the student table if applicable.
 */

// Bootstrap the Laravel application
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;

echo "=================================================\n";
echo "COMPLETE USER RESET TOOL\n";
echo "=================================================\n";

// Find all users named Anthony
$users = User::where('name', 'like', '%Anthony%')->get();

if ($users->isEmpty()) {
    echo "No users found with name 'Anthony'\n";
    exit;
}

echo "Found " . $users->count() . " users matching 'Anthony':\n\n";

foreach ($users as $index => $user) {
    echo "[{$index}] ID: {$user->id} | Name: {$user->name} | Email: {$user->email} | Username: {$user->username}\n";
    
    // Show if user has student profile
    if ($user->student) {
        echo "    Has student profile (ID: {$user->student->id})\n";
    } else {
        echo "    No student profile\n";
    }
}

echo "\nWhich Anthony would you like to reset? Enter number [0-" . ($users->count() - 1) . "]: ";
$handle = fopen("php://stdin", "r");
$line = trim(fgets($handle));
$index = (int)$line;

if (!isset($users[$index])) {
    echo "Invalid selection.\n";
    exit;
}

$selectedUser = $users[$index];
echo "\nSelected user: {$selectedUser->name} (ID: {$selectedUser->id})\n";

// Ask for default values for the user account
echo "\n=================================================\n";
echo "SETTING DEFAULT USER VALUES\n";
echo "=================================================\n";

echo "Enter default name for this user (leave blank to keep current: {$selectedUser->name}): ";
$handle = fopen("php://stdin", "r");
$name = trim(fgets($handle));
$name = empty($name) ? $selectedUser->name : $name;

echo "Enter default email for this user (leave blank to keep current: {$selectedUser->email}): ";
$handle = fopen("php://stdin", "r");
$email = trim(fgets($handle));
$email = empty($email) ? $selectedUser->email : $email;

echo "Enter default username for this user (leave blank to keep current: {$selectedUser->username}): ";
$handle = fopen("php://stdin", "r");
$username = trim(fgets($handle));
$username = empty($username) ? $selectedUser->username : $username;

echo "Set a new password? (y/n): ";
$handle = fopen("php://stdin", "r");
$resetPassword = trim(fgets($handle));
$newPassword = null;

if (strtolower($resetPassword) === 'y') {
    echo "Enter new password: ";
    $handle = fopen("php://stdin", "r");
    $newPassword = trim(fgets($handle));
}

echo "Reset profile photo to empty? (y/n): ";
$handle = fopen("php://stdin", "r");
$resetPhoto = trim(fgets($handle));
$resetProfilePhoto = (strtolower($resetPhoto) === 'y');

// Build update data for user
$userUpdateData = [
    'name' => $name,
    'email' => $email,
    'username' => $username,
    'updated_at' => now()
];

// Add password if needed
if (!empty($newPassword)) {
    $userUpdateData['password'] = Hash::make($newPassword);
}

// Reset profile photo if requested
if ($resetProfilePhoto) {
    $userUpdateData['profile_photo'] = null;
}

// Check if user has a student profile
$studentUpdateData = [];
if ($selectedUser->student) {
    echo "\n=================================================\n";
    echo "USER HAS STUDENT PROFILE - SET STUDENT DEFAULT VALUES\n";
    echo "=================================================\n";
    
    $student = $selectedUser->student;
    
    echo "Reset student profile data as well? (y/n): ";
    $handle = fopen("php://stdin", "r");
    $resetStudent = trim(fgets($handle));
    
    if (strtolower($resetStudent) === 'y') {
        // School relationship
        echo "Enter default school ID (leave blank to keep current: {$student->school_id}): ";
        $handle = fopen("php://stdin", "r");
        $schoolId = trim(fgets($handle));
        if (!empty($schoolId)) {
            $studentUpdateData['school_id'] = $schoolId;
        }
        
        // Program Type
        echo "Enter default program type ID (leave blank to keep current: {$student->program_type_id}): ";
        $handle = fopen("php://stdin", "r");
        $programTypeId = trim(fgets($handle));
        if (!empty($programTypeId)) {
            $studentUpdateData['program_type_id'] = $programTypeId;
        }
        
        // Stage
        echo "Enter default stage ID (leave blank to keep current: {$student->stage_id}): ";
        $handle = fopen("php://stdin", "r");
        $stageId = trim(fgets($handle));
        if (!empty($stageId)) {
            $studentUpdateData['stage_id'] = $stageId;
        }
        
        // Gender
        echo "Enter default gender (leave blank to keep current: {$student->gender}): ";
        $handle = fopen("php://stdin", "r");
        $gender = trim(fgets($handle));
        if (!empty($gender)) {
            $studentUpdateData['gender'] = $gender;
        }
        
        // Class
        echo "Enter default class (leave blank to keep current: {$student->class}): ";
        $handle = fopen("php://stdin", "r");
        $class = trim(fgets($handle));
        if (!empty($class)) {
            $studentUpdateData['class'] = $class;
        }
    }
}

// Confirm reset
echo "\n=================================================\n";
echo "CONFIRM RESET\n";
echo "=================================================\n";

echo "Are you sure you want to reset this user to default values? (y/n): ";
$handle = fopen("php://stdin", "r");
$confirm = trim(fgets($handle));

if (strtolower($confirm) !== 'y') {
    echo "\nReset cancelled.\n";
    exit;
}

// Perform the reset within a transaction
try {
    DB::beginTransaction();
    
    // Update user
    $selectedUser->update($userUpdateData);
    
    // Update student if needed
    if ($selectedUser->student && !empty($studentUpdateData)) {
        $selectedUser->student->update($studentUpdateData);
    }
    
    DB::commit();
    
    echo "\nSUCCESS: User has been reset to default values.\n";
    
    // Show summary of changes
    echo "\nUser account reset summary:\n";
    echo "- Name: {$selectedUser->name}\n";
    echo "- Email: {$selectedUser->email}\n";
    echo "- Username: {$selectedUser->username}\n";
    
    if (!empty($newPassword)) {
        echo "- Password: Changed to new value\n";
    } else {
        echo "- Password: Not changed\n";
    }
    
    if ($resetProfilePhoto) {
        echo "- Profile photo: Reset to null\n";
    } else {
        echo "- Profile photo: Not changed\n";
    }
    
    if ($selectedUser->student && !empty($studentUpdateData)) {
        echo "\nStudent profile reset summary:\n";
        foreach ($studentUpdateData as $key => $value) {
            echo "- {$key}: {$value}\n";
        }
    }
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "\nERROR: Failed to reset user. " . $e->getMessage() . "\n";
}

echo "\nDone.\n";
