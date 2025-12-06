# PixelParts 🎮

> **Professional E-commerce Platform for Gaming PC Components**

A modern, full-stack web application built with Laravel 11 and Tailwind CSS, featuring advanced product management, secure payments, AI-powered customer support, and a comprehensive admin dashboard.

[![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.0-38B2AC?style=flat&logo=tailwind-css)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

---

## ✨ Features

### 🛒 Customer Experience
- **Advanced Product Catalog** - Filter by categories, brands with real-time search
- **Dynamic Pricing** - Percentage-based discount system with visual indicators
- **Smart Shopping Cart** - Persistent sessions with automatic recovery
- **Bundle Deals** - Package offers with automatic savings calculation
- **Product Reviews** - User ratings and feedback system
- **Secure Checkout** - Stripe payment integration
- **AI Assistant** - n8n-powered chatbot for instant support
- **Responsive Design** - Seamless experience across all devices

### 🎛️ Admin Dashboard
- **Real-time Analytics** - Orders, revenue, and inventory metrics
- **Product Management** - Multi-page modal with dynamic fields
- **User Administration** - Customer account management
- **Order Processing** - Status tracking and updates
- **Excel Exports** - Professional stock reports
- **Bulk Operations** - Mass editing with smart filtering
- **Role-based Access** - Secure admin middleware

### 🔐 Security & Performance
- CSRF & XSS protection
- SQL injection prevention (Eloquent ORM)
- Password hashing (bcrypt)
- OAuth 2.0 social login (Google)
- Optimized database queries
- Asset minification & caching
- Lazy loading & pagination

---

## 🚀 Quick Start

### Prerequisites

- **PHP** 8.2 or higher
- **Composer** 2.x
- **Node.js** 18+ & npm
- **MySQL** 8.0+
- **Git**

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/pixelparts.git
   cd pixelparts
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   
   Create a MySQL database and update your `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=
   DB_USERNAME=
   DB_PASSWORD=
   ```

5. **Run migrations**
   ```bash
   php artisan migrate
   ```

6. **Build frontend assets**
   ```bash
   npm run dev
   ```

7. **Start development server**
   ```bash
   php artisan serve
   ```

Visit **http://localhost:8000** to see your application!

---

## ⚙️ Configuration

### Payment Gateway (Stripe)

Add your Stripe credentials to `.env`:
```env
STRIPE_KEY=pk_test_your_publishable_key
STRIPE_SECRET=sk_test_your_secret_key
```

### Social Authentication (Google OAuth)

1. Create OAuth credentials in [Google Cloud Console](https://console.cloud.google.com)
2. Add to `.env`:
   ```env
   GOOGLE_CLIENT_ID=your_client_id
   GOOGLE_CLIENT_SECRET=your_client_secret
   GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
   ```

### AI Chatbot (n8n - Optional)

1. Set up your n8n workflow with a webhook
2. Update chatbot endpoint in `resources/views/layouts/master.blade.php`:
   ```html
   <div id="n8n-chat" data-endpoint="https://your-n8n-instance.com/webhook/your-id"
   ```

### Email Configuration

For cart recovery and notifications:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@pixelparts.com
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 👤 Admin Access

Create an admin user via Tinker:

```bash
php artisan tinker
```

```php
App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@pixelparts.com',
    'password' => bcrypt('your-secure-password'),
    'role' => 'admin',
    'email_verified_at' => now(),
]);
```

**Backoffice URL:** `http://localhost:8000/backoffice`

---

## 📂 Project Structure

```
pixelparts/
├── app/
│   ├── Exports/             # Excel export handlers
│   ├── Http/
│   │   ├── Controllers/     # Request handlers
│   │   └── Middleware/      # Custom middleware
│   └── Models/              # Eloquent models
├── database/
│   ├── migrations/          # Database schema
│   └── seeders/             # Sample data
├── public/
│   ├── css/                 # Compiled styles
│   ├── js/                  # Frontend scripts
│   └── images/              # Static assets
├── resources/
│   ├── css/                 # Source styles
│   └── views/               # Blade templates
└── routes/
    └── web.php              # Route definitions
```

---

## 🎨 Key Technologies

| Category | Technology |
|----------|-----------|
| **Backend** | Laravel 11, PHP 8.2+ |
| **Frontend** | Tailwind CSS, Vanilla JS (ES6+) |
| **Database** | MySQL 8.0 |
| **Authentication** | Laravel Fortify, Socialite |
| **Payments** | Stripe API |
| **Icons** | Lucide Icons |
| **Build Tool** | Vite |
| **Exports** | PhpSpreadsheet |
| **Chatbot** | n8n Workflow |

---

## 🔌 API Routes

### Public Endpoints
```
GET  /                    # Homepage
GET  /produtos            # Product listing
GET  /produtos/{slug}     # Product details
GET  /sobre               # About page
```

### Authenticated Endpoints
```
GET   /cart                          # Shopping cart
POST  /cart/add                      # Add to cart
POST  /order                         # Place order
POST  /products/{product}/reviews    # Submit review
GET   /profile                       # User profile
```

### Admin Endpoints (Role: admin)
```
GET   /backoffice              # Dashboard
GET   /backoffice/stock        # Product management
GET   /backoffice/users        # User management
GET   /backoffice/orders       # Order management
GET   /backoffice/stock/excel  # Export stock to Excel
```

---

## 🚢 Deployment

### Production Optimization

```bash
# Install production dependencies
composer install --optimize-autoloader --no-dev

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build assets
npm run build
```

### Environment Variables

Set production environment in `.env`:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

### File Permissions

```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Web Server Configuration

Point your web server's document root to the `/public` directory.

**Nginx Example:**
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/pixelparts/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 🧪 Testing

Run the test suite:
```bash
php artisan test
```

---

## 🤝 Contributing

Contributions are welcome! Here's how you can help:

1. **Fork** the repository
2. **Create** a feature branch (`git checkout -b feature/amazing-feature`)
3. **Commit** your changes (`git commit -m 'Add amazing feature'`)
4. **Push** to the branch (`git push origin feature/amazing-feature`)
5. **Open** a Pull Request

Please ensure your code follows PSR-12 coding standards.

---

## 📄 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

---

## 👨‍💻 Author

**Gonçalo**  
[GitHub](https://github.com/yourusername) • [LinkedIn](https://linkedin.com/in/yourprofile)

---

## 🙏 Acknowledgments

- [Laravel Framework](https://laravel.com)
- [Tailwind CSS](https://tailwindcss.com)
- [Stripe](https://stripe.com)
- [Lucide Icons](https://lucide.dev)
- [n8n](https://n8n.io)

---

## 📧 Support

For questions or issues:
- 📧 Email: support@pixelparts.com
- 🐛 Issues: [GitHub Issues](https://github.com/yourusername/pixelparts/issues)

---

<div align="center">
  <strong>PixelParts</strong> - Gaming Components of Elite 🎮
  <br>
  Built with ❤️ using Laravel & Tailwind CSS
</div>
