# 🗂️ Entity Relationship Diagram (ERD)

## Sistem Absensi PKL - Modul Setting

---

## 📐 Visual ERD - ASCII Art

```
╔═══════════════════════════════════════════════════════════════════╗
║                    ENTITY RELATIONSHIP DIAGRAM                     ║
║                         MODUL SETTING                              ║
╚═══════════════════════════════════════════════════════════════════╝


┌─────────────────────────────────┐
│      DIVISI_ADMINS              │
├─────────────────────────────────┤
│ 🔑 id (PK)                      │
│ 📝 nama_divisi (VARCHAR)        │
│ 📄 deskripsi (TEXT)             │
│ 🕐 created_at (TIMESTAMP)       │
│ 🕐 updated_at (TIMESTAMP)       │
└─────────────────────────────────┘
              │
              │ 1
              │
              │ has many
              │
              │ *
              ▼
┌─────────────────────────────────┐
│         MENTORS                 │
├─────────────────────────────────┤
│ 🔑 id (PK)                      │
│ 👤 nama_mentor (VARCHAR)        │
│ 📧 email (VARCHAR)              │
│ 📞 no_telepon (VARCHAR)         │
│ 🔗 divisi_id (FK) ───────────┐  │
│ 💼 keahlian (TEXT)           │  │
│ 🕐 created_at (TIMESTAMP)    │  │
│ 🕐 updated_at (TIMESTAMP)    │  │
└──────────────────────────────┼──┘
                               │
                               │ belongs to
                               │
                               └──────────────┐
                                              │
                                              ▲


┌─────────────────────────────────┐
│         SEKOLAHS                │
├─────────────────────────────────┤
│ 🔑 id (PK)                      │
│ 🏫 nama_sekolah (VARCHAR)       │
│ 📍 alamat (TEXT)                │
│ 📞 no_telepon (VARCHAR)         │
│ 🕐 created_at (TIMESTAMP)       │
│ 🕐 updated_at (TIMESTAMP)       │
└─────────────────────────────────┘
   (Standalone - No Relations)
```

---

## 🔗 Relationship Details

### 1. DIVISI_ADMINS ──< MENTORS (One to Many)

**Relasi:** Satu divisi dapat memiliki banyak mentor

```
divisi_admins (1) ──────< (∞) mentors
     id                      divisi_id
```

**Karakteristik:**
- **Cardinality:** One-to-Many (1:N)
- **Foreign Key:** `mentors.divisi_id` → `divisi_admins.id`
- **On Delete:** CASCADE (hapus divisi → hapus semua mentor terkait)
- **On Update:** CASCADE

**Contoh:**
```
IT Support Divisi (id: 1)
  ├─ Budi Santoso (mentor)
  ├─ Doni Hermawan (mentor)
  └─ ...

Frontend Development (id: 2)
  ├─ Siti Nurhaliza (mentor)
  ├─ Rina Kusuma (mentor)
  └─ ...
```

---

## 📊 Detailed Table Structures

### 🟦 DIVISI_ADMINS

| Column       | Type         | Constraints              | Description                    |
|--------------|--------------|--------------------------|--------------------------------|
| id           | BIGINT       | PRIMARY KEY, AUTO_INC    | Unique identifier              |
| nama_divisi  | VARCHAR(255) | NOT NULL                 | Nama divisi/departemen         |
| deskripsi    | TEXT         | NULLABLE                 | Deskripsi lengkap divisi       |
| created_at   | TIMESTAMP    | NULLABLE                 | Waktu record dibuat            |
| updated_at   | TIMESTAMP    | NULLABLE                 | Waktu record terakhir diupdate |

**Indexes:**
- PRIMARY: `id`

**Sample Data:**
```
id | nama_divisi          | deskripsi
---+----------------------+----------------------------------------
1  | IT Support           | Divisi infrastruktur TI
2  | Frontend Development | Divisi pengembangan UI
3  | Backend Development  | Divisi pengembangan API & Database
```

---

### 🟩 MENTORS

| Column        | Type         | Constraints              | Description                    |
|---------------|--------------|--------------------------|--------------------------------|
| id            | BIGINT       | PRIMARY KEY, AUTO_INC    | Unique identifier              |
| nama_mentor   | VARCHAR(255) | NOT NULL                 | Nama lengkap mentor            |
| email         | VARCHAR(255) | NULLABLE                 | Email mentor                   |
| no_telepon    | VARCHAR(255) | NULLABLE                 | Nomor telepon mentor           |
| divisi_id     | BIGINT       | FOREIGN KEY, NOT NULL    | ID divisi mentor               |
| keahlian      | TEXT         | NULLABLE                 | Keahlian/expertise mentor      |
| created_at    | TIMESTAMP    | NULLABLE                 | Waktu record dibuat            |
| updated_at    | TIMESTAMP    | NULLABLE                 | Waktu record terakhir diupdate |

**Indexes:**
- PRIMARY: `id`
- FOREIGN: `divisi_id` → `divisi_admins.id`

**Constraints:**
- `FOREIGN KEY (divisi_id) REFERENCES divisi_admins(id) ON DELETE CASCADE`

**Sample Data:**
```
id | nama_mentor    | email                  | divisi_id | keahlian
---+----------------+------------------------+-----------+------------------
1  | Budi Santoso   | budi@company.com       | 1         | Network Admin
2  | Siti Nurhaliza | siti@company.com       | 2         | React, Vue.js
3  | Ahmad Zaki     | ahmad@company.com      | 3         | Laravel, Node.js
```

---

### 🟨 SEKOLAHS

| Column        | Type         | Constraints              | Description                    |
|---------------|--------------|--------------------------|--------------------------------|
| id            | BIGINT       | PRIMARY KEY, AUTO_INC    | Unique identifier              |
| nama_sekolah  | VARCHAR(255) | NOT NULL                 | Nama sekolah                   |
| alamat        | TEXT         | NULLABLE                 | Alamat lengkap sekolah         |
| no_telepon    | VARCHAR(255) | NULLABLE                 | Nomor telepon sekolah          |
| created_at    | TIMESTAMP    | NULLABLE                 | Waktu record dibuat            |
| updated_at    | TIMESTAMP    | NULLABLE                 | Waktu record terakhir diupdate |

**Indexes:**
- PRIMARY: `id`

**Relationships:** NONE (Standalone table)

**Sample Data:**
```
id | nama_sekolah          | alamat                      | no_telepon
---+-----------------------+-----------------------------+-------------
1  | SMK Negeri 1 Jakarta  | Jl. Budi Utomo No. 7        | 021-3456789
2  | SMK Negeri 2 Bandung  | Jl. Ciliwung No. 4          | 022-7654321
```

---

## 🎨 ERD dengan Notasi Crow's Foot

```
                    ┌─────────────────┐
                    │  divisi_admins  │
                    ├─────────────────┤
                    │ • id            │
                    │   nama_divisi   │
                    │   deskripsi     │
                    │   timestamps    │
                    └────────┬────────┘
                             │
                             │ 1
                             │
                             ○<
                             │
                             │ *
                             │
                    ┌────────┴────────┐
                    │     mentors     │
                    ├─────────────────┤
                    │ • id            │
                    │   nama_mentor   │
                    │   email         │
                    │   no_telepon    │
                    │ ◊ divisi_id     │
                    │   keahlian      │
                    │   timestamps    │
                    └─────────────────┘


                    ┌─────────────────┐
                    │    sekolahs     │
                    ├─────────────────┤
                    │ • id            │
                    │   nama_sekolah  │
                    │   alamat        │
                    │   no_telepon    │
                    │   timestamps    │
                    └─────────────────┘


Legend:
  • = Primary Key
  ◊ = Foreign Key
  ○< = One-to-Many relationship
  1 = One
  * = Many
```

---

## 🔄 Data Flow Diagram

```
┌──────────────┐
│    ADMIN     │
│   (User)     │
└──────┬───────┘
       │
       │ manage
       ▼
┌──────────────────────────────┐
│    SETTING MODULE            │
├──────────────────────────────┤
│  • Kelola Divisi             │
│  • Kelola Sekolah            │
│  • Kelola Mentor             │
└──────┬───────────────────────┘
       │
       ├────────────────┬────────────────┐
       ▼                ▼                ▼
┌─────────────┐  ┌─────────────┐  ┌─────────────┐
│   DIVISI    │  │  SEKOLAH    │  │   MENTOR    │
│   ADMINS    │  │             │  │             │
└──────┬──────┘  └─────────────┘  └──────┬──────┘
       │                                  │
       │ provides                         │
       │ mentors                          │
       └──────────────────────────────────┘
              belongs to divisi
```

---

## 🧩 Integration Points

### Current State
```
┌─────────────────┐
│ divisi_admins   │ ◄──┐
└─────────────────┘    │
                       │
┌─────────────────┐    │
│    mentors      │ ───┘
└─────────────────┘

┌─────────────────┐
│   sekolahs      │ (standalone)
└─────────────────┘
```

### Future Integration (Recommendation)
```
┌─────────────────┐
│ divisi_admins   │ ◄──┬───┐
└─────────────────┘    │   │
                       │   │
┌─────────────────┐    │   │
│    mentors      │ ───┘   │
└────────┬────────┘        │
         │                 │
         │ guides          │
         ▼                 │
┌─────────────────┐        │
│     users       │ ───────┴─ assigned to divisi
│  (PKL Students) │ ───────┬─ from sekolah
└─────────────────┘        │
         │                 │
         │                 │
         └─────────────┐   │
                       ▼   ▼
                ┌─────────────────┐
                │   sekolahs      │
                └─────────────────┘
```

---

## 📋 Business Rules

### Rule 1: Divisi & Mentor Dependency
```
IF divisi dihapus
THEN semua mentor terkait juga dihapus (CASCADE)

Example:
DELETE divisi_admins WHERE id = 1;
→ Otomatis DELETE mentors WHERE divisi_id = 1;
```

### Rule 2: Mentor Must Have Divisi
```
INSERT INTO mentors REQUIRES divisi_id (NOT NULL)

Valid:
INSERT INTO mentors (nama_mentor, divisi_id) VALUES ('Budi', 1);

Invalid:
INSERT INTO mentors (nama_mentor) VALUES ('Budi');
❌ Error: divisi_id cannot be null
```

### Rule 3: Sekolah Independence
```
Sekolah table is standalone
→ Dapat dihapus tanpa mempengaruhi tabel lain
→ Tidak memiliki foreign key constraint
```

---

## 🔍 SQL Queries Examples

### Query 1: Get Mentors with Divisi Name
```sql
SELECT 
    m.nama_mentor,
    m.email,
    d.nama_divisi,
    m.keahlian
FROM mentors m
INNER JOIN divisi_admins d ON m.divisi_id = d.id;
```

### Query 2: Count Mentors per Divisi
```sql
SELECT 
    d.nama_divisi,
    COUNT(m.id) as jumlah_mentor
FROM divisi_admins d
LEFT JOIN mentors m ON d.id = m.divisi_id
GROUP BY d.id, d.nama_divisi;
```

### Query 3: Find Divisi Without Mentors
```sql
SELECT d.*
FROM divisi_admins d
LEFT JOIN mentors m ON d.id = m.divisi_id
WHERE m.id IS NULL;
```

### Query 4: Get All Schools
```sql
SELECT * FROM sekolahs
ORDER BY nama_sekolah ASC;
```

---

## 🎯 Database Normalization

**Current Normalization Level:** 3NF (Third Normal Form)

### Why 3NF?

✅ **1NF** - Atomic values (no repeating groups)
- Setiap kolom hanya menyimpan satu nilai
- Tidak ada array atau list dalam field

✅ **2NF** - No partial dependencies
- Semua non-key attributes fully dependent on primary key

✅ **3NF** - No transitive dependencies
- Non-key attributes tidak bergantung pada non-key attributes lain

---

**Dibuat:** 23 Januari 2026  
**Format:** Markdown with ASCII Diagrams  
**Tool:** Manual ASCII Art