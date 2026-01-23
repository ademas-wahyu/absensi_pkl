# Database Schema Documentation

## Sistem Absensi PKL

Dokumentasi ini menjelaskan struktur database untuk sistem absensi PKL, khususnya untuk modul Settings yang mencakup manajemen Divisi, Sekolah, dan Mentor.

---

## 📋 Daftar Tabel

1. [divisi_admins](#1-divisi_admins)
2. [sekolahs](#2-sekolahs)
3. [mentors](#3-mentors)

---

## 1. divisi_admins

Tabel untuk menyimpan data divisi dalam perusahaan/instansi.

### Struktur Tabel

| Kolom         | Tipe Data      | Null | Default | Keterangan                    |
|---------------|----------------|------|---------|-------------------------------|
| id            | BIGINT         | NO   | AUTO    | Primary Key                   |
| nama_divisi   | VARCHAR(255)   | NO   | -       | Nama divisi                   |
| deskripsi     | TEXT           | YES  | NULL    | Deskripsi divisi              |
| created_at    | TIMESTAMP      | YES  | NULL    | Waktu data dibuat             |
| updated_at    | TIMESTAMP      | YES  | NULL    | Waktu data terakhir diupdate  |

### Relationships

- **Has Many**: `mentors` - Satu divisi dapat memiliki banyak mentor

### Indexes

- PRIMARY KEY: `id`

### Contoh Data

```sql
INSERT INTO divisi_admins (nama_divisi, deskripsi, created_at, updated_at) VALUES
('IT Support', 'Divisi yang menangani infrastruktur teknologi informasi', NOW(), NOW()),
('Frontend Development', 'Divisi pengembangan antarmuka pengguna', NOW(), NOW()),
('Backend Development', 'Divisi pengembangan sistem server dan database', NOW(), NOW()),
('UI/UX Design', 'Divisi desain pengalaman dan antarmuka pengguna', NOW(), NOW());
```

---

## 2. sekolahs

Tabel untuk menyimpan data sekolah asal siswa PKL.

### Struktur Tabel

| Kolom         | Tipe Data      | Null | Default | Keterangan                    |
|---------------|----------------|------|---------|-------------------------------|
| id            | BIGINT         | NO   | AUTO    | Primary Key                   |
| nama_sekolah  | VARCHAR(255)   | NO   | -       | Nama sekolah                  |
| alamat        | TEXT           | YES  | NULL    | Alamat lengkap sekolah        |
| no_telepon    | VARCHAR(255)   | YES  | NULL    | Nomor telepon sekolah         |
| created_at    | TIMESTAMP      | YES  | NULL    | Waktu data dibuat             |
| updated_at    | TIMESTAMP      | YES  | NULL    | Waktu data terakhir diupdate  |

### Relationships

- Tidak memiliki relasi langsung dengan tabel lain (standalone)

### Indexes

- PRIMARY KEY: `id`

### Contoh Data

```sql
INSERT INTO sekolahs (nama_sekolah, alamat, no_telepon, created_at, updated_at) VALUES
('SMK Negeri 1 Jakarta', 'Jl. Budi Utomo No. 7, Jakarta Pusat', '021-3456789', NOW(), NOW()),
('SMK Negeri 2 Bandung', 'Jl. Ciliwung No. 4, Bandung', '022-7654321', NOW(), NOW()),
('SMK Muhammadiyah 1 Surabaya', 'Jl. Raya Darmo No. 12, Surabaya', '031-5678910', NOW(), NOW());
```

---

## 3. mentors

Tabel untuk menyimpan data mentor yang membimbing siswa PKL. Setiap mentor terhubung dengan divisi tertentu.

### Struktur Tabel

| Kolom         | Tipe Data      | Null | Default | Keterangan                      |
|---------------|----------------|------|---------|---------------------------------|
| id            | BIGINT         | NO   | AUTO    | Primary Key                     |
| nama_mentor   | VARCHAR(255)   | NO   | -       | Nama lengkap mentor             |
| email         | VARCHAR(255)   | YES  | NULL    | Email mentor                    |
| no_telepon    | VARCHAR(255)   | YES  | NULL    | Nomor telepon mentor            |
| divisi_id     | BIGINT         | NO   | -       | Foreign Key ke divisi_admins    |
| keahlian      | TEXT           | YES  | NULL    | Keahlian/skill mentor           |
| created_at    | TIMESTAMP      | YES  | NULL    | Waktu data dibuat               |
| updated_at    | TIMESTAMP      | YES  | NULL    | Waktu data terakhir diupdate    |

### Relationships

- **Belongs To**: `divisi_admins` - Setiap mentor milik satu divisi

### Indexes

- PRIMARY KEY: `id`
- FOREIGN KEY: `divisi_id` REFERENCES `divisi_admins(id)` ON DELETE CASCADE

### Constraints

- `divisi_id` harus ada di tabel `divisi_admins`
- Jika divisi dihapus, semua mentor yang terkait akan otomatis terhapus (CASCADE)

### Contoh Data

```sql
INSERT INTO mentors (nama_mentor, email, no_telepon, divisi_id, keahlian, created_at, updated_at) VALUES
('Budi Santoso', 'budi.santoso@company.com', '08123456789', 1, 'Network Administration, Server Management', NOW(), NOW()),
('Siti Nurhaliza', 'siti.nurhaliza@company.com', '08234567890', 2, 'React, Vue.js, Tailwind CSS', NOW(), NOW()),
('Ahmad Zaki', 'ahmad.zaki@company.com', '08345678901', 3, 'Laravel, Node.js, PostgreSQL', NOW(), NOW()),
('Dewi Lestari', 'dewi.lestari@company.com', '08456789012', 4, 'Figma, Adobe XD, User Research', NOW(), NOW());
```

---

## 🔗 Entity Relationship Diagram (ERD)

```
┌─────────────────┐
│  divisi_admins  │
├─────────────────┤
│ • id (PK)       │
│ • nama_divisi   │
│ • deskripsi     │
│ • created_at    │
│ • updated_at    │
└─────────────────┘
         │
         │ 1
         │
         │ has many
         │
         │ *
         ▼
┌─────────────────┐
│    mentors      │
├─────────────────┤
│ • id (PK)       │
│ • nama_mentor   │
│ • email         │
│ • no_telepon    │
│ • divisi_id(FK) │◄─┐
│ • keahlian      │  │ belongs to
│ • created_at    │  │
│ • updated_at    │  │
└─────────────────┘  │
                     └─


┌─────────────────┐
│    sekolahs     │
├─────────────────┤
│ • id (PK)       │
│ • nama_sekolah  │
│ • alamat        │
│ • no_telepon    │
│ • created_at    │
│ • updated_at    │
└─────────────────┘
(Standalone - No Relations)
```

---

## 📝 Model Eloquent

### DivisiAdmin Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DivisiAdmin extends Model
{
    protected $fillable = ['nama_divisi', 'deskripsi'];

    public function mentors()
    {
        return $this->hasMany(Mentor::class, 'divisi_id');
    }
}
```

### Sekolah Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    protected $fillable = ['nama_sekolah', 'alamat', 'no_telepon'];
}
```

### Mentor Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    protected $fillable = [
        'nama_mentor',
        'email',
        'no_telepon',
        'divisi_id',
        'keahlian',
    ];

    public function divisi()
    {
        return $this->belongsTo(DivisiAdmin::class, 'divisi_id');
    }
}
```

---

## 🎯 Use Cases

### 1. Menampilkan Mentor Beserta Divisinya

```php
$mentors = Mentor::with('divisi')->get();

foreach ($mentors as $mentor) {
    echo $mentor->nama_mentor . ' - ' . $mentor->divisi->nama_divisi;
}
```

### 2. Menampilkan Semua Mentor di Divisi Tertentu

```php
$divisi = DivisiAdmin::with('mentors')->find(1);

foreach ($divisi->mentors as $mentor) {
    echo $mentor->nama_mentor;
}
```

### 3. Menghitung Jumlah Mentor per Divisi

```php
$divisiList = DivisiAdmin::withCount('mentors')->get();

foreach ($divisiList as $divisi) {
    echo $divisi->nama_divisi . ': ' . $divisi->mentors_count . ' mentors';
}
```

### 4. Mencari Mentor Berdasarkan Keahlian

```php
$mentors = Mentor::where('keahlian', 'LIKE', '%Laravel%')
                 ->with('divisi')
                 ->get();
```

---

## 🔒 Aturan Bisnis

1. **Divisi**:
   - Nama divisi harus unik
   - Divisi tidak dapat dihapus jika masih memiliki mentor aktif (CASCADE akan menghapus semua mentor)
   
2. **Sekolah**:
   - Nama sekolah harus diisi
   - Data kontak (alamat, telepon) bersifat opsional
   
3. **Mentor**:
   - Setiap mentor wajib terhubung dengan satu divisi
   - Email mentor harus valid jika diisi
   - Jika divisi dihapus, mentor yang terkait akan otomatis terhapus

---

## 🚀 Migration Commands

### Membuat tabel baru:
```bash
php artisan migrate
```

### Rollback migration terakhir:
```bash
php artisan migrate:rollback
```

### Reset semua migration:
```bash
php artisan migrate:reset
```

### Refresh migration (drop all tables & migrate):
```bash
php artisan migrate:refresh
```

### Membuat seeder:
```bash
php artisan make:seeder DivisiAdminSeeder
php artisan make:seeder SekolahSeeder
php artisan make:seeder MentorSeeder
```

### Menjalankan seeder:
```bash
php artisan db:seed --class=DivisiAdminSeeder
php artisan db:seed --class=SekolahSeeder
php artisan db:seed --class=MentorSeeder
```

---

## 📊 Future Improvements

Beberapa peningkatan yang dapat dipertimbangkan:

1. **Tabel `users` Integration**:
   - Tambahkan kolom `divisi_id` dan `sekolah_id` di tabel users
   - Hubungkan siswa PKL dengan sekolah asal mereka
   - Hubungkan siswa PKL dengan divisi tempat mereka magang

2. **Tabel `mentor_siswa`** (Many-to-Many):
   - Buat tabel pivot untuk menghubungkan mentor dengan siswa
   - Satu mentor dapat membimbing banyak siswa
   - Satu siswa dapat memiliki beberapa mentor

3. **Audit Trail**:
   - Tambahkan kolom `created_by` dan `updated_by`
   - Track siapa yang membuat/mengubah data

4. **Soft Deletes**:
   - Implementasi soft delete untuk data historis
   - Data tidak benar-benar dihapus dari database

5. **Status Field**:
   - Tambahkan status aktif/non-aktif untuk mentor
   - Filter mentor yang sedang aktif membimbing

---

**Dokumentasi dibuat pada:** 23 Januari 2026  
**Versi:** 1.0  
**Maintainer:** Development Team