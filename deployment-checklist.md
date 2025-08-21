# Laravel Image System Deployment Checklist

## Pre-Deployment Checks

- [ ] Ensure all controllers use the new `ImageUploadHelper` class
- [ ] Verify all views use asset() or url() helpers for image paths
- [ ] Run `test-public-directories.php` on local environment
- [ ] Check Laravel logs for any image path resolution errors

## Code Files to Deploy

- [ ] `app/Helpers/ImageUploadHelper.php` - Handles direct uploads to public directory
- [ ] `app/Services/ImagePathService.php` - Resolves image paths with improved logic
- [ ] `public/test-public-directories.php` - Creates and checks required directories
- [ ] `fix-image-paths.php` - Migrates existing images (if needed)

## Production Deployment Steps

1. **Backup First**
   - [ ] Backup the production database
   - [ ] Backup existing images in both `public/images` and `storage/app/public`

2. **Deploy Code Changes**
   - [ ] Push all code changes to production via Git
   - [ ] Run `composer install` if any dependencies were updated

3. **Directory Setup**
   - [ ] Visit `https://yoursite.com/test-public-directories.php` to create and check directories
   - [ ] Verify all image directories have 755 permissions (or 777 if needed)
   - [ ] Run `php artisan config:cache` to refresh Laravel's configuration

4. **Image Migration (if needed)**
   - [ ] Run `php fix-image-paths.php` if you need to migrate existing images
   - [ ] Check for any errors in the console output or Laravel logs

5. **Testing**
   - [ ] Test uploading new images in each section (events, hero sections, etc.)
   - [ ] Verify existing images display correctly
   - [ ] Check Laravel logs (`storage/logs/laravel.log`) for any image-related errors

## Rollback Plan (if issues occur)

1. **If 500 errors occur after deployment:**
   - [ ] Check Laravel logs for specific error messages
   - [ ] Verify directory permissions using `test-public-directories.php`
   - [ ] Restore backup if necessary

2. **If images are not displaying:**
   - [ ] Check browser console for 404 errors
   - [ ] Verify file paths in database match actual file locations
   - [ ] Use the ImagePathService debug logs to trace path resolution

## Maintenance Notes

- All new image uploads will go directly to `public/images/{section}` folders
- Image path resolution now checks multiple locations for backward compatibility
- The system will automatically fall back to storage-based uploads if direct public uploads fail
- Directory permissions must be maintained when server configurations change
