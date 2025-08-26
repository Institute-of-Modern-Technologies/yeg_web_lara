<?php
/**
 * Reset Anthony's Account with Password
 * This script properly hashes passwords using Laravel's Hash facade
 */

// Bootstrap the Laravel application
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// We need these facades
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

echo "=================================================\n";
echo "RESET ANTHONY'S ACCOUNT WITH PASSWORD\n";
echo "=================================================\n";

// Find all users named Anthony
$users = DB::table('users')->where('name', 'like', '%Anthony%')->get();

if ($users->isEmpty()) {
    echo "No users found with name 'Anthony'\n";
    exit;
}

echo "Found " . count($users) . " users matching 'Anthony':\n\n";

foreach ($users as $index => $user) {
    echo "[{$index}] ID: {$user->id} | Name: {$user->name} | Email: {$user->email}\n";
}

echo "\nWhich Anthony would you like to reset? Enter number [0-" . (count($users) - 1) . "]: ";
$handle = fopen("php://stdin", "r");
$line = trim(fgets($handle));
$index = (int)$line;

if (!isset($users[$index])) {
    echo "Invalid selection.\n";
    exit;
}

$anthonyUser = $users[$index];
echo "\nSelected user: {$anthonyUser->name} (ID: {$anthonyUser->id})\n\n";

// Get new values for the user
echo "Enter new name (leave blank to keep current: {$anthonyUser->name}): ";
$handle = fopen("php://stdin", "r");
$name = trim(fgets($handle));
$name = empty($name) ? $anthonyUser->name : $name;

echo "Enter new email (leave blank to keep current: {$anthonyUser->email}): ";
$handle = fopen("php://stdin", "r");
$email = trim(fgets($handle));
$email = empty($email) ? $anthonyUser->email : $email;

echo "Enter new username (leave blank to keep current: {$anthonyUser->username}): ";
$handle = fopen("php://stdin", "r");
$username = trim(fgets($handle));
$username = empty($username) ? $anthonyUser->username : $username;

echo "Enter new password (leave blank to keep current password): ";
$handle = fopen("php://stdin", "r");
$password = trim(fgets($handle));

// Reset profile photo?
echo "Reset profile photo? (y/n): ";
$handle = fopen("php://stdin", "r");
$resetPhoto = trim(fgets($handle));
$resetProfilePhoto = (strtolower($resetPhoto) === 'y');

// Build the update array
$updateData = [
    'name' => $name,
    'email' => $email,
    'username' => $username,
    'updated_at' => now()
];

// Only update password if a new one was provided
if (!empty($password)) {
    // Use Laravel's Hash facade to properly hash the password
    $updateData['password'] = Hash::make($password);
}

// Reset profile photo if requested
if ($resetProfilePhoto) {
    $updateData['profile_photo'] = null;
}

// Perform the update
try {
    // Using the query builder to update
    $updated = DB::table('users')
        ->where('id', $anthonyUser->id)
        ->update($updateData);
    
    if ($updated) {
        echo "\nSUCCESS: {$anthonyUser->name}'s account has been reset.\n";
        
        if (!empty($password)) {
            echo "Password was properly hashed and updated.\n";
        } else {
            echo "Password was not changed.\n";
        }
        
        if ($resetProfilePhoto) {
            echo "Profile photo was reset.\n";
        }
    } else {
        echo "\nWARNING: No changes were made. User data might already match what you provided.\n";
    }
} catch (\Exception $e) {
    echo "\nERROR: Failed to update user. " . $e->getMessage() . "\n";
}

echo "\nDone.\n";
