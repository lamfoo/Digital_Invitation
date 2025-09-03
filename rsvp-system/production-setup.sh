#!/bin/bash

# Laravel Production Setup Script
# Run this script in your Laravel project root directory

echo "🚀 Setting up Laravel RSVP System for Production..."

# Get the project directory
PROJECT_DIR=$(pwd)
echo "Project directory: $PROJECT_DIR"

# Check if we're in a Laravel project
if [ ! -f "artisan" ]; then
    echo "❌ Error: This doesn't appear to be a Laravel project root directory."
    echo "Please run this script from your Laravel project root."
    exit 1
fi

echo "✅ Laravel project detected"

# Create storage directories if they don't exist
echo "📁 Creating storage directories..."
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

echo "✅ Storage directories created"

# Fix permissions
echo "🔧 Fixing permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache

echo "✅ Permissions set"

# Try to determine web server user
WEB_USER=""
if id "www-data" &>/dev/null; then
    WEB_USER="www-data"
elif id "apache" &>/dev/null; then
    WEB_USER="apache"
elif id "nginx" &>/dev/null; then
    WEB_USER="nginx"
elif id "httpd" &>/dev/null; then
    WEB_USER="httpd"
fi

if [ ! -z "$WEB_USER" ]; then
    echo "🔧 Setting ownership to $WEB_USER..."
    chown -R $WEB_USER:$WEB_USER storage
    chown -R $WEB_USER:$WEB_USER bootstrap/cache
    echo "✅ Ownership set to $WEB_USER"
else
    echo "⚠️  Could not determine web server user. You may need to set ownership manually."
    echo "Common users: www-data, apache, nginx, httpd"
    echo "Example: sudo chown -R www-data:www-data storage bootstrap/cache"
fi

# Clear all caches
echo "🧹 Clearing caches..."
php artisan cache:clear 2>/dev/null || echo "Cache clear skipped (cache not found)"
php artisan config:clear 2>/dev/null || echo "Config clear skipped"
php artisan route:clear 2>/dev/null || echo "Route clear skipped"
php artisan view:clear 2>/dev/null || echo "View clear skipped"

echo "✅ Caches cleared"

# Generate app key if not set
if ! grep -q "APP_KEY=base64:" .env; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
    echo "✅ Application key generated"
fi

# Run migrations if needed
echo "🗄️  Running database migrations..."
php artisan migrate --force
echo "✅ Migrations completed"

# Optimize for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

echo "✅ Optimization completed"

# Final permission check
echo "🔍 Final permission check..."
ls -la storage/framework/
ls -la bootstrap/cache/

echo "🎉 Setup complete!"
echo ""
echo "📋 Next steps:"
echo "1. Ensure your web server points to the 'public' directory"
echo "2. Configure your .env file with proper database credentials"
echo "3. Test the application by visiting your domain"
echo "4. Login with: admin@rsvp.com / password"
echo ""
echo "🔗 Your RSVP system should now be accessible!"