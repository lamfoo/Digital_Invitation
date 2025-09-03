#!/bin/bash

# Complete Fix Script for RSVP System
echo "🔧 Fixing All RSVP System Issues..."

PROJECT_DIR="/www/wwwroot/fileserver.corenexa.it.com"

if [ ! -d "$PROJECT_DIR" ]; then
    echo "❌ Project directory not found: $PROJECT_DIR"
    exit 1
fi

cd "$PROJECT_DIR"

echo "📁 Working in: $(pwd)"

# 1. Fix ALL permissions issues
echo "🔐 Fixing all file permissions..."

# Create directories if they don't exist
sudo mkdir -p storage/logs
sudo mkdir -p storage/framework/cache
sudo mkdir -p storage/framework/sessions
sudo mkdir -p storage/framework/views
sudo mkdir -p bootstrap/cache

# Set proper permissions
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache
sudo chmod -R 644 storage/logs/*
sudo chmod 775 storage/logs

# Set ownership (try different common web server users)
if id "www-data" &>/dev/null; then
    sudo chown -R www-data:www-data storage bootstrap/cache
    echo "✅ Ownership set to www-data"
elif id "apache" &>/dev/null; then
    sudo chown -R apache:apache storage bootstrap/cache
    echo "✅ Ownership set to apache"
elif id "nginx" &>/dev/null; then
    sudo chown -R nginx:nginx storage bootstrap/cache
    echo "✅ Ownership set to nginx"
else
    # Fallback: give everyone write access
    sudo chmod -R 777 storage bootstrap/cache
    echo "⚠️  Set full permissions (777) - consider setting proper ownership later"
fi

# 2. Fix environment for production
echo "🔒 Configuring for HTTPS production..."

# Backup .env
cp .env .env.backup.$(date +%Y%m%d_%H%M%S)

# Update .env for production
sed -i 's|APP_ENV=.*|APP_ENV=production|g' .env
sed -i 's|APP_DEBUG=.*|APP_DEBUG=false|g' .env
sed -i 's|APP_URL=.*|APP_URL=https://fileserver.corenexa.it.com|g' .env

# Add missing environment variables
if ! grep -q "ASSET_URL" .env; then
    echo "ASSET_URL=https://fileserver.corenexa.it.com" >> .env
fi

if ! grep -q "FORCE_HTTPS" .env; then
    echo "FORCE_HTTPS=true" >> .env
fi

# 3. Clear ALL caches
echo "🧹 Clearing all caches..."
php artisan cache:clear 2>/dev/null || echo "Cache clear: skipped"
php artisan config:clear 2>/dev/null || echo "Config clear: skipped"
php artisan route:clear 2>/dev/null || echo "Route clear: skipped"
php artisan view:clear 2>/dev/null || echo "View clear: skipped"

# 4. Generate app key if needed
if ! grep -q "APP_KEY=base64:" .env; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# 5. Test database connection
echo "🗄️  Testing database..."
php artisan tinker --execute="
try {
    echo 'Database: ' . (DB::connection()->getPdo() ? 'Connected' : 'Failed') . PHP_EOL;
    echo 'Events: ' . App\Models\Event::count() . PHP_EOL;
    echo 'Guests: ' . App\Models\Guest::count() . PHP_EOL;
} catch (Exception \$e) {
    echo 'Database error: ' . \$e->getMessage() . PHP_EOL;
}
" 2>/dev/null || echo "Database test failed - check connection"

# 6. Rebuild optimizations
echo "⚡ Optimizing for production..."
php artisan config:cache 2>/dev/null || echo "Config cache: skipped"
php artisan route:cache 2>/dev/null || echo "Route cache: skipped"
php artisan view:cache 2>/dev/null || echo "View cache: skipped"

# 7. Test RSVP functionality
echo "🧪 Testing RSVP functionality..."
php artisan tinker --execute="
\$guest = App\Models\Guest::where('rsvp_status', 'pending')->first();
if (\$guest) {
    echo 'Test guest found: ' . \$guest->name . PHP_EOL;
    echo 'Token: ' . \$guest->unique_link_token . PHP_EOL;
    echo 'Invitation URL: ' . \$guest->invitation_url . PHP_EOL;
    echo 'Status: ' . \$guest->rsvp_status . PHP_EOL;
    echo 'Valid: ' . (\$guest->isInvitationValid() ? 'Yes' : 'No') . PHP_EOL;
} else {
    echo 'No pending guests found for testing' . PHP_EOL;
}
" 2>/dev/null || echo "RSVP test failed"

# 8. Final permission check
echo "🔍 Final permission verification..."
ls -la storage/logs/ | head -3
ls -la storage/framework/ | head -3

echo ""
echo "🎉 Fix Complete!"
echo ""
echo "📋 What was fixed:"
echo "✅ Storage permissions (775)"
echo "✅ Log directory permissions"
echo "✅ HTTPS configuration"
echo "✅ Cache clearing"
echo "✅ Production optimization"
echo ""
echo "🔗 Test your RSVP system now at:"
echo "   https://fileserver.corenexa.it.com"
echo ""
echo "🧪 To test RSVP:"
echo "1. Login to admin panel"
echo "2. Go to any event → Guests"
echo "3. Copy an invitation link"
echo "4. Open in new browser/incognito"
echo "5. Click RSVP buttons"
echo ""
echo "📝 If still having issues:"
echo "1. Check browser console (F12)"
echo "2. Try the debug version of invitation page"
echo "3. Verify web server configuration"