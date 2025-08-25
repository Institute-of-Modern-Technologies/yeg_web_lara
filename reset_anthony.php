<?php
/**
 * Direct SQL Reset Script for Anthony's Account
 * Simple direct database update without interactive prompts
 */

// Bootstrap the Laravel application
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Use DB facade for direct SQL if needed
use Illuminate\Support\Facades\DB;

echo "=================================================\n";
echo "DIRECT ACCOUNT RESET FOR ANTHONY\n";
echo "=================================================\n";

// Find all users named Anthony
$users = DB::table('users')->where('name', 'like', '%Anthony%')->get();

echo "Found " . count($users) . " users matching 'Anthony'\n\n";

foreach ($users as $user) {
    echo "ID: {$user->id} | Name: {$user->name} | Email: {$user->email}\n";
}

// If you know the exact user ID, you can use it directly
// Replace 123 with Anthony's actual user ID from the list above
$userId = 123; // ← CHANGE THIS TO ANTHONY'S ACTUAL USER ID

echo "\nResetting user ID: {$userId}...\n";

// These are the default values to reset to
// Change these values to what you want Anthony's profile to be reset to
$defaultName = "Anthony"; // Set the original name here
$defaultEmail = "anthony@example.com"; // Set the original email here
$defaultUsername = "anthony"; // Set the original username here
$defaultProfilePhoto = null; // Set to null to remove profile photo

// Perform direct update
$updated = DB::table('users')
    ->where('id', $userId)
    ->update([
        'name' => $defaultName,
        'email' => $defaultEmail,
        'username' => $defaultUsername,
        'profile_photo' => $defaultProfilePhoto,
        'updated_at' => now()
    ]);

if ($updated) {
    echo "SUCCESS: User account has been reset!\n";
} else {
    echo "FAILED: Could not reset user account. Check the user ID.\n";
}

echo "\nDone.\n";
