# RSVP System

A complete digital invitation system with RSVP functionality built with Laravel 10.x, PHP 8.x, and MySQL 8.x.

## Features

- **Event Management**: Create, edit, and delete events with full details
- **Guest Management**: Add guests manually or import from CSV
- **Personalized Invitations**: Unique secure links for each guest
- **RSVP Tracking**: Real-time tracking of responses (Yes/No/Maybe)
- **Invitation Expiration**: Configurable RSVP deadlines
- **Admin Dashboard**: Complete management interface
- **CSV Export**: Export RSVP lists for analysis
- **Responsive Design**: Beautiful, modern UI inspired by PhotoADKing and Canva
- **Security**: CSRF protection, input validation, secure tokens

## Requirements

- PHP 8.2 or higher
- Composer
- MySQL 8.0 or SQLite
- Node.js & NPM

## Installation

1. **Clone and setup the project:**
   ```bash
   cd /workspace/rsvp-system
   composer install
   npm install
   ```

2. **Configure environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Setup database:**
   - For SQLite (default): The database file is already created
   - For MySQL: Update `.env` with your MySQL credentials:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=rsvp_system
     DB_USERNAME=your_username
     DB_PASSWORD=your_password
     ```

4. **Run migrations and seed data:**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Build assets:**
   ```bash
   npm run build
   ```

6. **Start the server:**
   ```bash
   php artisan serve
   ```

## Usage

### Admin Access

1. **Login**: Visit `/login` and use:
   - Email: `admin@rsvp.com`
   - Password: `password` (default Laravel password)

2. **Create Events**: Navigate to Events → Create Event

3. **Manage Guests**: 
   - Add guests manually
   - Import from CSV (format: `name` column header)
   - Copy invitation links

4. **Track RSVPs**: View real-time responses and export CSV reports

### Guest Experience

Guests receive unique invitation links like `/invite/{token}` that display:
- Personalized invitation with their name
- Event details (date, time, location, description)
- RSVP buttons (Yes/No/Maybe)
- Expiration handling
- One-time response enforcement

## File Structure

```
app/
├── Http/Controllers/
│   ├── Admin/
│   │   ├── EventController.php     # Event CRUD operations
│   │   └── GuestController.php     # Guest management & CSV
│   └── RsvpController.php          # Public RSVP functionality
├── Models/
│   ├── Event.php                   # Event model with relationships
│   └── Guest.php                   # Guest model with UUID generation
database/
├── migrations/
│   ├── *_create_events_table.php   # Events table schema
│   └── *_create_guests_table.php   # Guests table with foreign keys
└── seeders/
    └── DatabaseSeeder.php          # Sample data
resources/
├── views/
│   ├── admin/                      # Admin panel templates
│   │   ├── events/                 # Event management views
│   │   └── guests/                 # Guest & RSVP management
│   ├── rsvp/                       # Public invitation views
│   └── layouts/                    # Layout templates
└── css/app.css                     # Custom styles with Bootstrap
```

## Sample CSV Format

Create a CSV file with the following format for bulk guest import:

```csv
name
John Doe
Jane Smith
Bob Johnson
Alice Williams
```

## Testing Features

1. **Create an Event**: Test event creation with validation
2. **Add Guests**: Test manual addition and CSV import
3. **Test Invitations**: Copy invitation links and test RSVP flow
4. **Test Expiration**: Create an event with past expiration date
5. **Export Data**: Test CSV export functionality

## Security Features

- CSRF protection on all forms
- Input validation and sanitization
- Secure UUID tokens for invitations
- Authentication middleware for admin routes
- SQL injection prevention via Eloquent ORM
- XSS prevention via Blade templating

## Database Schema

### Events Table
- `id`: Primary key
- `title`: Event name
- `location`: Event venue
- `event_date`: Date of event
- `event_time`: Time of event
- `description`: Event description (optional)
- `rsvp_expiration_at`: RSVP deadline
- `created_at`, `updated_at`: Timestamps

### Guests Table
- `id`: Primary key
- `event_id`: Foreign key to events
- `name`: Guest name
- `unique_link_token`: UUID for invitation link
- `rsvp_status`: Enum (pending, yes, no, maybe)
- `rsvp_confirmed_at`: Response timestamp
- `created_at`, `updated_at`: Timestamps

## Customization

The system uses Bootstrap 5 with custom CSS variables for easy theming. Edit `/resources/css/app.css` to customize colors and styles.

Key CSS variables:
- `--primary-color`: Main brand color
- `--secondary-color`: Secondary brand color
- `--success-color`, `--danger-color`, etc.: Status colors

## Production Deployment

1. Set `APP_ENV=production` in `.env`
2. Configure proper database credentials
3. Set up web server (Apache/Nginx) to point to `/public`
4. Run `php artisan config:cache` and `php artisan route:cache`
5. Ensure proper file permissions for storage and cache directories