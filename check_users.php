<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get last 5 users
$users = App\Models\User::orderBy('id', 'desc')->limit(5)->get(['id', 'name', 'email', 'username', 'user_type_id']);
echo "Recent Users:\n";
foreach ($users as $user) {
    echo "ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, Username: {$user->username}, Type: {$user->user_type_id}\n";
}

// Check if de-youngsters user exists
$deYoungstersUser = App\Models\User::where('username', 'de-youngsters')->first();
echo "\nDe-Youngsters User:\n";
if ($deYoungstersUser) {
    echo "Found - ID: {$deYoungstersUser->id}, Name: {$deYoungstersUser->name}, Email: {$deYoungstersUser->email}\n";
} else {
    echo "Not found\n";
}

// Check school with user_id
$school = App\Models\School::where('name', 'like', '%youngsters%')->first();
echo "\nSchool:\n";
if ($school) {
    echo "Found - ID: {$school->id}, Name: {$school->name}, User ID: " . ($school->user_id ?? 'NULL') . "\n";
} else {
    echo "No school with 'youngsters' in the name found\n";
}
