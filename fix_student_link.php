<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Available student profiles to link:\n";
echo "===================================\n";

$students = App\Models\Student::orderBy('id', 'desc')->take(10)->get();
foreach ($students as $student) {
    echo "ID: " . $student->id . " - Name: " . $student->full_name . "\n";
}

echo "\nWhich student ID would you like to link to your user account?\n";
echo "Enter the student ID: ";

$handle = fopen("php://stdin", "r");
$studentId = trim(fgets($handle));
fclose($handle);

if (!is_numeric($studentId)) {
    echo "Invalid student ID. Exiting.\n";
    exit(1);
}

$student = App\Models\Student::find($studentId);
if (!$student) {
    echo "Student not found. Exiting.\n";
    exit(1);
}

echo "\nWhich user email would you like to link this student to?\n";
$users = App\Models\User::all();
foreach ($users as $user) {
    echo "ID: " . $user->id . " - Email: " . $user->email . "\n";
}

echo "Enter the user ID: ";
$handle = fopen("php://stdin", "r");
$userId = trim(fgets($handle));
fclose($handle);

if (!is_numeric($userId)) {
    echo "Invalid user ID. Exiting.\n";
    exit(1);
}

$user = App\Models\User::find($userId);
if (!$user) {
    echo "User not found. Exiting.\n";
    exit(1);
}

// Update the student record to link it to the user
$student->user_id = $user->id;
$student->save();

echo "\nSuccess! Linked student '" . $student->full_name . "' to user '" . $user->email . "'\n";
echo "You should now be able to access the challenges page.\n";
