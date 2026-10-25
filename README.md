# Simple Blog (Laravel 13)

O'rganish uchun oddiy blog. Bitta Laravel backend ikki xil interfeys bilan ishlaydi:

- **Blade** (server tomonda render): Breeze auth, kategoriyalar, postlar, izohlar.
- **React SPA** (`frontend/`): Laravel JSON API (Sanctum) orqali ishlaydi.

Kod ichidagi izohlarda har bir Laravel tushunchasi Django'dagi o'xshashi bilan solishtirilgan.

## Mundarija

- [Imkoniyatlar](#imkoniyatlar)
- [Texnologiyalar](#texnologiyalar)
- [Talablar](#talablar)
- [O'rnatish](#ornatish)
  - [Laravel (backend va Blade)](#1-laravel-backend-va-blade)
  - [React frontend](#2-react-frontend)
- [Ishga tushirish](#ishga-tushirish)
- [Test akkaunt va seed ma'lumotlar](#test-akkaunt-va-seed-malumotlar)
- [Loyiha tuzilmasi](#loyiha-tuzilmasi)
- [Ma'lumotlar bazasi](#malumotlar-bazasi)
- [Ruxsatlar (policy)](#ruxsatlar-policy)
- [Blade marshrutlari](#blade-marshrutlari)
- [JSON API](#json-api)
- [React frontend tafsilotlari](#react-frontend-tafsilotlari)
- [Testlar](#testlar)
- [Muhit o'zgaruvchilari](#muhit-ozgaruvchilari)
- [Foydali buyruqlar](#foydali-buyruqlar)
- [Muammolarni hal qilish](#muammolarni-hal-qilish)

## Imkoniyatlar

- Ro'yxatdan o'tish, kirish, chiqish, parolni tiklash, email tasdiqlash, profilni tahrirlash (Breeze).
- Postlarni ko'rish (hamma uchun ochiq), yaratish, tahrirlash va o'chirish (faqat muallif).
- Kategoriya bo'yicha filtr va sahifalash (10 tadan).
- Izoh qoldirish; izohni muallifi yoki post muallifi o'chira oladi.
- Shaxsiy dashboard: foydalanuvchining o'z postlari (faqat Blade).
- URL'da `id` o'rniga `slug` (`/posts/my-first-post`), slug sarlavhadan avtomatik hosil bo'ladi va noyobligi ta'minlanadi.
- Bir xil ruxsat qoidalari (policy) Blade ham, API ham uchun ishlaydi.

## Texnologiyalar

| Qatlam | Nima ishlatilgan |
|---|---|
| Backend | Laravel 13, PHP 8.3+ |
| Baza | SQLite (standart) |
| Auth (Blade) | Laravel Breeze, session |
| Auth (API) | Laravel Sanctum, Bearer token |
| Blade UI | Tailwind CSS 3, Alpine.js, Vite |
| React UI | React 19, React Router 7, Tailwind CSS 4, Vite 8 (`.jsx`) |
| Testlar | PHPUnit 12 |
| Kod uslubi | Laravel Pint |

## Talablar

- PHP 8.3+ (`pdo_sqlite` kengaytmasi bilan)
- Composer 2
- Node.js 20+ va npm

## O'rnatish

### 1. Laravel (backend va Blade)

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
npm install
```

`php artisan migrate --seed` jadvallarni yaratadi va test ma'lumotlarni to'ldiradi
(qarang: [Test akkaunt va seed ma'lumotlar](#test-akkaunt-va-seed-malumotlar)).

### 2. React frontend

```bash
cd frontend
npm install
```

## Ishga tushirish

Development rejimida uchta jarayon kerak bo'lishi mumkin, har birini alohida terminalda ishga tushiring.

| Xizmat | Buyruq (papka) | Manzil |
|---|---|---|
| Laravel server | `php artisan serve` (ildiz) | http://127.0.0.1:8000 |
| Blade uchun Vite (Tailwind, Alpine) | `npm run dev` (ildiz) | `:5173` |
| React dev server | `npm run dev` (`frontend/`) | http://localhost:5174 |

Qaysi interfeys kerakligiga qarab:

- **Faqat Blade:** Laravel server + ildizdagi Vite (yoki bir marta `npm run build`, keyin Vite shart emas).
- **Faqat React:** Laravel server (API uchun) + `frontend/` dev serveri.
- **Ikkalasi:** uchala xizmat.

Production uchun:

```bash
npm run build              # Blade assetlari (public/build)
cd frontend && npm run build   # React (frontend/dist)
```

## Test akkaunt va seed ma'lumotlar

`php artisan migrate --seed` quyidagilarni yaratadi:

- Foydalanuvchi: `test@example.com` / `password`
- Yana 4 ta tasodifiy foydalanuvchi
- 5 ta kategoriya: Laravel, Django, PHP, Python, JavaScript
- 30 ta post, har biriga 0-5 ta izoh

Bazani noldan qayta to'ldirish: `php artisan migrate:fresh --seed`

## Loyiha tuzilmasi

```
.
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── PostController.php        # Blade: postlar CRUD
│   │   │   ├── CommentController.php     # Blade: izohlar
│   │   │   ├── ProfileController.php     # Blade: profil
│   │   │   ├── Auth/                     # Breeze auth controllerlari
│   │   │   └── Api/                      # JSON API controllerlari
│   │   ├── Requests/                     # Validatsiya (Store/Update Post, StoreComment)
│   │   └── Resources/                    # API javob shakli (Post, Comment, Category, User)
│   ├── Models/                           # User, Category, Post, Comment
│   └── Policies/                         # PostPolicy, CommentPolicy
├── database/
│   ├── migrations/                       # Jadvallar
│   ├── factories/                        # Test ma'lumot generatorlari
│   └── seeders/                          # Category, Post, Database seeder
├── resources/
│   ├── views/                            # Blade shablonlari (posts, auth, profile, layouts)
│   ├── css/app.css
│   └── js/app.js                         # Alpine.js
├── routes/
│   ├── web.php                           # Blade marshrutlari
│   ├── auth.php                          # Breeze auth marshrutlari
│   └── api.php                           # JSON API (/api prefiksi)
├── tests/Feature/                        # PostTest, ApiTest, Auth/, ProfileTest
└── frontend/                             # React SPA (alohida package.json)
    ├── vite.config.js                    # port 5174 va /api proxy
    └── src/
        ├── main.jsx                      # kirish nuqtasi
        ├── App.jsx                       # layout va marshrutlar
        ├── api.js                        # fetch wrapper (token, xatolar)
        ├── auth.jsx                      # AuthProvider va useAuth()
        ├── components/Field.jsx          # forma maydoni (label + xato)
        └── pages/                        # PostList, PostShow, PostForm, Login, Register
```

## Ma'lumotlar bazasi

Asosiy jadvallar (`users` Laravel standart jadvali):

**categories**

| Ustun | Tur | Izoh |
|---|---|---|
| id | bigint | PK |
| name | string | |
| slug | string | noyob |
| created_at, updated_at | timestamp | |

**posts**

| Ustun | Tur | Izoh |
|---|---|---|
| id | bigint | PK |
| user_id | FK -> users | muallif; user o'chsa postlari ham o'chadi (cascade) |
| category_id | FK -> categories | postli kategoriyani o'chirib bo'lmaydi (restrict) |
| title | string | |
| slug | string | noyob, sarlavhadan avtomatik |
| body | text | |
| created_at, updated_at | timestamp | |

**comments**

| Ustun | Tur | Izoh |
|---|---|---|
| id | bigint | PK |
| post_id | FK -> posts | post o'chsa izohlari ham o'chadi |
| user_id | FK -> users | izoh muallifi |
| body | text | |
| created_at, updated_at | timestamp | |

**personal_access_tokens**: Sanctum API tokenlari (`php artisan install:api` yaratgan).

Bog'lanishlar: `User` 1-N `Post`, `Category` 1-N `Post`, `Post` 1-N `Comment`, `User` 1-N `Comment`.

## Ruxsatlar (policy)

| Amal | Kim qila oladi |
|---|---|
| Postlarni ko'rish | hamma (mehmon ham) |
| Post yaratish | har qanday kirgan foydalanuvchi |
| Postni tahrirlash / o'chirish | faqat post muallifi |
| Izoh qoldirish | har qanday kirgan foydalanuvchi |
| Izohni o'chirish | izoh muallifi yoki post muallifi |

Ruxsat bo'lmasa `403 Forbidden` qaytadi. Qoidalar `app/Policies/` da.

## Blade marshrutlari

| Metod | URL | Tavsif | Auth |
|---|---|---|---|
| GET | `/` | `/posts` ga yo'naltiradi | yo'q |
| GET | `/posts` | Ro'yxat (`?category=slug`) | yo'q |
| GET | `/posts/{slug}` | Bitta post | yo'q |
| GET | `/posts/create` | Yangi post formasi | ha |
| POST | `/posts` | Post saqlash | ha |
| GET | `/posts/{slug}/edit` | Tahrirlash formasi | ha (muallif) |
| PUT/PATCH | `/posts/{slug}` | Postni yangilash | ha (muallif) |
| DELETE | `/posts/{slug}` | Postni o'chirish | ha (muallif) |
| POST | `/posts/{slug}/comments` | Izoh qo'shish | ha |
| DELETE | `/comments/{id}` | Izohni o'chirish | ha |
| GET | `/dashboard` | O'z postlarim | ha |
| GET/PATCH/DELETE | `/profile` | Profil | ha |
| GET/POST | `/login`, `/register`, `/forgot-password`, ... | Breeze auth | |

To'liq ro'yxat: `php artisan route:list`.

## JSON API

Barcha manzillar `/api` prefiksi bilan. Autentifikatsiya: Sanctum Bearer token.

```
Authorization: Bearer <token>
Accept: application/json
```

### Endpointlar

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

### So'rov maydonlari

| Endpoint | Maydonlar |
|---|---|
| `POST /api/register` | `name`, `email`, `password`, `password_confirmation` |
| `POST /api/login` | `email`, `password` |
| `POST/PUT /api/posts` | `title` (majburiy, 255 gacha), `category_id` (mavjud kategoriya), `body` (kamida 10 belgi) |
| `POST /api/posts/{slug}/comments` | `body` (majburiy, 1000 gacha) |

### Javob formatlari

Post:

```json
{
  "data": {
    "id": 1,
    "slug": "my-first-post",
    "title": "My first post",
    "body": "...",
    "created_at": "2026-10-16T12:53:19.000000Z",
    "comments_count": 2,
    "user": { "id": 6, "name": "Ali" },
    "category": { "id": 1, "name": "Laravel", "slug": "laravel" },
    "comments": [],
    "can_update": false,
    "can_delete": false
  }
}
```

- `GET /api/posts` ro'yxati `data`, `links` va `meta` (`current_page`, `last_page`, ...) qaytaradi. `comments` faqat bitta post so'ralganda keladi.
- `can_update` / `can_delete` joriy foydalanuvchiga qarab hisoblanadi (mehmon uchun `false`). Frontend tugmalarni shunga qarab ko'rsatadi.
- Login/register javobi: `{ "token": "...", "user": { "id": 1, "name": "..." } }`.

### Status kodlar

| Kod | Qachon |
|---|---|
| 200 | Muvaffaqiyatli o'qish yoki yangilash |
| 201 | Yaratildi (post, izoh, register) |
| 204 | O'chirildi (javob tanasi yo'q) |
| 401 | Token yo'q yoki yaroqsiz |
| 403 | Ruxsat yo'q (boshqaning posti yoki izohi) |
| 404 | Post yoki izoh topilmadi |
| 422 | Validatsiya xatosi |

Validatsiya xatosi:

```json
{
  "message": "The title field is required.",
  "errors": { "title": ["The title field is required."] }
}
```

### curl misollari

```bash
# Kirish va token olish
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password"}'

# Postlar ro'yxati (ochiq)
curl -H "Accept: application/json" "http://127.0.0.1:8000/api/posts?category=laravel"

# Post yaratish (token bilan)
curl -X POST http://127.0.0.1:8000/api/posts \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -H "Authorization: Bearer <token>" \
  -d '{"title":"Salom","category_id":1,"body":"Birinchi postim matni."}'
```

## React frontend tafsilotlari

### Sahifalar

| URL | Sahifa | Auth |
|---|---|---|
| `/posts` | Postlar ro'yxati, kategoriya filtri, sahifalash | yo'q |
| `/posts/:slug` | Post, izohlar, izoh qo'shish/o'chirish | yo'q (izoh uchun kirish kerak) |
| `/posts/new` | Yangi post | ha |
| `/posts/:slug/edit` | Postni tahrirlash | ha |
| `/login`, `/register` | Kirish va ro'yxatdan o'tish | yo'q |

Login qilmagan foydalanuvchi himoyalangan sahifaga kirsa `/login` ga yo'naltiriladi.

### Qanday ishlaydi

- **Token:** login/register javobidagi token `localStorage` da (`blog_token`) saqlanadi va har bir so'rovga `Authorization` sarlavhasi bilan qo'shiladi (`src/api.js`).
- **Sessiyani tiklash:** sahifa yangilanganda `AuthProvider` (`src/auth.jsx`) token bo'yicha `/api/user` ni chaqirib foydalanuvchini tiklaydi. Token yaroqsiz bo'lsa o'chiriladi.
- **Xatolar:** `ApiError` 422 javobdagi `errors` ni formalarga yetkazadi, `Field` komponenti ularni maydon ostida ko'rsatadi.
- **Proxy:** dev serverda `/api` so'rovlari `http://localhost:8000` ga yo'naltiriladi (`frontend/vite.config.js`), shuning uchun CORS kerak emas.
- **Production:** `frontend/dist/` statik fayllarni istalgan hostingga qo'yish mumkin; u holda `/api` ni Laravel'ga yo'naltirish (reverse proxy) yoki API manzilini sozlash kerak bo'ladi.

## Testlar

```bash
php artisan test
```

Hozir 42 ta test: Breeze auth, profil, postlar (Blade) va JSON API (`tests/Feature/ApiTest.php`).
Har bir test `RefreshDatabase` bilan toza bazada ishlaydi.

API testlari quyidagilarni tekshiradi: mehmon ro'yxat va postni ko'ra olishi, kategoriya filtri, register/login/logout,
noto'g'ri parol, post CRUD, validatsiya xatolari, boshqaning post va izohiga ruxsat yo'qligi.

Kod uslubini tekshirish va tuzatish: `./vendor/bin/pint`

## Muhit o'zgaruvchilari

`.env.example` dan `.env` nusxalanadi. Asosiylari:

| O'zgaruvchi | Standart | Izoh |
|---|---|---|
| `APP_NAME` | Laravel | Ilova nomi |
| `APP_ENV` | local | Muhit (`production` da `APP_DEBUG=false` qiling) |
| `APP_KEY` | | `php artisan key:generate` yaratadi |
| `APP_DEBUG` | true | Production'da `false` |
| `APP_URL` | http://localhost:8000 | Ilova manzili |
| `DB_CONNECTION` | sqlite | Baza turi |
| `SESSION_DRIVER` | database | Session saqlash joyi |
| `QUEUE_CONNECTION` | database | Navbat drayveri |
| `CACHE_STORE` | database | Kesh |

`.env` fayl gitga qo'shilmaydi. Maxfiy kalitlarni kodga yozmang.

## Foydali buyruqlar

| Laravel | Django |
|---|---|
| `php artisan serve` | `python manage.py runserver` |
| `php artisan migrate` | `python manage.py migrate` |
| `php artisan migrate:fresh --seed` | `flush` + `migrate` + `loaddata` |
| `php artisan make:migration ...` | `makemigrations` |
| `php artisan tinker` | `python manage.py shell` |
| `php artisan route:list` | `show_urls` (django-extensions) |
| `php artisan test` | `python manage.py test` |

## Muammolarni hal qilish

| Muammo | Yechim |
|---|---|
| Blade sahifasida stil yo'q yoki `Vite manifest not found` | Ildizda `npm run dev` ni ishga tushiring yoki bir marta `npm run build` qiling |
| React'da `/api` so'rovlari xato beradi | Laravel server (`php artisan serve`, `:8000`) ishlab turganini tekshiring |
| `5174` yoki `8000` port band | Boshqa jarayonni to'xtating yoki portni o'zgartiring (`frontend/vite.config.js`, `--port`) |
| `could not find driver` | PHP'da `pdo_sqlite` kengaytmasini yoqing |
| `No application encryption key` | `php artisan key:generate` |
| `unable to open database file` | `database/database.sqlite` faylini yarating: `touch database/database.sqlite` |
| API `401 Unauthenticated` | `Authorization: Bearer <token>` va `Accept: application/json` sarlavhalarini yuboring |
