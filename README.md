# Sisters Station - Baby Wear Marketplace

A comprehensive multi-vendor e-commerce marketplace built with Laravel, specializing in baby wear and children's clothing.

## Features

### 🏪 Multi-Vendor Marketplace
- Independent seller registration and verification
- Seller dashboard with product management
- Analytics and sales reporting
- Commission-based revenue model

### 🛍️ E-commerce Functionality
- Responsive product catalog with advanced filtering
- Shopping cart and wishlist functionality
- Secure checkout with multiple payment methods
- Order management and tracking
- Customer reviews and ratings

### 👥 User Management
- Customer, Seller, and Admin roles
- User authentication and authorization
- Profile management
- Address book for shipping/billing

### 📊 Admin Panel
- Complete marketplace management
- User and seller verification
- Product approval system
- Order monitoring
- Analytics and reporting

### 🎯 Marketing Features
- Coupon and discount system
- Featured products and sellers
- Search and recommendations
- Email notifications

## Tech Stack

- **Backend**: Laravel 10
- **Frontend**: Bootstrap 5, Blade Templates
- **Database**: MySQL
- **Authentication**: Laravel Sanctum + Spatie Permissions
- **File Storage**: Local/Cloud Storage
- **Payment**: Stripe, PayPal Integration Ready

## Installation

### Prerequisites
- PHP 8.1+
- Composer
- MySQL/MariaDB
- Node.js (for asset compilation)

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd babywear
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database**
   ```bash
   # Edit .env file with your database credentials
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sisters_station
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Compile assets**
   ```bash
   npm run build
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

## Default Login Credentials

### Admin Account
- **Email**: admin@sistersstation.com
- **Password**: password

### Sample Sellers
- **Email**: babybliss@example.com
- **Password**: password

- **Email**: tinytreasures@example.com
- **Password**: password

- **Email**: littlewonders@example.com
- **Password**: password

## Directory Structure

```
├── app/
│   ├── Http/Controllers/          # Application controllers
│   ├── Models/                    # Eloquent models
│   ├── Helpers/                   # Custom helper classes
│   └── Providers/                 # Service providers
├── database/
│   ├── migrations/                # Database migrations
│   └── seeders/                   # Database seeders
├── resources/
│   ├── views/                     # Blade templates
│   │   ├── layouts/               # Layout templates
│   │   ├── auth/                  # Authentication views
│   │   ├── products/              # Product-related views
│   │   ├── seller/                # Seller dashboard views
│   │   ├── admin/                 # Admin panel views
│   │   └── checkout/              # Checkout process views
│   └── js/                        # JavaScript files
├── routes/                        # Route definitions
└── storage/                       # File storage
```

## Key Features Implementation

### Authentication & Authorization
- Role-based access control using Spatie Laravel Permission
- Email verification for user registration
- Password reset functionality
- Secure session management

### Product Management
- Multi-category product organization
- Product variants (size, color options)
- Image gallery with primary image selection
- Inventory tracking and management
- SEO-friendly URLs and meta tags

### Shopping Cart & Checkout
- Session-based cart for guests
- User cart persistence after login
- Multiple payment method support
- Address management
- Coupon/discount system
- Order confirmation emails

### Seller Dashboard
- Product CRUD operations
- Order fulfillment management
- Sales analytics and reporting
- Store profile customization
- Commission tracking

### Admin Panel
- User management and roles
- Seller verification workflow
- Product approval system
- Order monitoring
- System analytics
- Settings management

## API Endpoints

The marketplace includes RESTful API endpoints for:
- Product catalog
- Shopping cart operations
- User authentication
- Order management
- Search and filtering

## Security Features

- CSRF protection
- Input validation and sanitization
- SQL injection prevention
- XSS protection
- Secure file uploads
- Rate limiting
- Password hashing

## Performance Optimizations

- Database query optimization
- Image lazy loading
- Caching strategies
- Asset minification
- Pagination for large datasets

## Payment Integration

The system is ready for integration with:
- Stripe (credit/debit cards)
- PayPal
- Apple Pay
- Google Pay

## Shipping Integration

Support for multiple shipping options:
- Standard shipping
- Express delivery
- Local pickup
- Real-time tracking integration ready

## Future Enhancements

- Mobile app development (React Native/Flutter)
- Advanced AI-powered recommendations
- Multi-language support
- Advanced analytics dashboard
- Marketing automation
- Affiliate program
- Subscription services

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests
5. Submit a pull request

## Support

For support and questions:
- Email: info@sistersstation.com
- Documentation: [Link to documentation]
- Issues: [Link to issue tracker]

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Credits

Built with ❤️ using Laravel and modern web technologies.
