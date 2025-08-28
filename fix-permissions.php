<?php
/**
 * Fix Permissions Script
 * 
 * This script sets appropriate permissions on image directories
 * to prevent permission denied errors during image uploads or deletions.
 */

// Bootstrap the Laravel application
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "======================================================\n";
echo "FIXING FILE PERMISSIONS\n";
echo "======================================================\n";
echo "Running on: " . gethostname() . "\n";
echo "Environment: " . app()->environment() . "\n";
echo "User running script: " . exec('whoami') . "\n";
echo "------------------------------------------------------\n\n";

// Define image directories to fix
$directories = [
    'images',
    'images/partner-schools',
    'images/hero-sections',
    'images/events',
    'images/testimonials',
    'images/happenings',
    'images/trainers',
    'images/schools',
    'images/profile-photos',
];

// Get web server user (usually www-data or apache)
$webServerUser = null;
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    echo "Running on Windows - skipping web server user check\n";
} else {
    // Try to detect web server user
    $possibleUsers = ['www-data', 'apache', 'nginx', 'nobody', 'httpd'];
    $processUser = trim(exec('ps aux | grep -E "apache|httpd|nginx" | grep -v "grep" | head -1 | cut -d " " -f 1'));
    
    if ($processUser && !in_array($processUser, ['root'])) {
        $webServerUser = $processUser;
    } else {
        foreach ($possibleUsers as $user) {
            if (posix_getpwnam($user)) {
                $webServerUser = $user;
                break;
            }
        }
    }
    
    echo "Detected web server user: " . ($webServerUser ?: "Unable to detect") . "\n\n";
}

// Fix permissions for each directory
foreach ($directories as $directory) {
    $fullPath = public_path($directory);
    
    echo "Processing: {$directory}\n";
    
    // Create directory if it doesn't exist
    if (!file_exists($fullPath)) {
        echo "  Creating directory... ";
        if (mkdir($fullPath, 0755, true)) {
            echo "SUCCESS\n";
        } else {
            echo "FAILED\n";
            echo "  Attempting with shell command...\n";
            shell_exec("mkdir -p " . escapeshellarg($fullPath));
            
            if (file_exists($fullPath)) {
                echo "  Created successfully with shell command\n";
            } else {
                echo "  FAILED to create directory\n";
                continue;
            }
        }
    } else {
        echo "  Directory exists\n";
    }
    
    // Set directory permissions
    echo "  Setting directory permissions to 0755... ";
    if (chmod($fullPath, 0755)) {
        echo "SUCCESS\n";
    } else {
        echo "FAILED\n";
        echo "  Attempting with shell command...\n";
        shell_exec("chmod 755 " . escapeshellarg($fullPath));
        echo "  Command executed\n";
    }
    
    // Change ownership if we know the web server user and we're not on Windows
    if ($webServerUser && strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
        echo "  Setting ownership to {$webServerUser}... ";
        $currentUser = exec('whoami');
        
        // Use shell_exec for chown as it might require sudo
        $chownCommand = "chown -R {$webServerUser}:{$webServerUser} " . escapeshellarg($fullPath);
        shell_exec($chownCommand);
        echo "Command executed (may require sudo to work)\n";
        
        // Provide instructions for manual fix if likely to be necessary
        echo "  If permission problems persist, run this command as root/sudo:\n";
        echo "  sudo {$chownCommand}\n";
    }
    
    // Check and fix permissions on existing files
    if (file_exists($fullPath) && is_dir($fullPath)) {
        $files = glob($fullPath . '/*');
        $fileCount = count($files);
        
        echo "  Found {$fileCount} files in directory\n";
        
        if ($fileCount > 0) {
            echo "  Setting files to 0644 permissions... ";
            
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                foreach ($files as $file) {
                    if (is_file($file)) {
                        chmod($file, 0644);
                    }
                }
            } else {
                shell_exec("find " . escapeshellarg($fullPath) . " -type f -exec chmod 644 {} \\;");
            }
            
            echo "DONE\n";
        }
    }
    
    echo "----------------------------------------\n";
}

echo "\n======================================================\n";
echo "PERMISSION FIX COMPLETED\n";
echo "======================================================\n";
echo "If you still encounter permission issues, please check:\n";
echo "1. Your web server is running as the detected user\n";
echo "2. You have proper permissions on the parent directories\n";
echo "3. SELinux or AppArmor settings (if applicable)\n\n";

echo "To run with root privileges (if needed):\n";
echo "sudo php fix-permissions.php\n";
echo "======================================================\n";
