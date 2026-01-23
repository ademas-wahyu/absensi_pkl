# 🚀 Quick Start Guide - Modul Setting

## ⚡ 5 Menit Setup

### 1️⃣ Setup Database (1 menit)

```bash
# Jalankan migration untuk membuat tabel
php artisan migrate

# Isi dengan data dummy (opsional tapi direkomendasikan)
php artisan db:seed --class=DivisiAdminSeeder
php artisan db:seed --class=SekolahSeeder
php artisan db:seed --class=MentorSeeder
```

**Selesai!** Database siap digunakan ✅

---

### 2️⃣ Akses Halaman Setting (30 detik)

1. Login sebagai **admin**
2. Buka URL: `/setting` atau klik menu **Settings** di sidebar
3. Anda akan melihat 3 card: **Divisi**, **Asal Sekolah**, **Mentor**

---

### 3️⃣ Tambah Data Baru (1 menit)

**Contoh: Menambah Divisi**

1. Di card **Pengaturan Divisi**, isi form:
   - Nama Divisi: `Marketing`
   - Deskripsi: `Divisi pemasaran dan promosi`
2. Klik tombol **"Simpan Perubahan"**
3. ✅ Notifikasi sukses muncul!

**Ulangi untuk Sekolah dan Mentor** dengan cara yang sama.

---

### 4️⃣ Lihat Data dalam Modal (1 menit)

1. Klik tombol **"Lihat Detail"** di salah satu card
2. Modal terbuka dengan tabel data
3. Anda dapat:
   - ✏️ **Edit** - Klik tombol Edit untuk mengubah data
   - 🗑️ **Hapus** - Klik tombol Hapus untuk menghapus data
4. Klik **"Tutup"** untuk menutup modal

---

### 5️⃣ Edit Data (1 menit)

1. Buka modal **"Lihat Detail"**
2. Klik tombol **"Edit"** pada baris data yang ingin diubah
3. Form di card akan terisi otomatis
4. Ubah data sesuai keinginan
5. Klik **"Simpan Perubahan"**
6. ✅ Data berhasil diupdate!

---

## 🎯 Fitur Utama

| Fitur | Divisi | Sekolah | Mentor |
|-------|--------|---------|--------|
| Tambah Data | ✅ | ✅ | ✅ |
| Lihat Detail | ✅ | ✅ | ✅ |
| Edit Data | ✅ | ✅ | ✅ |
| Hapus Data | ✅ | ✅ | ✅ |
| Modal Tabel | ✅ | ✅ | ✅ |

---

## 🔗 Relasi Database

```
Divisi (1) ──────< (∞) Mentor
```

**Penting:**
- Setiap Mentor **harus** memiliki Divisi
- Jika Divisi dihapus, semua Mentor terkait juga terhapus (CASCADE)
- Tambahkan Divisi terlebih dahulu sebelum menambah Mentor

---

## 📋 Contoh Data

### Divisi
- IT Support
- Frontend Development
- Backend Development
- UI/UX Design

### Sekolah
- SMK Negeri 1 Jakarta
- SMK Negeri 2 Bandung
- SMK Muhammadiyah 1 Surabaya

### Mentor
- Budi Santoso (IT Support)
- Siti Nurhaliza (Frontend Development)
- Ahmad Zaki (Backend Development)

---

## 🐛 Troubleshooting Cepat

### ❌ "Column not found: nama_divisi"
**Solusi:**
```bash
php artisan migrate:refresh
php artisan db:seed --class=DivisiAdminSeeder
```

### ❌ Dropdown Divisi kosong di form Mentor
**Solusi:**
```bash
php artisan db:seed --class=DivisiAdminSeeder
```
Refresh halaman.

### ❌ Modal tidak muncul
**Solusi:**
- Clear cache browser (Ctrl+Shift+R)
- Pastikan JavaScript tidak di-block
- Check console browser untuk error

---

## 📱 Responsive Design

✅ **Desktop** - 3 kolom side-by-side
✅ **Tablet** - 2 kolom
✅ **Mobile** - 1 kolom stack

---

## 🌙 Dark Mode

Semua komponen mendukung dark mode secara otomatis!

---

## 📚 Dokumentasi Lengkap

Butuh informasi lebih detail? Baca:

1. **User Guide** → `SETTING_MODULE_README.md`
2. **Database Schema** → `DATABASE_SCHEMA.md`
3. **ERD Visual** → `ERD_VISUAL.md`
4. **Implementation** → `IMPLEMENTATION_SUMMARY.md`

---

## ✅ Checklist First Time Setup

- [ ] Jalankan migration: `php artisan migrate`
- [ ] Jalankan seeder: `php artisan db:seed --class=DivisiAdminSeeder`
- [ ] Jalankan seeder: `php artisan db:seed --class=SekolahSeeder`
- [ ] Jalankan seeder: `php artisan db:seed --class=MentorSeeder`
- [ ] Login sebagai admin
- [ ] Akses halaman `/setting`
- [ ] Test tambah divisi baru
- [ ] Test tambah sekolah baru
- [ ] Test tambah mentor baru
- [ ] Test modal "Lihat Detail"
- [ ] Test edit data
- [ ] Test hapus data

---

## 🎉 Selamat!

Anda sudah siap menggunakan Modul Setting! 🚀

**Next Steps:**
1. Tambahkan divisi sesuai kebutuhan perusahaan
2. Tambahkan sekolah yang bekerja sama
3. Tambahkan mentor untuk setiap divisi
4. Assign siswa PKL ke mentor (coming soon)

---

**Last Updated:** 23 Januari 2026  
**Version:** 1.0.0  
**Estimated Setup Time:** 5 menit