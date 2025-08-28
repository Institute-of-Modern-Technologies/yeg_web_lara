# Production Image Fix Guide

## Problem Identification
Your production server is still storing images in `partner-schools/` or using Laravel storage paths, while your local environment correctly uses `images/partner-schools/`.

## Steps to Fix Production

### 1. Deploy Updated Controller and Helper Code
Make sure these files are in production:
- `app/Helpers/ImageUploadHelper.php`
- `app/Http/Controllers/PartnerSchoolController.php`

### 2. Run Fix Scripts on Production
Execute these scripts in order:

```bash
# Fix 1: Main fix for partner school images
php fix_production_partner_schools_final.php

# Fix 2: Create default images for any missing images
php fix_missing_partner_images.php
```

### 3. Ensure Directory Permissions
The public images directory must be writable:

```bash
chmod -R 755 public/images
```

### 4. Clear Laravel Caches
```bash
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan cache:clear
```

## Testing After Deployment
1. Try to upload a new partner school image
2. Check that new images are being stored in `public/images/partner-schools/`
3. Verify image paths in database show `images/partner-schools/filename.jpg`

## Preventing Future Issues
1. Always use `ImageUploadHelper::uploadImageToPublic()` for uploads
2. Never manually create paths with `storage/` references
3. Use relative paths starting with `images/` in database

This should ensure your production environment uses the same direct public path approach as your local environment.
