# Simple Blog (Laravel 13)

O'rganish uchun oddiy blog: Breeze (Blade) auth, kategoriyalar, postlar, izohlar.
Kod ichidagi izohlarda har bir Laravel tushunchasi Django'dagi o'xshashi bilan solishtirilgan.

## Talablar

- PHP 8.3+ (`pdo_sqlite` kengaytmasi bilan)
- Composer 2
- Node.js 20+

## O'rnatish

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

Brauzerda: http://127.0.0.1:8000

Test akkaunt: `test@example.com` / `password`

## Foydali buyruqlar

| Laravel | Django |
|---|---|
| `php artisan serve` | `python manage.py runserver` |
| `php artisan migrate` | `python manage.py migrate` |
| `php artisan migrate:fresh --seed` | `flush` + `migrate` + `loaddata` |
| `php artisan tinker` | `python manage.py shell` |
| `php artisan route:list` | `show_urls` (django-extensions) |
| `php artisan test` | `python manage.py test` |
