# RSVP System - Complete Setup Instructions

## Quick Start

The system is ready to run! Follow these steps:

### 1. Access the Application

The Laravel development server should be running on `http://localhost:8000`

### 2. Login to Admin Panel

- **URL**: `http://localhost:8000/login`
- **Email**: `admin@rsvp.com`
- **Password**: `password`

### 3. Test the System

#### A. Admin Panel Testing
1. **View Events**: After login, you'll see the events dashboard with 3 sample events
2. **Create New Event**: Click "Create Event" and fill in the details
3. **Manage Guests**: Click on any event to manage guests
4. **Import CSV**: Use the provided `sample-guests.csv` file to test bulk import
5. **View RSVPs**: Check RSVP responses and export CSV reports

#### B. Guest Invitation Testing
1. **Get Invitation Link**: In the admin panel, go to an event's guest list
2. **Copy Link**: Copy any guest's invitation link
3. **Test RSVP**: Open the link in a new browser window/incognito mode
4. **Submit Response**: Choose Yes/No/Maybe and submit
5. **Verify**: Return to admin panel to see the response recorded

### 4. Key Features to Test

#### Event Management
- ✅ Create events with all required fields
- ✅ Edit existing events
- ✅ Delete events (cascades to guests)
- ✅ View event statistics

#### Guest Management
- ✅ Add guests manually
- ✅ Import guests from CSV
- ✅ Edit guest names
- ✅ Remove guests
- ✅ Copy invitation links

#### RSVP Functionality
- ✅ Unique invitation URLs
- ✅ Personalized invitation pages
- ✅ One-time RSVP submission
- ✅ Expiration handling
- ✅ Response tracking

#### Data Export
- ✅ CSV export with guest names, status, timestamps
- ✅ Filtered views (Confirmed, Declined, Maybe, Pending)

### 5. Sample Data

The system comes with 3 pre-created events:

1. **John & Sarah's Wedding** (30 days from now)
   - 8 guests with mixed responses
   - Wedding theme styling

2. **Emma's 30th Birthday Bash** (15 days from now)
   - 6 guests with some responses
   - Party theme styling

3. **Annual Company Retreat** (45 days from now)
   - 10 guests, all pending responses
   - Corporate theme styling

### 6. Testing Scenarios

#### Scenario 1: Complete Event Workflow
1. Login to admin panel
2. Create a new event
3. Add guests (both manually and via CSV)
4. Copy invitation links
5. Test RSVP submissions
6. View responses in admin panel
7. Export CSV report

#### Scenario 2: Expiration Testing
1. Create an event with expiration date in the past
2. Try to access guest invitation link
3. Verify expiration message appears

#### Scenario 3: One-time Response Testing
1. Submit an RSVP response
2. Try to access the same link again
3. Verify "already responded" message

### 7. Customization

#### Styling
- Edit `/resources/css/app.css` for custom colors
- Modify CSS variables in `:root` section
- Run `npm run build` after changes

#### Email Integration (Optional)
To add email sending capability:
1. Configure mail settings in `.env`
2. Add email field to guests table migration
3. Create mail templates
4. Send invitation emails automatically

### 8. Production Deployment

#### Environment Setup
```bash
# Set production environment
APP_ENV=production
APP_DEBUG=false

# Configure database
DB_CONNECTION=mysql
DB_HOST=your-host
DB_DATABASE=your-database
DB_USERNAME=your-username
DB_PASSWORD=your-password

# Set application URL
APP_URL=https://your-domain.com
```

#### Optimization Commands
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 9. File Permissions (Production)
```bash
chmod -R 755 /path/to/your/project
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data /path/to/your/project
```

### 10. Web Server Configuration

#### Apache (.htaccess)
The Laravel project includes proper `.htaccess` files.

#### Nginx
Point document root to `/public` directory and configure URL rewriting.

## Support

The system is built with Laravel best practices and includes:
- Comprehensive error handling
- Input validation
- CSRF protection
- Responsive design
- Scalable architecture

For customization or issues, refer to the Laravel documentation or modify the controllers/views as needed.