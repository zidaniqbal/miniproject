# Belajar

![Laravel](https://img.shields.io/badge/Laravel-10-red?style=for-the-badge&logo=laravel)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-blue?style=for-the-badge&logo=bootstrap)
![MySQL](https://img.shields.io/badge/MySQL-Database-informational?style=for-the-badge&logo=mysql)

Sistem manajemen user dengan multiple role menggunakan **Laravel 10**, **Bootstrap 5**, dan **MySQL**.

## 🚀 Fitur

✅ Multi-level Authentication (Admin & User)  
✅ Dashboard Admin  
✅ Dashboard User  
✅ Landing Page  
✅ Bootstrap 5 UI + Icons  
✅ Responsive Design  
✅ User Registration & Login  
✅ Database Seeder untuk Admin dan User default

---

## 🛠️ Persyaratan Sistem

Pastikan komputer Anda memiliki software berikut:

-   PHP >= 8.1
-   Composer
-   Node.js & NPM
-   MySQL
-   Git _(optional, untuk clone)_

---

## 🔧 Perintah Terminal untuk Instalasi Project Baru

```bash
# 1. Buat project Laravel baru
composer create-project laravel/laravel project-name

# 2. Masuk ke direktori proyek
cd project-name

# 3. Install Laravel UI
composer require laravel/ui

# 4. Generate auth scaffolding dengan Bootstrap
php artisan ui bootstrap --auth

# 5. Install dependencies Node.js
npm install

# 6. Build assets
npm run build

# 7. Buat migration untuk role
php artisan make:migration add_role_to_users_table --table=users

# 8. Buat seeder
php artisan make:seeder UserSeeder

# 9. Buat middleware
php artisan make:middleware CheckRole

# 10. Buat controllers
php artisan make:controller LandingController
php artisan make:controller UserDashboardController
php artisan make:controller AdminDashboardController
```

---

## 📥 Langkah Instalasi (Setelah Clone/Download)

```bash
# 1. Clone repository (atau download ZIP)
git clone https://github.com/username/repository.git
cd repository

# 2. Install dependencies
composer install
npm install

# 3. Salin file .env dan konfigurasi
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Buat database baru di MySQL
CREATE DATABASE laravel_role_management;

# 6. Sesuaikan konfigurasi database di .env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=laravel_role_management
DB_USERNAME=root
DB_PASSWORD=

# 7. Jalankan migration
php artisan migrate

# 8. Build assets dan jalankan server
npm run build
php artisan serve
npm run dev
```

---

## 📷 Screenshots

Tambahkan beberapa screenshot dari aplikasi Anda di sini untuk menunjukkan antarmuka pengguna.

---

## 🤝 Kontribusi

Jika Anda ingin berkontribusi, silakan fork repository ini dan ajukan pull request.

---

## 📜 Lisensi

Proyek ini dilindungi oleh lisensi **MIT**.

---

## 📬 Kontak

Jika Anda memiliki pertanyaan, silakan hubungi:
📧 Email: your@email.com  
🐙 GitHub: [yourgithub](https://github.com/yourgithub)
