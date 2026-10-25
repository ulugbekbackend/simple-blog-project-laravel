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

## React frontend

Loyiha ikki xil interfeys bilan ishlaydi, ikkalasi bir xil bazadan foydalanadi:

- **Blade** (Laravel o'zi render qiladi): http://127.0.0.1:8000
- **React SPA** (`frontend/`, JSX + Vite + Tailwind + React Router): http://localhost:5174

React'ni ishga tushirish (Laravel `php artisan serve` bilan `:8000` da ishlab turishi kerak):

```bash
cd frontend
npm install
npm run dev
```

Dev serverda `/api` so'rovlari `vite.config.js` orqali Laravel'ga proxy qilinadi, shuning uchun CORS sozlash shart emas.
Production build: `npm run build` (natija `frontend/dist/`).

## API

JSON API `/api` prefiksi bilan ishlaydi, autentifikatsiya Laravel Sanctum Bearer token orqali:
`Authorization: Bearer <token>`.

| Metod | URL | Tavsif | Auth |
|---|---|---|---|
| POST | `/api/register` | Ro'yxatdan o'tish, token qaytaradi | yo'q |
| POST | `/api/login` | Kirish, token qaytaradi | yo'q |
| POST | `/api/logout` | Joriy tokenni o'chiradi | ha |
| GET | `/api/user` | Joriy foydalanuvchi | ha |
| GET | `/api/categories` | Kategoriyalar ro'yxati | yo'q |
| GET | `/api/posts` | Postlar (`?category=slug&page=2`) | yo'q |
| GET | `/api/posts/{slug}` | Bitta post izohlari bilan | yo'q |
| POST | `/api/posts` | Post yaratish | ha |
| PUT/PATCH | `/api/posts/{slug}` | Postni tahrirlash (faqat muallif) | ha |
| DELETE | `/api/posts/{slug}` | Postni o'chirish (faqat muallif) | ha |
| POST | `/api/posts/{slug}/comments` | Izoh qo'shish | ha |
| DELETE | `/api/comments/{id}` | Izohni o'chirish (izoh yoki post muallifi) | ha |

Post va izoh javoblarida `can_update` / `can_delete` maydonlari bor: frontend tugmalarni shunga qarab ko'rsatadi.
Validatsiya xatosi `422` bilan `{ "message": ..., "errors": { maydon: [...] } }` ko'rinishida qaytadi.

```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password"}'
```

## Foydali buyruqlar

| Laravel | Django |
|---|---|
| `php artisan serve` | `python manage.py runserver` |
| `php artisan migrate` | `python manage.py migrate` |
| `php artisan migrate:fresh --seed` | `flush` + `migrate` + `loaddata` |
| `php artisan tinker` | `python manage.py shell` |
| `php artisan route:list` | `show_urls` (django-extensions) |
| `php artisan test` | `python manage.py test` |
