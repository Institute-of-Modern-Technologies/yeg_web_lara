<?php
// Script to create an admin user

// Bootstrap the Laravel application
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Hash;

// Ensure user type exists
if (!UserType::where('slug', 'super_admin')->exists()) {
    UserType::create([
        'name' => 'Super Admin',
        'slug' => 'super_admin',
        'description' => 'Has complete system access'
    ]);
    echo "Created Super Admin user type.\n";
}

// Create admin user
try {
    $admin = User::firstOrCreate(
        ['email' => 'admin@yeg.com'],
        [
            'name' => 'Admin User',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'user_type_id' => UserType::where('slug', 'super_admin')->first()->id
        ]
    );
    
    if ($admin->wasRecentlyCreated) {
        echo "Admin user created successfully!\n";
        echo "Email: admin@yeg.com\n";
        echo "Password: admin123\n";
    } else {
        echo "Admin user already exists.\n";
        
        // Update password if requested
        $admin->password = Hash::make('admin123');
        $admin->save();
        echo "Admin password has been reset to: admin123\n";
    }
} catch (Exception $e) {
    echo "Error creating admin user: " . $e->getMessage() . "\n";
}
