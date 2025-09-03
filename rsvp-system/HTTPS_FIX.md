# HTTPS Mixed Content Fix

## 🔒 Issue: Mixed Content Errors

You're seeing these errors because:
- Your site runs on HTTPS (`https://fileserver.corenexa.it.com`)
- Laravel is generating HTTP URLs for assets
- Browsers block HTTP content on HTTPS pages for security

## 🚀 Complete Solution

### Step 1: Update .env File

Add/update these settings in your `.env` file:

```env
# Force HTTPS
APP_URL=https://fileserver.corenexa.it.com
ASSET_URL=https://fileserver.corenexa.it.com

# Force HTTPS in Laravel
FORCE_HTTPS=true
```

### Step 2: Create HTTPS Middleware

Create a new middleware to force HTTPS:

```bash
cd /www/wwwroot/fileserver.corenexa.it.com
php artisan make:middleware ForceHttps
```

### Step 3: Configure AppServiceProvider

Update `app/Providers/AppServiceProvider.php`:

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Force HTTPS in production
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
        
        // Or always force HTTPS
        // URL::forceScheme('https');
    }
}
```

### Step 4: Update Kernel.php (Optional)

Add HTTPS middleware to `app/Http/Kernel.php`:

```php
protected $middleware = [
    // ... other middleware
    \App\Http\Middleware\ForceHttps::class,
];
```

### Step 5: Quick Fix Commands

Run these commands in your project directory:

```bash
cd /www/wwwroot/fileserver.corenexa.it.com

# Update .env
echo "APP_URL=https://fileserver.corenexa.it.com" >> .env
echo "ASSET_URL=https://fileserver.corenexa.it.com" >> .env
echo "FORCE_HTTPS=true" >> .env

# Clear and rebuild caches
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

## 🎯 Alternative Quick Solutions

### Option 1: Force HTTPS in .htaccess (Apache)

Add to `public/.htaccess` (before Laravel rules):

```apache
# Force HTTPS
RewriteEngine On
RewriteCond %{HTTPS} !=on
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### Option 2: Nginx HTTPS Redirect

Add to your Nginx config:

```nginx
server {
    listen 80;
    server_name fileserver.corenexa.it.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl;
    server_name fileserver.corenexa.it.com;
    
    # Your SSL certificates
    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;
    
    # Rest of your Laravel configuration...
}
```

### Option 3: Cloudflare/Proxy Fix

If you're using Cloudflare or a proxy:

```php
// Add to AppServiceProvider boot() method
if (request()->header('CF-Visitor')) {
    URL::forceScheme('https');
}

// Or for general proxy setups
if (request()->header('X-Forwarded-Proto') === 'https') {
    URL::forceScheme('https');
}
```

## 📝 Files to Update

### 1. Update .env
```env
APP_URL=https://fileserver.corenexa.it.com
ASSET_URL=https://fileserver.corenexa.it.com
APP_ENV=production
APP_DEBUG=false
```

### 2. Update AppServiceProvider.php
```php
public function boot(): void
{
    if ($this->app->environment('production')) {
        URL::forceScheme('https');
    }
}
```

### 3. Clear Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan config:cache
```

## 🔍 Verification

After applying the fix:

1. **Check URLs**: Visit your site and inspect source - all URLs should be HTTPS
2. **Test Login**: The password field warning should disappear
3. **Check Assets**: CSS/JS should load without mixed content errors
4. **Browser Console**: No more mixed content warnings

## 🛡️ Security Benefits

This fix ensures:
- ✅ All content loaded over secure HTTPS
- ✅ Login forms are secure
- ✅ No mixed content vulnerabilities
- ✅ Better SEO and user trust
- ✅ Compliance with modern security standards

The quickest solution is updating your `.env` file with the HTTPS URLs and clearing the config cache!