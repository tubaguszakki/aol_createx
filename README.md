# Createx - Room Booking System

A comprehensive room booking and management system built with Laravel, featuring real-time payment processing through Midtrans and Google OAuth integration.

## 🚀 Features

### User Features
- **User Authentication**
  - Email/Password registration and login
  - Google OAuth integration
  - Secure session management

- **Room Booking**
  - Browse available rooms by type
  - Real-time availability checking
  - Add-on services selection
  - Conflict detection for booking slots

- **Payment Integration**
  - Midtrans payment gateway integration
  - Multiple payment methods support
  - Real-time payment status updates
  - Automatic booking confirmation

- **Booking Management**
  - View booking history
  - Payment status tracking
  - Room PIN generation after payment
  - Booking status updates

### Admin Features
- **Dashboard Analytics**
  - Total bookings, users, and rooms overview
  - Revenue tracking (paid vs pending)
  - Recent bookings monitoring
  - Popular room statistics

- **Booking Management**
  - View all bookings with filtering
  - Bulk status updates
  - Payment status verification
  - PIN regeneration capabilities
 
## 📋 Requirements

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL/PostgreSQL
- Laravel 10.x

## 🛠 Installation

### 1. Clone Repository
```bash
git clone <repository-url>
cd room-booking-system
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install NPM dependencies
npm install
```

### 3. Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration
Edit `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=room_booking
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Midtrans Configuration
Add Midtrans configuration to `.env`:
```env
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

### 6. Google OAuth Configuration
Add Google OAuth credentials to `.env`:
```env
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URL="${APP_URL}/auth/google/callback"
```

### 7. Run Migrations and Seeders
```bash
# Run database migrations
php artisan migrate

# Run seeders (optional)
php artisan db:seed
```

### 8. Build Assets
```bash
# Compile assets
npm run build

# For development
npm run dev
```

### 9. Start Development Server
```bash
php artisan serve
```

## 📁 Project Structure

```
app/
├── Http/Controllers/
│   ├── AdminController.php      # Admin dashboard and management
│   ├── AuthController.php       # Authentication handling
│   ├── BookingController.php    # Booking and payment processing
│   ├── DashboardController.php  # User dashboard
│   └── HomeController.php       # Public pages
├── Models/
│   ├── User.php                 # User model
│   ├── Room.php                 # Room model
│   ├── Booking.php              # Booking model
│   ├── Addon.php                # Room addon model
│   └── BookingAddon.php         # Booking-addon relationship
└── ...

resources/
├── views/
│   ├── auth/                    # Authentication views
│   ├── admin/                   # Admin panel views
│   ├── dashboard/               # User dashboard views
│   ├── booking/                 # Booking process views
│   └── ...
└── ...

routes/
├── web.php                      # Web routes
└── api.php                      # API routes
```

## 🔗 API Endpoints

### Authentication
- `GET /login` - Show login form
- `POST /login` - Process login
- `GET /register` - Show registration form
- `POST /register` - Process registration
- `GET /auth/google` - Google OAuth redirect
- `GET /auth/google/callback` - Google OAuth callback
- `POST /logout` - Logout user

### User Dashboard
- `GET /dashboard` - User dashboard
- `POST /dashboard/booking` - Create new booking
- `GET /dashboard/bookings` - User's booking history
- `GET /dashboard/available-slots` - Check room availability

### Booking & Payment
- `POST /booking` - Create booking with payment
- `GET /booking/{booking}/pay` - Retry payment
- `GET /booking/{booking}/success` - Payment success page
- `GET /booking/{booking}/failed` - Payment failed page
- `GET /booking/{booking}/pending` - Payment pending page
- `POST /midtrans/notification` - Midtrans webhook

### Admin Panel
- `GET /admin/dashboard` - Admin dashboard
- `GET /admin/bookings` - Manage all bookings
- `PUT /admin/bookings/{booking}/status` - Update booking status
- `POST /admin/bookings/bulk-update` - Bulk status update
- `GET /admin/bookings/{booking}` - Booking details
- `DELETE /admin/bookings/{booking}` - Delete booking
- `POST /admin/bookings/{booking}/check-payment` - Check payment status
- `POST /admin/bookings/{booking}/regenerate-pin` - Regenerate PIN
- `GET /admin/export` - Export bookings to CSV
- `GET /admin/stats` - Admin statistics

## 🗄 Database Schema

### Core Tables
- **users** - User accounts and profiles
- **rooms** - Available rooms and their details
- **addons** - Additional services for rooms
- **bookings** - Booking records and status
- **booking_addons** - Many-to-many relationship for booking addons

### Key Relationships
- User has many Bookings
- Room has many Bookings
- Room has many Addons
- Booking belongs to User and Room
- Booking has many BookingAddons through Addons

## 💳 Payment Flow

1. **Booking Creation**
   - User selects room and time slot
   - System validates availability
   - Booking created with 'pending' status

2. **Payment Processing**
   - Midtrans Snap token generated
   - User redirected to payment page
   - Payment processed through Midtrans

3. **Payment Confirmation**
   - Midtrans sends webhook notification
   - System updates booking status
   - PIN generated for successful payments

4. **Booking Completion**
   - User receives booking confirmation
   - Admin can monitor through dashboard

## 🔧 Configuration

### Midtrans Setup
1. Register at [Midtrans](https://midtrans.com)
2. Get Server Key and Client Key
3. Configure webhook URL: `{your-domain}/midtrans/notification`
4. Set environment variables in `.env`

### Google OAuth Setup
1. Create project in [Google Cloud Console](https://console.cloud.google.com)
2. Enable Google+ API
3. Create OAuth 2.0 credentials
4. Set authorized redirect URI: `{your-domain}/auth/google/callback`
5. Configure environment variables

## 🛡 Security Features

- **Authentication & Authorization**
  - Role-based access control (Admin/User)
  - Session management
  - CSRF protection

- **Data Validation**
  - Server-side input validation
  - SQL injection prevention
  - XSS protection

- **Payment Security**
  - Secure Midtrans integration
  - Transaction verification
  - Payment status synchronization

## 🧪 Testing

### Running Tests
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

### Test Categories
- **Unit Tests** - Model and service logic
- **Feature Tests** - Controller and integration tests
- **Browser Tests** - End-to-end user flows

## 📊 Monitoring & Logging

### Application Logs
- Payment processing logs
- Midtrans webhook responses
- Authentication attempts
- Booking creation and updates

### Log Files Location
```
storage/logs/laravel.log
```

## 🚀 Deployment

### Production Setup
1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false`
3. Configure production database
4. Set Midtrans to production mode
5. Configure SSL certificate
6. Set up queue workers for background jobs

### Queue Configuration
```bash
# Start queue worker
php artisan queue:work

# For production (supervisor recommended)
php artisan queue:work --daemon
```

## 📝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

## 🆘 Support

For support and questions:
- Create an issue in the repository
- Contact the development team
- Check the documentation

## 🔄 Changelog

### Version 1.0.0
- Initial release
- Basic room booking functionality
- Midtrans payment integration
- Google OAuth authentication
- Admin dashboard
- Export functionality

---

**Built with ❤️ using Laravel Framework**
