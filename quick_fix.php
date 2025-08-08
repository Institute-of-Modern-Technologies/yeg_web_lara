<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Let's link the most recent student (Anthony Adjekum, ID: 53) to a user account
// We'll use the student@example.com user (ID: 4) as it seems like a test student account

$student = App\Models\Student::find(53); // Anthony Adjekum
$user = App\Models\User::find(4); // student@example.com

if ($student && $user) {
    $student->user_id = $user->id;
    $student->save();
    
    echo "Successfully linked:\n";
    echo "Student: " . $student->full_name . " (ID: " . $student->id . ")\n";
    echo "User: " . $user->email . " (ID: " . $user->id . ")\n";
    echo "\nNow try logging in as student@example.com and accessing the challenges page.\n";
} else {
    echo "Error: Could not find student or user records.\n";
    if (!$student) echo "Student ID 53 not found.\n";
    if (!$user) echo "User ID 4 not found.\n";
}
