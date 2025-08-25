<?php
/**
 * Reset User Profile Script
 * 
 * This script helps reset a specific user's profile to default values or 
 * restore it to an earlier state.
 */

// Bootstrap the Laravel application
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "======================================================\n";
echo "USER PROFILE RESET TOOL\n";
echo "======================================================\n";

// Function to search for users by name
function searchUserByName($name) {
    return \App\Models\User::where('name', 'like', "%{$name}%")->get();
}

// Function to display user info
function displayUserInfo($user) {
    echo "ID: {$user->id}\n";
    echo "Name: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "Username: {$user->username}\n";
    echo "User Type: {$user->user_type_id}\n";
    echo "Profile Photo: {$user->profile_photo}\n";
    echo "Created At: {$user->created_at}\n";
    echo "Updated At: {$user->updated_at}\n";
    
    // If this user has student information, display it
    if ($user->student) {
        echo "\nStudent Information:\n";
        echo "Gender: {$user->student->gender}\n";
        echo "School: {$user->student->school_id}\n";
        echo "Class: {$user->student->class}\n";
        // Add more student fields as needed
    }
}

// Search for user named Anthony
echo "\nSearching for user 'Anthony'...\n";
$users = searchUserByName('Anthony');

if ($users->isEmpty()) {
    echo "No users found with name containing 'Anthony'. Please try another search.\n";
    exit;
}

echo "\nFound " . $users->count() . " matching users:\n";

// List all found users
foreach ($users as $index => $user) {
    echo "\n--- User #" . ($index + 1) . " ---\n";
    displayUserInfo($user);
    echo "-------------------------\n";
}

// If multiple users found, ask which one to reset
$selectedUser = null;
if ($users->count() > 1) {
    echo "\nMultiple users found. Which user would you like to reset? (Enter the number): ";
    $handle = fopen("php://stdin", "r");
    $line = trim(fgets($handle));
    $index = (int)$line - 1;
    
    if ($index < 0 || $index >= $users->count()) {
        echo "Invalid selection.\n";
        exit;
    }
    
    $selectedUser = $users[$index];
} else {
    $selectedUser = $users[0];
}

// Display the selected user's information
echo "\nSelected user to reset:\n";
displayUserInfo($selectedUser);

// Reset options menu
echo "\nWhat would you like to reset?\n";
echo "1. Name\n";
echo "2. Email\n";
echo "3. Username\n";
echo "4. Profile Photo\n";
echo "5. All profile fields\n";
echo "6. Student information (if applicable)\n";
echo "7. Cancel\n";

echo "\nEnter your choice (1-7): ";
$handle = fopen("php://stdin", "r");
$choice = trim(fgets($handle));

// Get new values for reset
switch ($choice) {
    case '1':
        echo "\nEnter new name: ";
        $handle = fopen("php://stdin", "r");
        $newName = trim(fgets($handle));
        $selectedUser->name = $newName;
        $selectedUser->save();
        echo "Name has been reset to: {$newName}\n";
        break;
        
    case '2':
        echo "\nEnter new email: ";
        $handle = fopen("php://stdin", "r");
        $newEmail = trim(fgets($handle));
        $selectedUser->email = $newEmail;
        $selectedUser->save();
        echo "Email has been reset to: {$newEmail}\n";
        break;
        
    case '3':
        echo "\nEnter new username: ";
        $handle = fopen("php://stdin", "r");
        $newUsername = trim(fgets($handle));
        $selectedUser->username = $newUsername;
        $selectedUser->save();
        echo "Username has been reset to: {$newUsername}\n";
        break;
        
    case '4':
        // Reset profile photo to default or remove it
        $selectedUser->profile_photo = null;
        $selectedUser->save();
        echo "Profile photo has been reset to default.\n";
        break;
        
    case '5':
        echo "\nAre you sure you want to reset all profile fields? This cannot be undone. (y/n): ";
        $handle = fopen("php://stdin", "r");
        $confirm = trim(fgets($handle));
        
        if (strtolower($confirm) === 'y') {
            echo "\nEnter new name: ";
            $handle = fopen("php://stdin", "r");
            $newName = trim(fgets($handle));
            
            echo "Enter new email: ";
            $handle = fopen("php://stdin", "r");
            $newEmail = trim(fgets($handle));
            
            echo "Enter new username: ";
            $handle = fopen("php://stdin", "r");
            $newUsername = trim(fgets($handle));
            
            $selectedUser->name = $newName;
            $selectedUser->email = $newEmail;
            $selectedUser->username = $newUsername;
            $selectedUser->profile_photo = null;
            $selectedUser->save();
            
            echo "\nAll profile fields have been reset.\n";
        } else {
            echo "Reset cancelled.\n";
        }
        break;
        
    case '6':
        if ($selectedUser->student) {
            echo "\nAre you sure you want to reset student information? This cannot be undone. (y/n): ";
            $handle = fopen("php://stdin", "r");
            $confirm = trim(fgets($handle));
            
            if (strtolower($confirm) === 'y') {
                $student = $selectedUser->student;
                
                // Reset student fields to defaults or original values
                // Modify these according to your specific requirements
                echo "Enter school ID (leave empty to keep current value): ";
                $handle = fopen("php://stdin", "r");
                $newSchoolId = trim(fgets($handle));
                
                if (!empty($newSchoolId)) {
                    $student->school_id = $newSchoolId;
                }
                
                echo "Enter class (leave empty to keep current value): ";
                $handle = fopen("php://stdin", "r");
                $newClass = trim(fgets($handle));
                
                if (!empty($newClass)) {
                    $student->class = $newClass;
                }
                
                $student->save();
                echo "\nStudent information has been reset.\n";
            } else {
                echo "Reset cancelled.\n";
            }
        } else {
            echo "\nThis user does not have associated student information.\n";
        }
        break;
        
    case '7':
        echo "Operation cancelled.\n";
        break;
        
    default:
        echo "Invalid choice.\n";
        break;
}

echo "\n======================================================\n";
echo "User profile reset operation completed!\n";
echo "======================================================\n";
