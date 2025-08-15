<?php
// Script to link school accounts to user accounts
// Run with: php link_school_user.php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\DB;

// Get all schools
$schools = School::all();
$count = 0;

echo "Starting to link schools with users...\n";

foreach ($schools as $school) {
    echo "Processing school: {$school->name} (ID: {$school->id})\n";
    
    // Find user by email
    $user = User::where('email', $school->email)->first();
    
    if ($user) {
        echo "  Found matching user: {$user->name} (ID: {$user->id})\n";
        
        // Update the school record
        $school->user_id = $user->id;
        $school->save();
        
        echo "  Updated school record with user_id: {$user->id}\n";
        $count++;
    } else {
        echo "  No matching user found for email: {$school->email}\n";
    }
}

echo "\nFinished! Linked {$count} schools with user accounts.\n";
