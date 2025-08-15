<?php
// Script to manually link a specific user to a specific school
// Run with: php manual_link.php <login_email> <school_id>

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\School;
use App\Models\User;

if ($argc < 3) {
    echo "Usage: php manual_link.php <login_email> <school_id>\n";
    echo "Example: php manual_link.php school@example.com 1\n";
    echo "\nAvailable schools:\n";
    
    $schools = School::all(['id', 'name', 'email']);
    foreach ($schools as $school) {
        echo "ID: {$school->id}, Name: {$school->name}, Email: " . ($school->email ?: '(empty)') . "\n";
    }
    
    echo "\nAvailable users:\n";
    $users = User::all(['id', 'name', 'email']);
    foreach ($users as $user) {
        echo "ID: {$user->id}, Name: {$user->name}, Email: {$user->email}\n";
    }
    
    exit(1);
}

$email = $argv[1];
$schoolId = $argv[2];

// Find the user
$user = User::where('email', $email)->first();
if (!$user) {
    echo "Error: No user found with email {$email}\n";
    exit(1);
}

// Find the school
$school = School::find($schoolId);
if (!$school) {
    echo "Error: No school found with ID {$schoolId}\n";
    exit(1);
}

// Link them
$school->user_id = $user->id;
$school->save();

echo "Success! Linked user {$user->name} (ID: {$user->id}) to school {$school->name} (ID: {$school->id}).\n";

// Update the school email to match user if needed
if (empty($school->email) || $school->email === 'pending@example.com') {
    $school->email = $user->email;
    $school->save();
    echo "Updated school email to match user email: {$user->email}\n";
}

echo "\nYou should now be able to log in successfully!\n";
