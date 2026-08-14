# Absensi Magang

Sistem absensi peserta magang berbasis web untuk mengelola kehadiran, izin/sakit, dan laporan rekap peserta magang dalam sebuah instansi/perusahaan. Dibangun dengan Laravel 12, Blade, dan Tailwind CSS.

---

## Daftar Isi

- [Fitur Utama](#fitur-utama)
- [Role & Hak Akses](#role--hak-akses)
- [Teknologi](#teknologi)
- [Struktur Database](#struktur-database)
- [Instalasi](#instalasi)
- [Akun Demo](#akun-demo)
- [Struktur Folder](#struktur-folder)
- [Alur Aplikasi](#alur-aplikasi)
- [Testing](#testing)
- [Fitur Opsional (Belum Aktif)](#fitur-opsional-belum-aktif)
- [Catatan Keamanan Sebelum Production](#catatan-keamanan-sebelum-production)
- [Known Issues & Improvement](#known-issues--improvement)
- [Lisensi](#lisensi)

---

## Fitur Utama

- **Autentikasi & Role** — Login dengan redirect otomatis sesuai role (Admin/Mentor/Intern), dibangun di atas Laravel Breeze + Spatie Permission.
- **Manajemen Data Master** — CRUD Peserta Magang, Mentor, Periode Magang, dan Jadwal Kerja (dengan penanda jadwal aktif).
- **Absensi Check-in/Check-out** — Deteksi otomatis status Hadir/Terlambat berdasarkan jadwal kerja & toleransi keterlambatan, dengan pencegahan absensi ganda (dilindungi row-locking untuk mencegah race condition).
- **Riwayat & Koreksi Absensi** — Riwayat absensi per peserta dengan filter bulan/status; admin dapat mengoreksi data absensi langsung dari tabel.
- **Pengajuan & Approval Izin** — Peserta mengajukan izin/sakit dengan upload bukti (disimpan di disk privat, hanya dapat diakses pihak berwenang), mentor/admin dapat approve/reject dengan wajib menyertakan alasan saat menolak.
- **Laporan & Export Excel** — Laporan absensi dengan filter rentang tanggal/peserta/mentor/status, dapat diekspor ke Excel dengan filter yang sama persis dengan tampilan layar.
- **Dashboard Per Role** — Ringkasan kehadiran hari ini/bulan ini yang disesuaikan cakupan data masing-masing role.

## Role & Hak Akses

| Role | Hak Akses |
|---|---|
| **Admin** | Akses penuh: kelola user, peserta, mentor, periode, jadwal kerja, seluruh absensi (termasuk koreksi), seluruh pengajuan izin, laporan & export |
| **Mentor** | Melihat & memantau peserta bimbingannya, melihat absensi peserta bimbingan (read-only), approve/reject izin peserta bimbingan |
| **Intern** | Check-in/check-out, melihat riwayat & rekap absensi sendiri, mengajukan izin, melihat status pengajuan izin sendiri |

Setiap peserta/mentor **hanya** dapat mengakses data miliknya sendiri (atau bimbingannya, untuk mentor) — ditegakkan di level backend lewat Laravel Policy (`AttendancePolicy`, `LeaveRequestPolicy`, `InternPolicy`), bukan hanya disembunyikan di UI.

## Teknologi

- **Backend**: Laravel 12.66, PHP 8.2
- **Frontend**: Blade, Tailwind CSS, Alpine.js (bawaan Breeze)
- **Autentikasi**: Laravel Breeze
- **Role & Permission**: spatie/laravel-permission
- **Export Excel**: maatwebsite/excel v3
- **Database**: MySQL
- **Testing**: PHPUnit (Feature Test) dengan SQLite in-memory

## Struktur Database

```
users (Breeze + Spatie roles)
 ├── hasOne Profile
 ├── hasOne Mentor
 └── hasOne Intern

Mentor
 └── hasMany Intern

InternshipPeriod
 └── hasMany Intern

WorkSchedule (global, satu jadwal aktif berlaku untuk semua intern)

Intern
 ├── belongsTo User, Mentor, InternshipPeriod
 ├── hasMany Attendance
 └── hasMany LeaveRequest

Attendance (unique per intern_id + tanggal)
 └── belongsTo Intern

LeaveRequest
 ├── belongsTo Intern
 └── belongsTo User (sebagai approver)
```

Struktur lengkap migration ada di `database/migrations/`. Kolom `latitude`, `longitude`, `foto_check_in`, `foto_check_out` pada tabel `attendances` sudah disiapkan (nullable) untuk fitur geolocation & foto selfie di masa depan.

## Instalasi

### Requirement

- PHP ^8.2 dengan ekstensi `zip`, `gd`, `pdo_mysql` aktif
- Composer 2.x
- Node.js ^20.19 atau ^22.12
- MySQL

### Langkah

```bash
# Clone & masuk folder project
composer install
npm install

# Konfigurasi environment
cp .env.example .env
php artisan key:generate
```

Sesuaikan `.env`:
```env
APP_TIMEZONE=Asia/Jakarta
APP_LOCALE=id

DB_CONNECTION=mysql
DB_DATABASE=absensi_magang
DB_USERNAME=root
DB_PASSWORD=
```

Buat database MySQL:
```sql
CREATE DATABASE absensi_magang CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Migrasi & seed data awal:
```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

Jalankan aplikasi:
```bash
npm run build      # atau `npm run dev` untuk mode development
php artisan serve
```

Buka `http://localhost:8000`.

## Akun Demo

Password semua akun demo: `password`

| Role | Email |
|---|---|
| Admin | admin@example.com |
| Mentor | mentor@example.com, mentor2@example.com |
| Intern | intern@example.com, intern2@example.com – intern6@example.com |

> ⚠️ **Wajib diganti atau tidak dijalankan sama sekali di environment production.**

## Struktur Folder

```
app/
├── Http/
│   ├── Controllers/       # Admin/, Mentor/, Intern/ (dashboard) + controller lintas role
│   ├── Requests/          # Form Request untuk semua validasi input
│   └── Middleware/
├── Models/
├── Policies/               # AttendancePolicy, LeaveRequestPolicy, InternPolicy
├── Services/
│   └── AttendanceService.php   # Business logic check-in/out, penentuan status & keterlambatan
└── Exports/
    └── AttendanceExport.php

database/
├── factories/
├── migrations/
└── seeders/

resources/views/
├── layouts/, components/   # Layout & komponen Blade reusable
├── admin/, mentor/, intern/
└── attendance/, leave-requests/    # View lintas role (diproteksi Policy)

tests/Feature/
├── Auth/LoginTest.php
├── AttendanceTest.php
├── AuthorizationTest.php
└── LeaveRequestTest.php
```

## Alur Aplikasi

```
Login → redirect otomatis sesuai role
  │
  ├── Admin   → Dashboard → Kelola Data Master → Absensi (+koreksi) → Approval Izin → Laporan → Export Excel
  ├── Mentor  → Dashboard → Pantau Peserta Bimbingan → Absensi (read-only) → Approval Izin
  └── Intern  → Dashboard → Check In → (bekerja) → Check Out → Riwayat Absensi
                          └→ Ajukan Izin → Lihat Status Pengajuan
```

**Logic penentuan status kehadiran** (di `AttendanceService`):
- Jam kerja & toleransi diambil dari `WorkSchedule` yang sedang `is_active = true`.
- Check-in dalam batas toleransi → status `hadir`.
- Check-in melewati batas toleransi → status `terlambat`, dengan jumlah menit keterlambatan tercatat.
- Satu peserta hanya dapat check-in **satu kali per hari**, ditegakkan baik di level aplikasi (row locking + transaction) maupun di level database (`unique(intern_id, tanggal)`).

## Testing

```bash
php artisan test
```

Cakupan Feature Test (39 test, mencakup bawaan Breeze + custom):
- **Authentication** — login berhasil/gagal.
- **Attendance** — check-in, cegah double check-in, check-out, cegah check-out tanpa check-in, cegah akses absensi milik peserta lain.
- **Authorization** — akses admin ke halaman admin, penolakan akses lintas role (mentor/intern ke halaman admin).
- **Leave Request** — pengajuan izin dengan upload bukti, approve, reject dengan alasan wajib, cegah mentor approve izin di luar bimbingannya.

Test memakai SQLite in-memory (dikonfigurasi di `phpunit.xml`), terpisah sepenuhnya dari database development.

## Fitur Opsional (Belum Aktif)

Struktur sudah disiapkan sejak awal agar dapat diaktifkan tanpa migration tambahan:

| Fitur | Status |
|---|---|
| Geolocation saat check-in/out | Kolom `latitude`/`longitude` & validasi sudah siap, tinggal tambah `navigator.geolocation` di frontend |
| Foto selfie check-in/out | Kolom `foto_check_in`/`foto_check_out` & validasi upload sudah siap, tinggal tambah input kamera di form |
| QR Code check-in | Belum ada persiapan struktur, perlu desain terpisah |
| Notifikasi realtime (email/in-app) | Belum diimplementasikan, disengaja ditunda sesuai prioritas MVP |
| Integrasi WhatsApp | Di luar cakupan MVP, memerlukan gateway pihak ketiga |

## Catatan Keamanan Sebelum Production

- [ ] Ganti seluruh password akun demo, atau jangan jalankan seeder demo sama sekali.
- [ ] Konfigurasi `MAIL_MAILER` dengan SMTP asli (saat ini `log`, link reset password hanya tertulis di `storage/logs/laravel.log`).
- [ ] Set `APP_ENV=production` dan `APP_DEBUG=false` di `.env`.
- [ ] Pastikan middleware `throttle` masih aktif pada route login (bawaan Breeze) untuk mencegah brute-force.
- [ ] Review ulang permission Spatie jika ada penambahan role/fitur baru.
- [ ] Pertimbangkan menambah activity log untuk aksi sensitif (perubahan role, hapus data, koreksi absensi) — belum ada di versi ini.

## Known Issues & Improvement

- Route `show` untuk `InternshipPeriodController`/`WorkScheduleController` perlu dikecualikan (`->except(['show'])`) karena kedua controller tidak memiliki halaman detail terpisah.
- Penandaan `WorkSchedule` aktif ditegakkan di level aplikasi, belum ada constraint database (partial unique index) untuk mencegah dua jadwal aktif bersamaan dalam skenario concurrent request.
- Beberapa filter form (tanggal/status/dropdown) terduplikasi antar halaman (Intern, Absensi Admin, Laporan) — kandidat untuk diekstrak jadi komponen Blade bersama.
- Belum ada index database eksplisit pada kolom `attendances.tanggal`/`status` dan `leave_requests.status` — disarankan ditambah seiring pertumbuhan data.

## Lisensi

Internal — dikembangkan untuk kebutuhan pengelolaan absensi magang instansi.
