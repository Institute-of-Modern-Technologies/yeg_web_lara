<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== School Approval Test ===" . PHP_EOL;

// Check approved schools
$schools = App\Models\School::where('status', 'approved')->get();

echo "Found " . $schools->count() . " approved schools:" . PHP_EOL . PHP_EOL;

foreach($schools as $school) {
    echo "School: " . $school->name . PHP_EOL;
    echo "Email: " . $school->email . PHP_EOL;
    echo "Status: " . $school->status . PHP_EOL;
    echo "User ID: " . ($school->user_id ?? 'Not linked') . PHP_EOL;
    
    if($school->user_id) {
        $user = App\Models\User::find($school->user_id);
        if($user) {
            echo "Linked User: " . $user->username . PHP_EOL;
            echo "User Email: " . $user->email . PHP_EOL;
            echo "User Type ID: " . $user->user_type_id . PHP_EOL;
            
            // Check user type
            $userType = App\Models\UserType::find($user->user_type_id);
            if($userType) {
                echo "User Type: " . $userType->name . " (" . $userType->slug . ")" . PHP_EOL;
            }
        } else {
            echo "ERROR: User ID exists but user not found!" . PHP_EOL;
        }
    } else {
        echo "WARNING: No user account linked to this school" . PHP_EOL;
    }
    
    echo "---" . PHP_EOL;
}

// Check if there are any pending schools that could be tested
$pendingSchools = App\Models\School::where('status', 'pending')->get();
echo PHP_EOL . "Found " . $pendingSchools->count() . " pending schools:" . PHP_EOL;
foreach($pendingSchools as $school) {
    echo "- " . $school->name . " (" . $school->email . ")" . PHP_EOL;
}

echo PHP_EOL . "=== Test Complete ===" . PHP_EOL;
