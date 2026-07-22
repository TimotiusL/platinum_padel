# Implementasi Tampilan htdocs ke Laravel

Tampilan dari `htdocs.zip` sudah dipindahkan ke proyek Laravel dengan struktur terpisah:

- HTML/Blade utama: `resources/views/home/index.blade.php`
- CSS utama: `public/css/platinum.css`
- JavaScript utama: `public/js/platinum.js`
- PHP controller: `app/Http/Controllers/HomeController.php`
- PHP route: `routes/web.php`
- CSS autentikasi: `public/css/platinum-auth.css`
- Tampilan login: `resources/views/auth/login.blade.php`
- Tampilan register: `resources/views/auth/register.blade.php`
- Tampilan dashboard: `resources/views/dashboard.blade.php`

Navigasi utama menggunakan hash route seperti versi referensi:

- `/#/`
- `/#/tournaments`
- `/#/players`

URL Laravel lama `/tournaments`, `/tournaments/{id}`, `/players`, dan `/players/{id}` tetap tersedia dan otomatis diarahkan ke hash route yang sesuai.

## Menjalankan

1. Salin `.env.example` menjadi `.env`.
2. Atur koneksi database pada `.env`.
3. Jalankan `composer install` bila folder `vendor` belum tersedia.
4. Jalankan `php artisan key:generate`.
5. Jalankan `php artisan migrate`.
6. Arahkan document root hosting ke folder `public`.
