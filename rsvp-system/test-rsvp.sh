#!/bin/bash

# RSVP Testing Script
echo "🧪 Testing RSVP System..."

cd /www/wwwroot/fileserver.corenexa.it.com

# 1. Check if we can access the database
echo "📊 Checking database connection..."
php artisan tinker --execute="
try {
    echo 'Database connection: ' . (DB::connection()->getPdo() ? 'OK' : 'FAILED') . PHP_EOL;
    echo 'Events count: ' . App\Models\Event::count() . PHP_EOL;
    echo 'Guests count: ' . App\Models\Guest::count() . PHP_EOL;
    
    \$guest = App\Models\Guest::where('rsvp_status', 'pending')->first();
    if (\$guest) {
        echo 'Sample invitation URL: ' . \$guest->invitation_url . PHP_EOL;
        echo 'Guest status: ' . \$guest->rsvp_status . PHP_EOL;
        echo 'Has responded: ' . (\$guest->hasResponded() ? 'Yes' : 'No') . PHP_EOL;
        echo 'Invitation valid: ' . (\$guest->isInvitationValid() ? 'Yes' : 'No') . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Database error: ' . \$e->getMessage() . PHP_EOL;
}
"

# 2. Check routes
echo "🛤️  Checking routes..."
php artisan route:list | grep rsvp

# 3. Check logs for recent errors
echo "📝 Recent log entries..."
if [ -f "storage/logs/laravel.log" ]; then
    echo "Last 10 lines of log:"
    tail -10 storage/logs/laravel.log
else
    echo "No log file found"
fi

# 4. Check permissions
echo "🔐 Checking permissions..."
ls -la storage/framework/views/ | head -5
ls -la storage/logs/ | head -5

# 5. Clear caches
echo "🧹 Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear

echo "✅ Testing complete!"
echo ""
echo "📋 Next steps:"
echo "1. Check the debug info on the invitation page"
echo "2. Look at browser console for JavaScript errors"
echo "3. Check Laravel logs after clicking RSVP buttons"
echo "4. Verify CSRF token is being sent correctly"