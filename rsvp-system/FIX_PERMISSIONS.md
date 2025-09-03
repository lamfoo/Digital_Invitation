# Laravel Permission Fix Guide

## 🚨 Permission Error Solution

The error you're seeing is a common Laravel deployment issue where the web server cannot write to storage directories.

### Quick Fix Commands

Run these commands in your Laravel project root directory (`/www/wwwroot/fileserver.corenexa.it.com/`):

```bash
# Navigate to your project directory
cd /www/wwwroot/fileserver.corenexa.it.com

# Fix storage and bootstrap/cache permissions
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache

# Set proper ownership (replace 'www-data' with your web server user)
sudo chown -R www-data:www-data storage
sudo chown -R www-data:www-data bootstrap/cache

# Alternative: If you don't know the web server user, try these:
# For Apache: www-data, apache, or httpd
# For Nginx: nginx, www-data
# For shared hosting: your username

# If the above doesn't work, try broader permissions (less secure):
sudo chmod -R 777 storage
sudo chmod -R 777 bootstrap/cache
```

### Complete Permission Setup

```bash
# Set general permissions
sudo find /www/wwwroot/fileserver.corenexa.it.com -type f -exec chmod 644 {} \;
sudo find /www/wwwroot/fileserver.corenexa.it.com -type d -exec chmod 755 {} \;

# Set specific Laravel permissions
sudo chmod -R 775 /www/wwwroot/fileserver.corenexa.it.com/storage
sudo chmod -R 775 /www/wwwroot/fileserver.corenexa.it.com/bootstrap/cache

# Set ownership (adjust user:group as needed)
sudo chown -R www-data:www-data /www/wwwroot/fileserver.corenexa.it.com
```

### Alternative Solutions

#### Option 1: Create Missing Directories
```bash
cd /www/wwwroot/fileserver.corenexa.it.com

# Create storage directories if they don't exist
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set permissions
chmod -R 775 storage bootstrap/cache
```

#### Option 2: Clear and Rebuild Cache
```bash
cd /www/wwwroot/fileserver.corenexa.it.com

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Set permissions again
chmod -R 775 storage bootstrap/cache
```

#### Option 3: SELinux Fix (if applicable)
```bash
# If you're on CentOS/RHEL with SELinux enabled
sudo setsebool -P httpd_can_network_connect 1
sudo chcon -R -t httpd_exec_t /www/wwwroot/fileserver.corenexa.it.com/
sudo chcon -R -t httpd_rw_content_t /www/wwwroot/fileserver.corenexa.it.com/storage/
sudo chcon -R -t httpd_rw_content_t /www/wwwroot/fileserver.corenexa.it.com/bootstrap/cache/
```

### Web Server Configuration

#### Apache Virtual Host
```apache
<VirtualHost *:80>
    ServerName fileserver.corenexa.it.com
    DocumentRoot /www/wwwroot/fileserver.corenexa.it.com/public
    
    <Directory /www/wwwroot/fileserver.corenexa.it.com/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/rsvp_error.log
    CustomLog ${APACHE_LOG_DIR}/rsvp_access.log combined
</VirtualHost>
```

#### Nginx Configuration
```nginx
server {
    listen 80;
    server_name fileserver.corenexa.it.com;
    root /www/wwwroot/fileserver.corenexa.it.com/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Environment Configuration

Make sure your `.env` file is properly configured:

```env
APP_NAME="RSVP System"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=http://fileserver.corenexa.it.com

# Database (adjust as needed)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rsvp_system
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# Cache & Sessions
CACHE_STORE=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

### Post-Fix Steps

After fixing permissions:

1. **Clear all caches:**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

2. **Optimize for production:**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Test the application:**
   - Visit your domain
   - Try logging in with `admin@rsvp.com` / `password`
   - Create a test event
   - Test invitation links

### Common Issues & Solutions

#### Issue: "Class not found" errors
```bash
composer dump-autoload
php artisan optimize:clear
```

#### Issue: "Key not set" error
```bash
php artisan key:generate
```

#### Issue: Database connection errors
- Check database credentials in `.env`
- Ensure database exists
- Test connection: `php artisan tinker` then `DB::connection()->getPdo();`

#### Issue: Assets not loading
```bash
npm install
npm run build
```

### Security Checklist for Production

- ✅ Set `APP_DEBUG=false`
- ✅ Set `APP_ENV=production`
- ✅ Use strong database passwords
- ✅ Ensure HTTPS is configured
- ✅ Set proper file permissions (755/644)
- ✅ Disable directory listing
- ✅ Configure proper error logging

The most likely solution is running the permission commands above. Try the first set of commands and the error should be resolved!