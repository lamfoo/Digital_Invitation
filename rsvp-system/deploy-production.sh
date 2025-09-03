#!/bin/bash

# Production Deployment Script for RSVP System
# Fixes permissions, HTTPS, and optimizes for production

echo "🚀 Deploying RSVP System to Production..."

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: Run this script from your Laravel project root"
    exit 1
fi

PROJECT_DIR=$(pwd)
echo "📁 Project directory: $PROJECT_DIR"

# 1. Fix Permissions
echo "🔧 Fixing file permissions..."
sudo chmod -R 755 .
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache

# 2. Setup Environment for HTTPS
echo "🔒 Configuring HTTPS..."

# Backup original .env
cp .env .env.backup

# Update .env for HTTPS
sed -i 's|APP_URL=.*|APP_URL=https://fileserver.corenexa.it.com|g' .env
sed -i 's|APP_ENV=.*|APP_ENV=production|g' .env
sed -i 's|APP_DEBUG=.*|APP_DEBUG=false|g' .env

# Add ASSET_URL if not present
if ! grep -q "ASSET_URL" .env; then
    echo "ASSET_URL=https://fileserver.corenexa.it.com" >> .env
fi

# Add FORCE_HTTPS if not present
if ! grep -q "FORCE_HTTPS" .env; then
    echo "FORCE_HTTPS=true" >> .env
fi

echo "✅ Environment configured for HTTPS"

# 3. Clear all caches
echo "🧹 Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 4. Generate app key if needed
if ! grep -q "APP_KEY=base64:" .env; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# 5. Run migrations
echo "🗄️  Running migrations..."
php artisan migrate --force

# 6. Optimize for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# 7. Build assets
if [ -f "package.json" ]; then
    echo "🎨 Building assets..."
    npm install --production
    npm run build
fi

# 8. Final permission check
echo "🔍 Final permission verification..."
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache

echo ""
echo "🎉 Deployment Complete!"
echo ""
echo "📋 Verification Checklist:"
echo "✅ File permissions fixed"
echo "✅ HTTPS configuration applied"
echo "✅ Caches optimized"
echo "✅ Assets built"
echo ""
echo "🔗 Your RSVP system should now work at:"
echo "   https://fileserver.corenexa.it.com"
echo ""
echo "👤 Admin Login:"
echo "   Email: admin@rsvp.com"
echo "   Password: password"
echo ""
echo "🔧 If you still see mixed content errors:"
echo "1. Check that your web server is properly configured for HTTPS"
echo "2. Verify SSL certificates are installed"
echo "3. Clear browser cache and try incognito mode"