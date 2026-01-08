# Sistem Absensi PKL

Aplikasi web untuk mengelola absensi dan jurnal harian siswa/mahasiswa Praktik Kerja Lapangan (PKL). Dibangun dengan Laravel 12, Livewire 3, dan Tailwind CSS 4.

## ✨ Fitur Utama

-   **Autentikasi Lengkap**

    -   Login & Register
    -   Two-Factor Authentication (2FA)
    -   Reset Password
    -   Email Verification

-   **Manajemen Absensi**

    -   Pencatatan kehadiran harian
    -   Status kehadiran (Hadir, Sakit, Izin, dll)
    -   Alasan ketidakhadiran

-   **Jurnal Harian**

    -   Catatan aktivitas/kegiatan harian PKL
    -   Riwayat jurnal per user

-   **Dashboard Interaktif**

    -   Ringkasan absensi
    -   Ringkasan jurnal
    -   Dark/Light mode toggle

-   **Pengaturan Profil**
    -   Update profil
    -   Ganti password
    -   Pengaturan tampilan (appearance)
    -   Manajemen 2FA

## 🛠️ Tech Stack

| Kategori       | Teknologi                                     |
| -------------- | --------------------------------------------- |
| Framework      | Laravel 12                                    |
| Frontend       | Livewire 3 + Volt                             |
| UI Components  | Flux (Livewire)                               |
| Styling        | Tailwind CSS 4                                |
| Build Tool     | Vite 7                                        |
| Database       | SQLite (default), MySQL/PostgreSQL (opsional) |
| Authentication | Laravel Fortify                               |
| Testing        | Pest PHP                                      |
| Linting        | Laravel Pint                                  |

## 📋 Persyaratan Sistem

-   PHP >= 8.2
-   Composer
-   Node.js >= 18
-   NPM atau Yarn

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone <repository-url>
cd absensi_pkl
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Sesuaikan File `.env`

Edit file `.env` dan sesuaikan konfigurasi database:

```env
# Untuk SQLite (default)
DB_CONNECTION=sqlite

# Untuk MySQL
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=absensi_pkl
# DB_USERNAME=root
# DB_PASSWORD=
```

### 5. Jalankan Migrasi Database

```bash
php artisan migrate
```

### 6. Build Assets

```bash
npm run build
```

### 7. Jalankan Aplikasi

**Mode Development (dengan hot reload):**

```bash
composer dev
```

Perintah ini akan menjalankan:

-   Laravel development server
-   Queue listener
-   Vite dev server

**Atau jalankan manual:**

```bash
php artisan serve
npm run dev
```

Akses aplikasi di: `http://localhost:8000`

## 📁 Struktur Proyek

```
absensi_pkl/
├── app/
│   ├── Actions/           # Action classes (Fortify)
│   ├── Http/              # Controllers
│   ├── Livewire/          # Livewire components
│   │   ├── AbsentUserInput.php
│   │   ├── AbsentUsers.php
│   │   ├── JurnalUserInput.php
│   │   └── JurnalUsers.php
│   ├── Models/            # Eloquent models
│   │   ├── User.php
│   │   ├── AbsentUser.php
│   │   └── JurnalUser.php
│   └── Providers/         # Service providers
│
├── database/
│   ├── migrations/        # Database migrations
│   ├── factories/         # Model factories
│   └── seeders/           # Database seeders
│
├── resources/
│   ├── css/               # Stylesheets
│   ├── js/                # JavaScript files
│   └── views/             # Blade templates
│       ├── components/    # Blade components
│       ├── flux/          # Flux UI components
│       └── livewire/      # Livewire views
│           ├── auth/      # Authentication views
│           └── settings/  # Settings views
│
├── routes/
│   ├── web.php            # Web routes
│   └── console.php        # Artisan commands
│
├── tests/
│   ├── Feature/           # Feature tests
│   └── Unit/              # Unit tests
│
└── .github/
    └── workflows/         # GitHub Actions
        ├── lint.yml       # Code linting
        └── tests.yml      # Automated testing
```

## 🗄️ Database Schema

### Users

| Kolom             | Tipe      | Keterangan             |
| ----------------- | --------- | ---------------------- |
| id                | bigint    | Primary key            |
| name              | string    | Nama lengkap           |
| email             | string    | Email (unique)         |
| password          | string    | Password (hashed)      |
| email_verified_at | timestamp | Waktu verifikasi email |
| two*factor*\*     | -         | Kolom untuk 2FA        |
| remember_token    | string    | Token remember me      |
| timestamps        | -         | created_at, updated_at |

### Absent Users (Absensi)

| Kolom       | Tipe   | Keterangan                    |
| ----------- | ------ | ----------------------------- |
| id          | bigint | Primary key                   |
| user_id     | bigint | Foreign key ke users          |
| absent_date | date   | Tanggal absensi               |
| status      | string | Status (Hadir/Sakit/Izin/dll) |
| reason      | text   | Alasan (nullable)             |
| timestamps  | -      | created_at, updated_at        |

### Jurnal Users (Jurnal)

| Kolom       | Tipe   | Keterangan             |
| ----------- | ------ | ---------------------- |
| id          | bigint | Primary key            |
| user_id     | bigint | Foreign key ke users   |
| jurnal_date | date   | Tanggal jurnal         |
| activity    | text   | Deskripsi kegiatan     |
| timestamps  | -      | created_at, updated_at |

## 🧪 Testing

Jalankan test suite dengan Pest:

```bash
composer test
```

Atau langsung:

```bash
php artisan test
```

## 🔧 Perintah Artisan Berguna

```bash
# Jalankan migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Fresh migration (reset + migrate)
php artisan migrate:fresh

# Run seeders
php artisan db:seed

# Clear all cache
php artisan optimize:clear

# Generate application key
php artisan key:generate

# Run code linting
./vendor/bin/pint
```

## 🚀 CI/CD

Proyek ini menggunakan GitHub Actions untuk:

-   **Lint** (`lint.yml`): Menjalankan Laravel Pint untuk code style
-   **Tests** (`tests.yml`): Menjalankan automated tests dengan Pest

Workflow akan berjalan otomatis pada setiap push dan pull request.

## 📝 Environment Variables

| Variable         | Keterangan                     | Default          |
| ---------------- | ------------------------------ | ---------------- |
| APP_NAME         | Nama aplikasi                  | Laravel          |
| APP_ENV          | Environment (local/production) | local            |
| APP_DEBUG        | Mode debug                     | true             |
| APP_URL          | URL aplikasi                   | http://localhost |
| DB_CONNECTION    | Driver database                | sqlite           |
| MAIL_MAILER      | Driver email                   | log              |
| SESSION_DRIVER   | Driver session                 | database         |
| QUEUE_CONNECTION | Driver queue                   | database         |
| CACHE_STORE      | Driver cache                   | database         |

## 🤝 Kontribusi

1. Fork repository ini
2. Buat branch fitur (`git checkout -b feature/fitur-baru`)
3. Commit perubahan (`git commit -m 'Menambahkan fitur baru'`)
4. Push ke branch (`git push origin feature/fitur-baru`)
5. Buat Pull Request

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

---

Dikembangkan dengan ❤️ untuk kebutuhan PKL
