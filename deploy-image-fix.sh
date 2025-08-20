#!/bin/bash

# Deployment script for image path fix solution
# Run this on your online server after deploying code changes

echo "===== IMT Website Image Path Fix Deployment ====="
echo "Starting deployment process..."

# Step 1: Clear all Laravel caches
echo ""
echo "1. Clearing Laravel caches..."
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan cache:clear
php artisan optimize:clear

# Step 2: Dump composer autoload to ensure new classes are loaded
echo ""
echo "2. Updating autoloader..."
composer dump-autoload

# Step 3: Check if storage link exists, create if not
echo ""
echo "3. Checking storage symlink..."
if [ ! -L "public/storage" ]; then
    echo "Storage symlink not found, creating..."
    php artisan storage:link
    echo "Storage symlink created."
else
    echo "Storage symlink exists."
fi

# Step 4: Check directory permissions
echo ""
echo "4. Checking directory permissions..."

# Function to check and fix directory permissions
check_permissions() {
    DIR=$1
    echo "Checking $DIR directory..."
    
    if [ ! -d "$DIR" ]; then
        echo "WARNING: $DIR directory not found!"
        return
    fi
    
    # Check if directory is writable
    if [ -w "$DIR" ]; then
        echo "$DIR is writable."
    else
        echo "WARNING: $DIR is not writable, attempting to fix permissions..."
        chmod -R 755 $DIR
        echo "Permissions updated for $DIR"
    fi
}

# Check important directories
check_permissions "storage/app/public"
check_permissions "public/images"
check_permissions "storage/logs"
check_permissions "bootstrap/cache"

# Step 5: Test image service
echo ""
echo "5. Testing image service..."
echo "Visit /test-image-paths.php in your browser to verify image loading."

# Step 6: Display completion message
echo ""
echo "===== Deployment Complete ====="
echo "If you still encounter issues, please check:"
echo "1. Your providers.php file includes App\\Providers\\PathHelperServiceProvider::class"
echo "2. Your .env file has correct APP_URL setting"
echo "3. Browser console for JavaScript errors"
echo ""
echo "You may need to restart your web server for changes to take full effect."
