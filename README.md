# 🧩 PixelParts

PixelParts is a web application built with **Laravel 10**, **TailwindCSS**, and **MySQL**, simulating an online store specialized in high-performance gaming components.  
This project was developed for academic purposes, focusing on modern full-stack web development best practices.

---

## 🚀 Technologies Used

- ⚡ **[Laravel 10](https://laravel.com/)** — Modern PHP web development framework  
- 💅 **[TailwindCSS](https://tailwindcss.com/)** — Utility-first CSS framework for fast and responsive styling  
- 🛢️ **MySQL** — Relational database  
- 🔐 **Laravel Fortify and Socialite** — Authentication (login, registration, route protection)  
- 🌐 **HTML5 & Blade** — Dynamic templates using Laravel Blade

---

## 🧠 Main Features

- 👤 **Authentication System** with Laravel Fortify (Login / Register / Logout)  
- 🛍️ **Product Catalog** with categories, brands, and dynamic details  
- ⭐ **User Reviews & Ratings** with automatic rating average  
- 🧩 **Product Compatibility** (e.g., compatible PC components)  
- 🛒 **Shopping Cart** *(in development)*  
- 📱 **Responsive Design** powered by TailwindCSS

---

## ⚙️ Installation & Setup

### 1. Clone the repository

```bash
git clone https://github.com/your-username/pixelparts.git
cd pixelparts
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install front-end dependencies

```bash
npm install
```

### 4. Configure your `.env` file

Duplicate the example file and set up your database connection:

```bash
cp .env.example .env
```

Update the database credentials inside `.env` according to your local setup.

### 5. Generate the application key

```bash
php artisan key:generate
```

### 6. Run migrations

```bash
php artisan migrate
```

### 7. Build front-end assets

```bash
npm run dev
```

### 8. Start the local server

```bash
php artisan serve
```

The project should now be available at `http://127.0.0.1:8000`.

---

## 📝 Notes

**⚠️ This project is for academic purposes only.**  
**Some features are still under development** (e.g., cart and checkout) 🛠️

---

## 👨‍💻 Author

Developed by **Gonçalo Ferreira** for educational use.
