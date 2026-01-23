# 📂 Project Structure - Modul Setting

## 🌲 File Tree Overview

```
absensi_pkl/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   ├── Livewire/
│   │   ├── Setting.php                    ✨ [UPDATED] Main component untuk setting
│   │   └── ...
│   └── Models/
│       ├── DivisiAdmin.php                ✨ [UPDATED] Model divisi dengan relationships
│       ├── Mentor.php                     ✨ [NEW] Model mentor
│       ├── Sekolah.php                    ✨ [NEW] Model sekolah
│       ├── User.php
│       └── ...
│
├── database/
│   ├── migrations/
│   │   ├── 2026_01_16_032910_create_divisi_admins_table.php    ✨ [UPDATED]
│   │   ├── 2026_01_23_024717_create_sekolahs_table.php         ✨ [NEW]
│   │   ├── 2026_01_23_024724_create_mentors_table.php          ✨ [NEW]
│   │   └── ...
│   │
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── DivisiAdminSeeder.php          ✨ [NEW] Seeder untuk divisi
│       ├── SekolahSeeder.php              ✨ [NEW] Seeder untuk sekolah
│       ├── MentorSeeder.php               ✨ [NEW] Seeder untuk mentor
│       └── ...
│
├── resources/
│   └── views/
│       ├── livewire/
│       │   ├── setting.blade.php          ✨ [UPDATED] View dengan 3 modal
│       │   └── ...
│       └── components/
│           └── ...
│
├── routes/
│   ├── web.php
│   └── ...
│
├── public/
│   ├── css/
│   └── js/
│
├── config/
├── storage/
├── tests/
├── vendor/
│
├── .env                                   🔒 Configuration
├── .env.example
├── artisan
├── composer.json
├── package.json
│
└── 📚 DOCUMENTATION FILES (NEW)
    ├── DATABASE_SCHEMA.md                 ✨ [NEW] Dokumentasi skema database lengkap
    ├── SETTING_MODULE_README.md           ✨ [NEW] User guide & tutorial
    ├── ERD_VISUAL.md                      ✨ [NEW] Visualisasi ERD dengan ASCII art
    ├── IMPLEMENTATION_SUMMARY.md          ✨ [NEW] Summary implementasi
    ├── QUICK_START.md                     ✨ [NEW] Quick start guide 5 menit
    ├── PROJECT_STRUCTURE.md               ✨ [NEW] File ini - struktur project
    └── README.md                          📖 Original project README
```

---

## 🎯 Files Created/Modified untuk Modul Setting

### 1. Backend - Models (3 files)

```
app/Models/
├── DivisiAdmin.php          [UPDATED]
│   ├── Properties: $fillable
│   ├── Relationship: hasMany(Mentor)
│   └── Table: divisi_admins
│
├── Mentor.php               [NEW]
│   ├── Properties: $fillable
│   ├── Relationship: belongsTo(DivisiAdmin)
│   └── Table: mentors
│
└── Sekolah.php              [NEW]
    ├── Properties: $fillable
    └── Table: sekolahs
```

---

### 2. Backend - Livewire Component (1 file)

```
app/Livewire/
└── Setting.php              [UPDATED]
    ├── Properties (18+)
    │   ├── Form inputs (9)
    │   ├── Modal states (3)
    │   └── Edit mode IDs (3)
    │
    ├── Methods (18+)
    │   ├── Divisi: open, close, save, edit, delete (5)
    │   ├── Sekolah: open, close, save, edit, delete (5)
    │   ├── Mentor: open, close, save, edit, delete (5)
    │   ├── Reset forms (3)
    │   └── render (1)
    │
    └── Validation Rules (12)
```

---

### 3. Database - Migrations (3 files)

```
database/migrations/
├── 2026_01_16_032910_create_divisi_admins_table.php    [UPDATED]
│   └── Columns: id, nama_divisi, deskripsi, timestamps
│
├── 2026_01_23_024717_create_sekolahs_table.php         [NEW]
│   └── Columns: id, nama_sekolah, alamat, no_telepon, timestamps
│
└── 2026_01_23_024724_create_mentors_table.php          [NEW]
    └── Columns: id, nama_mentor, email, no_telepon, 
                 divisi_id (FK), keahlian, timestamps
```

---

### 4. Database - Seeders (3 files)

```
database/seeders/
├── DivisiAdminSeeder.php    [NEW]
│   └── Data: 6 divisi dummy
│
├── SekolahSeeder.php        [NEW]
│   └── Data: 6 sekolah dummy
│
└── MentorSeeder.php         [NEW]
    └── Data: 8 mentor dummy (with divisi_id)
```

---

### 5. Frontend - Views (1 file)

```
resources/views/livewire/
└── setting.blade.php        [UPDATED]
    ├── Header Section
    ├── Success Banner
    │
    ├── 3 Cards (Forms)
    │   ├── Card 1: Divisi
    │   │   ├── Form inputs
    │   │   └── "Lihat Detail" button
    │   │
    │   ├── Card 2: Sekolah
    │   │   ├── Form inputs
    │   │   └── "Lihat Detail" button
    │   │
    │   └── Card 3: Mentor
    │       ├── Form inputs (with dropdown divisi)
    │       └── "Lihat Detail" button
    │
    └── 3 Modals (Tables)
        ├── Modal Divisi
        │   └── Table: No, Nama, Deskripsi, Aksi
        │
        ├── Modal Sekolah
        │   └── Table: No, Nama, Alamat, Telepon, Aksi
        │
        └── Modal Mentor
            └── Table: No, Nama, Email, Telepon, Divisi, Keahlian, Aksi
```

---

### 6. Documentation (6 files)

```
📚 Documentation Files/
├── DATABASE_SCHEMA.md              [NEW] 369 lines
│   ├── Table structures
│   ├── Relationships
│   ├── ERD diagram
│   ├── Eloquent models
│   ├── Use cases
│   └── Migration commands
│
├── SETTING_MODULE_README.md        [NEW] 427 lines
│   ├── Overview
│   ├── Cara menggunakan
│   ├── Komponen teknis
│   ├── UI/UX features
│   ├── Troubleshooting
│   └── Best practices
│
├── ERD_VISUAL.md                   [NEW] 403 lines
│   ├── ASCII art ERD
│   ├── Crow's foot notation
│   ├── Relationship details
│   ├── SQL queries examples
│   └── Business rules
│
├── IMPLEMENTATION_SUMMARY.md       [NEW] 470 lines
│   ├── Fitur implemented
│   ├── Files created/modified
│   ├── Statistics
│   ├── Testing results
│   └── Future enhancements
│
├── QUICK_START.md                  [NEW] 190 lines
│   ├── 5 minute setup
│   ├── Quick troubleshooting
│   └── Checklist
│
└── PROJECT_STRUCTURE.md            [NEW] This file
    └── Complete file tree overview
```

---

## 📊 File Statistics

### By Type

| Type | Created | Modified | Total |
|------|---------|----------|-------|
| Models | 2 | 1 | 3 |
| Livewire | 0 | 1 | 1 |
| Migrations | 2 | 1 | 3 |
| Seeders | 3 | 0 | 3 |
| Views | 0 | 1 | 1 |
| Documentation | 6 | 0 | 6 |
| **TOTAL** | **13** | **4** | **17** |

### By Category

```
Backend Code:      7 files   (~800 lines)
Database:          6 files   (~300 lines)
Frontend:          1 file    (~350 lines)
Documentation:     6 files   (~2,200 lines)
─────────────────────────────────────────
TOTAL:            20 files   (~3,650 lines)
```

---

## 🔍 Key Directories Explained

### `/app/Models/`
Berisi Eloquent models yang merepresentasikan tabel database:
- `DivisiAdmin.php` - Model untuk tabel divisi_admins
- `Mentor.php` - Model untuk tabel mentors
- `Sekolah.php` - Model untuk tabel sekolahs

### `/app/Livewire/`
Berisi Livewire components (full-stack components):
- `Setting.php` - Component utama untuk halaman setting dengan 3 modal

### `/database/migrations/`
Berisi file migration untuk membuat struktur database:
- Schema definitions
- Foreign key constraints
- Indexes

### `/database/seeders/`
Berisi file seeder untuk mengisi database dengan data dummy:
- Test data
- Sample records
- Development data

### `/resources/views/livewire/`
Berisi Blade views untuk Livewire components:
- `setting.blade.php` - View dengan cards dan modals

---

## 🎨 Component Architecture

```
┌─────────────────────────────────────┐
│         Browser (Client)            │
│  resources/views/livewire/          │
│  └── setting.blade.php              │
└────────────────┬────────────────────┘
                 │ Livewire Wire
                 │ (Real-time Updates)
                 ▼
┌─────────────────────────────────────┐
│      Livewire Component             │
│   app/Livewire/Setting.php          │
│   ├── Properties                    │
│   ├── Methods (CRUD)                │
│   └── Validation                    │
└────────────────┬────────────────────┘
                 │
                 │ Eloquent ORM
                 ▼
┌─────────────────────────────────────┐
│         Models Layer                │
│   app/Models/                       │
│   ├── DivisiAdmin.php               │
│   ├── Mentor.php                    │
│   └── Sekolah.php                   │
└────────────────┬────────────────────┘
                 │
                 │ SQL Queries
                 ▼
┌─────────────────────────────────────┐
│          Database                   │
│   MySQL (via migrations)            │
│   ├── divisi_admins                 │
│   ├── mentors                       │
│   └── sekolahs                      │
└─────────────────────────────────────┘
```

---

## 🔗 Data Flow

### Create Flow (Menambah Data)
```
User Input (Form)
    ↓
Livewire Property Binding (wire:model)
    ↓
Validation (Setting.php)
    ↓
Model::create() (Eloquent)
    ↓
Database INSERT
    ↓
Success Notification
    ↓
Auto Refresh View
```

### Read Flow (Melihat Data)
```
User Click "Lihat Detail"
    ↓
openModal() Method
    ↓
Set $showModal = true
    ↓
render() Method
    ↓
Model::with()->latest()->get()
    ↓
Database SELECT with JOIN
    ↓
Display Table in Modal
```

### Update Flow (Edit Data)
```
User Click "Edit" Button
    ↓
editMethod($id)
    ↓
Model::findOrFail($id)
    ↓
Load Data to Properties
    ↓
Form Auto-Filled
    ↓
User Modify & Submit
    ↓
saveMethod() (with $editId)
    ↓
Model::update()
    ↓
Database UPDATE
    ↓
Success & Refresh
```

### Delete Flow (Hapus Data)
```
User Click "Hapus" Button
    ↓
Confirmation Dialog (wire:confirm)
    ↓
deleteMethod($id)
    ↓
Model::find($id)->delete()
    ↓
Database DELETE (CASCADE if needed)
    ↓
Success & Refresh
```

---

## 🎯 Quick Navigation

### Need to modify UI?
→ `resources/views/livewire/setting.blade.php`

### Need to change logic?
→ `app/Livewire/Setting.php`

### Need to modify database?
→ `database/migrations/2026_01_23_*.php`

### Need sample data?
→ `database/seeders/*Seeder.php`

### Need model relationships?
→ `app/Models/DivisiAdmin.php` (hasMany)
→ `app/Models/Mentor.php` (belongsTo)

### Need documentation?
→ Start with `QUICK_START.md`
→ Then `SETTING_MODULE_README.md`

---

## ✅ File Checklist

Use this checklist when modifying the system:

- [ ] `app/Models/*.php` - Model changes
- [ ] `app/Livewire/Setting.php` - Component logic
- [ ] `resources/views/livewire/setting.blade.php` - UI changes
- [ ] `database/migrations/*.php` - Database structure
- [ ] `database/seeders/*.php` - Sample data
- [ ] Documentation files - Keep updated

---

## 🔐 Important Files (Don't Delete!)

### Critical Backend Files
- ✅ `app/Livewire/Setting.php`
- ✅ `app/Models/DivisiAdmin.php`
- ✅ `app/Models/Mentor.php`
- ✅ `app/Models/Sekolah.php`

### Critical Migration Files
- ✅ `database/migrations/*_create_divisi_admins_table.php`
- ✅ `database/migrations/*_create_sekolahs_table.php`
- ✅ `database/migrations/*_create_mentors_table.php`

### Critical View Files
- ✅ `resources/views/livewire/setting.blade.php`

---

## 📝 Notes

1. **Naming Convention:**
   - Models: PascalCase (DivisiAdmin, Mentor)
   - Tables: snake_case_plural (divisi_admins, mentors)
   - Methods: camelCase (saveDivisi, openModal)
   - Properties: camelCase ($namaDivisi, $showModal)

2. **File Organization:**
   - Models in `/app/Models/`
   - Livewire in `/app/Livewire/`
   - Views in `/resources/views/livewire/`
   - Migrations in `/database/migrations/`

3. **Documentation Location:**
   - All markdown files in root directory
   - Easy to find and reference
   - Version controlled with Git

---

**Last Updated:** 23 Januari 2026  
**Version:** 1.0.0  
**Total Files:** 20 files (13 new, 4 modified)  
**Total Lines:** ~3,650 lines of code + documentation