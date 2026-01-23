# 🔧 Troubleshooting Guide - Modul Setting

## 🚨 Common Errors & Solutions

### 1. Error: "Unable to locate a class or view for component [flux::banner]"

**Error Message:**
```
InvalidArgumentException
Unable to locate a class or view for component [flux::banner].
```

**Penyebab:**
Komponen `flux::banner` tidak tersedia di versi Flux yang digunakan.

**Solusi:**
Gunakan HTML biasa untuk notification/alert message.

**Sebelum (Error):**
```blade
@if (session()->has('message'))
    <flux:banner variant="success" class="mb-4">
        {{ session('message') }}
    </flux:banner>
@endif
```

**Sesudah (Fixed):**
```blade
@if (session()->has('message'))
    <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900/30 dark:text-green-300" role="alert">
        <svg class="inline w-4 h-4 me-2" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 0 0 1 0-2h1v-3H8a1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <span class="font-medium">Berhasil!</span> {{ session('message') }}
    </div>
@endif
```

**Kemudian jalankan:**
```bash
php artisan view:clear
```

---

### 2. Error: "Column not found: nama_divisi"

**Error Message:**
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'nama_divisi'
```

**Penyebab:**
Migration belum dijalankan atau tabel tidak memiliki kolom yang diperlukan.

**Solusi:**
```bash
# Rollback dan migrate ulang
php artisan migrate:refresh

# Atau migrate fresh (HATI-HATI: menghapus semua data)
php artisan migrate:fresh

# Isi dengan data dummy
php artisan db:seed --class=DivisiAdminSeeder
php artisan db:seed --class=SekolahSeeder
php artisan db:seed --class=MentorSeeder
```

---

### 3. Error: "SQLSTATE[23000]: Integrity constraint violation"

**Error Message:**
```
SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row
```

**Penyebab:**
Foreign key constraint violation. Biasanya terjadi saat:
- Menambah mentor tanpa divisi_id
- divisi_id yang dipilih tidak ada di tabel divisi_admins

**Solusi:**
1. Pastikan divisi sudah ada sebelum menambah mentor:
```bash
php artisan db:seed --class=DivisiAdminSeeder
```

2. Refresh halaman untuk reload dropdown divisi

3. Pilih divisi dari dropdown sebelum simpan mentor

---

### 4. Modal Tidak Muncul

**Gejala:**
- Klik tombol "Lihat Detail" tapi modal tidak terbuka
- Layar tidak gelap (no backdrop)

**Penyebab:**
- JavaScript/Livewire tidak ter-load
- Browser cache lama
- Konflik JavaScript

**Solusi:**

**A. Clear Cache Browser:**
- Chrome/Edge: `Ctrl + Shift + R` atau `Ctrl + F5`
- Firefox: `Ctrl + Shift + R`
- Safari: `Cmd + Option + R`

**B. Clear Laravel Cache:**
```bash
php artisan view:clear
php artisan cache:clear
php artisan config:clear
```

**C. Cek Console Browser:**
1. Buka Developer Tools (F12)
2. Tab Console
3. Cek apakah ada error JavaScript
4. Jika ada error Livewire, pastikan @livewireScripts ada di layout

**D. Pastikan Livewire Scripts ada:**
```blade
<!-- Di layout app.blade.php -->
@livewireStyles
</head>
<body>
    {{ $slot }}
    @livewireScripts
</body>
```

---

### 5. Dropdown Divisi Kosong di Form Mentor

**Gejala:**
- Dropdown "Pilih Divisi" tidak menampilkan pilihan
- Atau menampilkan "Pilih Divisi" saja

**Penyebab:**
Belum ada data divisi di database.

**Solusi:**
```bash
# Isi data divisi
php artisan db:seed --class=DivisiAdminSeeder

# Refresh halaman browser
```

Atau tambah divisi manual lewat form di card "Pengaturan Divisi".

---

### 6. Data Tidak Tersimpan / Form Tidak Submit

**Gejala:**
- Klik "Simpan Perubahan" tapi tidak ada respon
- Data tidak masuk ke database
- Tidak ada notifikasi sukses

**Penyebab & Solusi:**

**A. Validasi Gagal:**
- Cek field yang wajib diisi (required)
- Untuk Mentor: Divisi wajib dipilih
- Format email harus valid jika diisi

**B. Error di Console:**
```bash
# Cek log Laravel
tail -f storage/logs/laravel.log
```

**C. CSRF Token Expired:**
- Refresh halaman
- Clear browser cache
- Login ulang

---

### 7. Error 500 Internal Server Error

**Solusi Umum:**

**A. Enable Debug Mode:**
```env
# Di file .env
APP_DEBUG=true
```
Refresh halaman untuk melihat error detail.

**B. Cek Log:**
```bash
tail -f storage/logs/laravel.log
```

**C. Clear All Cache:**
```bash
php artisan optimize:clear
```

**D. Check Permission:**
```bash
chmod -R 775 storage bootstrap/cache
```

---

### 8. Dark Mode Tidak Berfungsi

**Gejala:**
- Switch dark mode tidak mengubah tema
- Warna tidak berubah

**Solusi:**
Refresh halaman dan clear browser cache. Flux UI menggunakan localStorage untuk menyimpan preferensi.

---

### 9. Livewire Error: "Component not found"

**Error Message:**
```
Unable to find component: [setting]
```

**Solusi:**
```bash
# Clear view cache
php artisan view:clear

# Clear Livewire cache
php artisan livewire:discover

# Atau clear all
php artisan optimize:clear
```

---

### 10. Database Connection Error

**Error Message:**
```
SQLSTATE[HY000] [2002] Connection refused
```

**Solusi:**

**A. Cek MySQL Running:**
```bash
# Linux/Mac
sudo systemctl status mysql

# Atau
ps aux | grep mysql
```

**B. Cek .env Configuration:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

**C. Test Connection:**
```bash
php artisan tinker
>>> DB::connection()->getPdo();
```

---

### 11. Edit Button Tidak Mengisi Form

**Gejala:**
- Klik tombol "Edit" di modal
- Form tidak terisi otomatis
- Atau terisi dengan data lama

**Solusi:**

**A. Refresh Halaman:**
Browser mungkin cache data lama.

**B. Clear Cache:**
```bash
php artisan view:clear
```

**C. Check Console:**
Buka F12 → Console, lihat error Livewire.

---

### 12. Delete Confirmation Tidak Muncul

**Gejala:**
- Klik "Hapus" langsung menghapus tanpa konfirmasi
- Atau tidak ada aksi sama sekali

**Solusi:**

Pastikan attribute `wire:confirm` ada di button:
```blade
<flux:button 
    wire:click="deleteDivisi({{ $divisi->id }})"
    wire:confirm="Apakah Anda yakin ingin menghapus divisi ini?" 
    size="sm"
    variant="danger">
    Hapus
</flux:button>
```

---

### 13. Setelah Delete Divisi, Mentor Masih Ada

**Gejala:**
Setelah hapus divisi, mentor yang terkait masih ada di tabel mentor.

**Penyebab:**
Foreign key CASCADE tidak berfungsi.

**Solusi:**

**A. Cek Migration:**
Pastikan ada `onDelete('cascade')`:
```php
$table->foreignId('divisi_id')
      ->constrained('divisi_admins')
      ->onDelete('cascade');
```

**B. Recreate Migration:**
```bash
php artisan migrate:refresh
php artisan db:seed --class=DivisiAdminSeeder
php artisan db:seed --class=MentorSeeder
```

---

### 14. Session Flash Message Tidak Muncul

**Gejala:**
Setelah simpan/edit/hapus data, tidak ada notifikasi sukses.

**Penyebab:**
Session flash tidak di-render di view.

**Solusi:**
Pastikan ada di view:
```blade
@if (session()->has('message'))
    <div class="mb-4 rounded-lg bg-green-50 p-4 ...">
        {{ session('message') }}
    </div>
@endif
```

---

### 15. Styling Berantakan / Tidak Ada CSS

**Gejala:**
- Tampilan tidak ada style
- Layout berantakan
- Button tidak ada warna

**Solusi:**

**A. Compile Assets:**
```bash
npm run build
# Atau untuk development
npm run dev
```

**B. Check Vite/Mix:**
Pastikan ada di layout:
```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

**C. Clear Browser Cache:**
Hard refresh dengan `Ctrl + Shift + R`

---

## 🛠️ Debugging Tools

### 1. Laravel Telescope (Recommended)
```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```
Akses: `http://localhost:8000/telescope`

### 2. Laravel Debugbar
```bash
composer require barryvdh/laravel-debugbar --dev
```

### 3. Tinker (Built-in)
```bash
php artisan tinker

# Test queries
>>> App\Models\DivisiAdmin::count();
>>> App\Models\Mentor::with('divisi')->first();
```

---

## 📋 Quick Diagnostics Checklist

Jika ada masalah, cek urut dari atas:

- [ ] Migration sudah dijalankan? `php artisan migrate`
- [ ] Data dummy sudah di-seed? `php artisan db:seed`
- [ ] Cache sudah di-clear? `php artisan view:clear`
- [ ] Browser cache sudah di-clear? `Ctrl + Shift + R`
- [ ] Console browser ada error? `F12 → Console`
- [ ] Log Laravel ada error? `tail -f storage/logs/laravel.log`
- [ ] Database connection OK? `php artisan tinker → DB::connection()->getPdo()`
- [ ] Livewire scripts loaded? Check HTML source
- [ ] .env configuration benar? Check DB_* variables

---

## 🆘 Emergency Commands

Jika semua cara sudah dicoba dan masih error:

```bash
# Nuclear option - clear everything
php artisan optimize:clear
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Recreate database (WARNING: menghapus semua data!)
php artisan migrate:fresh --seed

# Restart server
# Ctrl+C to stop
php artisan serve
```

---

## 📞 Getting Help

Jika masih stuck:

1. **Check Documentation:**
   - `QUICK_START.md` - Setup guide
   - `SETTING_MODULE_README.md` - User guide
   - `DATABASE_SCHEMA.md` - Database reference

2. **Check Laravel Logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. **Check Browser Console:**
   - F12 → Console tab
   - Look for JavaScript/Livewire errors

4. **Google the Error:**
   - Copy exact error message
   - Search: "Laravel [error message]"
   - Search: "Livewire [error message]"

---

## ✅ Prevention Tips

1. **Always backup before major changes:**
   ```bash
   mysqldump -u root -p database_name > backup.sql
   ```

2. **Test in development first:**
   - Never test directly in production
   - Use `APP_ENV=local` in .env

3. **Keep dependencies updated:**
   ```bash
   composer update
   npm update
   ```

4. **Use version control:**
   ```bash
   git add .
   git commit -m "Working version before changes"
   ```

---

**Last Updated:** 23 Januari 2026  
**Version:** 1.0.1  
**Untuk:** Modul Setting Absensi PKL