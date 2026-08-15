# Absensi Magang

Sistem absensi peserta magang berbasis web untuk mengelola kehadiran, izin/sakit, dan laporan rekap peserta magang dalam sebuah instansi/perusahaan. Dibangun dengan Laravel 12, Blade, Tailwind CSS, dan Alpine.js — dilengkapi absensi berbasis **geolocation (radius kantor)** dan **selfie verification** yang dapat diaktifkan/nonaktifkan tanpa mengubah kode.

---

## Daftar Isi

- [Fitur Utama](#fitur-utama)
- [Role & Hak Akses](#role--hak-akses)
- [Teknologi](#teknologi)
- [Struktur Database](#struktur-database)
- [Instalasi](#instalasi)
- [Konfigurasi Fitur Geolocation & Selfie](#konfigurasi-fitur-geolocation--selfie)
- [Akun Demo](#akun-demo)
- [Struktur Folder](#struktur-folder)
- [Alur Aplikasi](#alur-aplikasi)
- [Alur Teknis Check-in/Check-out dengan Lokasi & Selfie](#alur-teknis-check-incheck-out-dengan-lokasi--selfie)
- [Testing](#testing)
- [Keamanan](#keamanan)
- [Catatan Keamanan Sebelum Production](#catatan-keamanan-sebelum-production)
- [Known Limitations](#known-limitations)
- [Known Issues & Improvement](#known-issues--improvement)
- [Fitur Opsional (Belum Aktif)](#fitur-opsional-belum-aktif)
- [Troubleshooting](#troubleshooting)
- [Lisensi](#lisensi)

---

## Fitur Utama

- **Autentikasi & Role** — Login dengan redirect otomatis sesuai role (Admin/Mentor/Intern), dibangun di atas Laravel Breeze + Spatie Permission.
- **Manajemen Data Master** — CRUD Peserta Magang, Mentor, Periode Magang, dan Jadwal Kerja (dengan penanda jadwal aktif).
- **Absensi Check-in/Check-out** — Deteksi otomatis status Hadir/Terlambat berdasarkan jadwal kerja & toleransi keterlambatan, dengan pencegahan absensi ganda (dilindungi row-locking untuk mencegah race condition).
- **Absensi Berbasis Lokasi (Geolocation)** — *(opsional, dapat diaktifkan)* Check-in/out hanya berhasil jika peserta berada dalam radius kantor yang ditentukan admin. Jarak dihitung ulang di backend (Haversine formula) — tidak pernah mempercayai keputusan dari browser.
- **Selfie Verification** — *(opsional, mengikuti flag lokasi)* Peserta wajib mengambil foto selfie langsung dari kamera perangkat (bukan upload file) sebelum absensi tersimpan. Foto disimpan di disk privat, hanya dapat diakses pihak berwenang.
- **Manajemen Lokasi Kantor** — Admin dapat menambah/mengedit/mengaktifkan titik lokasi & radius absensi, termasuk tombol "Gunakan Lokasi Saya Saat Ini" untuk mengisi koordinat otomatis.
- **Riwayat & Koreksi Absensi** — Riwayat absensi per peserta dengan filter bulan/status; admin dapat mengoreksi data absensi langsung dari tabel.
- **Pengajuan & Approval Izin** — Peserta mengajukan izin/sakit dengan upload bukti (disimpan di disk privat), mentor/admin dapat approve/reject dengan wajib menyertakan alasan saat menolak.
- **Laporan & Export Excel** — Laporan absensi dengan filter rentang tanggal/peserta/mentor/status, termasuk detail lokasi (koordinat, akurasi, jarak, status validasi), dapat diekspor ke Excel dengan filter yang sama persis dengan tampilan layar.
- **Dashboard Per Role** — Ringkasan kehadiran hari ini/bulan ini yang disesuaikan cakupan data masing-masing role.

## Role & Hak Akses

| Role | Hak Akses |
|---|---|
| **Admin** | Akses penuh: kelola user, peserta, mentor, periode, jadwal kerja, **lokasi absensi**, seluruh absensi (termasuk koreksi & lihat foto/lokasi), seluruh pengajuan izin, laporan & export |
| **Mentor** | Melihat & memantau peserta bimbingannya, melihat absensi peserta bimbingan termasuk foto/lokasi (read-only), approve/reject izin peserta bimbingan |
| **Intern** | Check-in/check-out (dengan lokasi & selfie jika diaktifkan), melihat riwayat & rekap absensi sendiri, mengajukan izin, melihat status pengajuan izin sendiri |

Setiap peserta/mentor **hanya** dapat mengakses data miliknya sendiri (atau bimbingannya, untuk mentor) — ditegakkan di level backend lewat Laravel Policy (`AttendancePolicy`, `LeaveRequestPolicy`, `InternPolicy`), bukan hanya disembunyikan di UI. Ini juga berlaku untuk akses foto selfie — endpoint penyajian foto memanggil Policy yang sama.

## Teknologi

- **Backend**: Laravel 12.66, PHP 8.2
- **Frontend**: Blade, Tailwind CSS, Alpine.js (bawaan Breeze) — tanpa framework frontend tambahan
- **Autentikasi**: Laravel Breeze
- **Role & Permission**: spatie/laravel-permission
- **Export Excel**: maatwebsite/excel v3
- **Database**: MySQL
- **Testing**: PHPUnit (Feature & Unit Test) dengan SQLite in-memory
- **Browser API**: Geolocation API (`navigator.geolocation`), MediaDevices API (`getUserMedia`) — native browser, tanpa library pihak ketiga

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

OfficeSetting (global, satu lokasi aktif berlaku untuk semua intern)
 id, name, latitude, longitude, radius_meter, is_active

Intern
 ├── belongsTo User, Mentor, InternshipPeriod
 ├── hasMany Attendance
 └── hasMany LeaveRequest

Attendance (unique per intern_id + tanggal)
 └── belongsTo Intern
 kolom: tanggal, waktu_masuk, waktu_pulang, status, keterlambatan, catatan,
        latitude, longitude, foto_check_in, foto_check_out,
        accuracy_check_in, accuracy_check_out,
        distance_check_in, distance_check_out,
        location_status_check_in, location_status_check_out

LeaveRequest
 ├── belongsTo Intern
 └── belongsTo User (sebagai approver)
```

**Catatan skema lokasi**: `latitude`/`longitude` pada `attendances` adalah **satu pasang kolom** yang dipakai baik untuk check-in maupun check-out (diisi ulang saat check-out jika data baru dikirim). Ini artinya laporan Excel kolom "Latitude/Longitude Check-out" akan menampilkan nilai yang sama dengan check-in kecuali lokasi berubah. Lihat [Known Limitations](#known-limitations).

## Instalasi

### Requirement

- PHP ^8.2 dengan ekstensi `zip`, `gd`, `pdo_mysql` aktif
- Composer 2.x
- Node.js ^20.19 atau ^22.12
- MySQL
- Browser modern (Chrome/Edge/Safari terbaru) untuk fitur geolocation & kamera

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

ATTENDANCE_REQUIRE_LOCATION=false
ATTENDANCE_MAX_ACCURACY=100
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

---

## Konfigurasi Fitur Geolocation & Selfie

Fitur ini dikendalikan penuh lewat `config/attendance.php` (dibaca dari `.env`), **tanpa mengubah kode apa pun**.

```env
ATTENDANCE_REQUIRE_LOCATION=false   # true untuk mewajibkan lokasi + selfie
ATTENDANCE_MAX_ACCURACY=100         # radius akurasi GPS maksimum yang diterima (meter)
```

| Kondisi | Perilaku |
|---|---|
| `ATTENDANCE_REQUIRE_LOCATION=false` (default) | Check-in/out berfungsi seperti absensi biasa — tombol satu klik, tanpa lokasi maupun kamera. Field lokasi/foto di database tetap kosong. |
| `ATTENDANCE_REQUIRE_LOCATION=true` | Intern **wajib** mengizinkan lokasi dan mengambil selfie sebelum absensi tersimpan. Check-in/out ditolak jika di luar radius kantor aktif atau akurasi GPS terlalu buruk. |

Setelah mengubah nilai ini, jalankan:
```bash
php artisan config:clear
```

### Mengatur Lokasi Kantor (wajib diisi sebelum mengaktifkan flag `true`)

1. Login sebagai **admin**.
2. Buka menu **Lokasi Absensi** di sidebar.
3. Klik **+ Tambah Lokasi**, isi nama lokasi, radius (meter), lalu klik **📍 Gunakan Lokasi Saya Saat Ini** (browser akan meminta izin lokasi) atau isi latitude/longitude manual.
4. Centang **Jadikan lokasi aktif**, simpan.

Hanya **satu** lokasi yang boleh aktif dalam satu waktu — menyimpan lokasi baru sebagai aktif akan otomatis menonaktifkan lokasi lain (pola yang sama dengan Jadwal Kerja).

### Persyaratan Browser (HTTPS)

Geolocation API dan Camera API (`getUserMedia`) **hanya berjalan di secure context**:
- ✅ `https://...`
- ✅ `http://localhost` atau `http://127.0.0.1` (persis, untuk development)
- ❌ `http://192.168.x.x:8000` atau IP lain — **akan gagal** dengan pesan "Fitur lokasi hanya dapat digunakan pada koneksi HTTPS."

Untuk menguji dari HP di jaringan lokal saat development, gunakan tunnel HTTPS sementara, contoh dengan `ngrok`:
```bash
ngrok http 8000
```
Akses URL HTTPS yang diberikan dari browser HP.

---

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
│   ├── Controllers/
│   │   ├── Admin/              # DashboardController, OfficeSettingController
│   │   ├── Mentor/              # DashboardController, InternController
│   │   ├── Intern/              # DashboardController
│   │   └── ...                  # AttendanceController, LeaveRequestController, FileController, ReportController
│   ├── Requests/                # Form Request untuk semua validasi input
│   └── Middleware/
├── Models/                      # User, Profile, Intern, Mentor, InternshipPeriod,
│                                 # WorkSchedule, OfficeSetting, Attendance, LeaveRequest
├── Policies/                    # AttendancePolicy, LeaveRequestPolicy, InternPolicy
├── Services/
│   ├── AttendanceService.php    # Business logic check-in/out, status & keterlambatan
│   └── LocationService.php      # Haversine distance, validasi accuracy & radius
└── Exports/
    └── AttendanceExport.php

config/
└── attendance.php                # Flag require_location & max_accuracy_meters

database/
├── factories/
├── migrations/
└── seeders/

resources/
├── js/
│   ├── attendance-checkin.js         # Modul geolocation murni
│   └── components/
│       ├── attendance-flow.js        # State machine check-in/out
│       ├── camera-capture.js         # Kontrol kamera (getUserMedia)
│       └── office-location-picker.js # Reuse geolocation untuk form admin
└── views/
    ├── layouts/, components/   # Layout & komponen Blade reusable (termasuk camera-capture.blade.php)
    ├── admin/, mentor/, intern/
    │   └── office-settings/    # CRUD lokasi absensi (admin)
    └── attendance/, leave-requests/    # View lintas role (diproteksi Policy)

tests/
├── Unit/
│   └── LocationServiceTest.php
└── Feature/
    ├── Auth/LoginTest.php
    ├── AttendanceTest.php
    ├── AttendanceLocationTest.php
    ├── AttendanceExportTest.php
    ├── AuthorizationTest.php
    ├── LeaveRequestTest.php
    └── OfficeSettingTest.php
```

## Alur Aplikasi

```
Login → redirect otomatis sesuai role
  │
  ├── Admin   → Dashboard → Kelola Data Master → Lokasi Absensi → Absensi (+koreksi, lihat foto)
  │                        → Approval Izin → Laporan → Export Excel
  ├── Mentor  → Dashboard → Pantau Peserta Bimbingan → Absensi (read-only, lihat foto) → Approval Izin
  └── Intern  → Dashboard → Check In (lokasi + selfie jika aktif) → (bekerja)
                          → Check Out (lokasi + selfie jika aktif) → Riwayat Absensi
                          └→ Ajukan Izin → Lihat Status Pengajuan
```

**Logic penentuan status kehadiran** (di `AttendanceService`, tidak berubah oleh fitur lokasi/selfie):
- Jam kerja & toleransi diambil dari `WorkSchedule` yang sedang `is_active = true`.
- Check-in dalam batas toleransi → status `hadir`.
- Check-in melewati batas toleransi → status `terlambat`, dengan jumlah menit keterlambatan tercatat.
- Satu peserta hanya dapat check-in **satu kali per hari**, ditegakkan di level aplikasi (row locking + transaction) maupun di level database (`unique(intern_id, tanggal)`).

## Alur Teknis Check-in/Check-out dengan Lokasi & Selfie

Saat `ATTENDANCE_REQUIRE_LOCATION=true`:

```
1. Intern klik "Check In"
2. Browser meminta izin lokasi (navigator.geolocation)
   → ditolak/timeout/tidak didukung → pesan error jelas + tombol "Coba Lagi"
   → berhasil → tampil "📍 Lokasi ditemukan (akurasi ±Xm)"
3. Kamera depan terbuka otomatis (getUserMedia)
   → izin ditolak/kamera tidak ada → pesan error jelas
4. Intern klik "Ambil Foto" → preview foto tampil
   → "Ambil Ulang" untuk foto ulang, atau "Gunakan Foto" untuk lanjut
5. Klik tombol "Check In" final → data dikirim via fetch()+FormData
   (latitude, longitude, accuracy, file foto — bukan base64)
6. Backend (CheckInRequest):
   - validasi tipe data, MIME foto, ukuran max 5MB
7. Backend (LocationService):
   - cek accuracy ≤ ATTENDANCE_MAX_ACCURACY
   - hitung ulang jarak ke OfficeSetting aktif (Haversine) — TIDAK percaya
     kesimpulan dari frontend
   - jika di luar radius → ditolak dengan pesan jarak & radius
8. Backend (AttendanceController → AttendanceService):
   - simpan foto ke disk privat: attendance-photos/{intern_id}/{tanggal}/
   - transaction + row lock → cegah double check-in (logic lama, tidak diubah)
   - simpan attendance beserta accuracy/distance/location_status
9. Redirect dengan pesan sukses, halaman reload menampilkan status terbaru
```

Saat `ATTENDANCE_REQUIRE_LOCATION=false` (default): langkah 2 tetap meminta lokasi (untuk dicatat jika tersedia), tapi langkah 3-4 (kamera) **dilewati sepenuhnya**, dan validasi lokasi bersifat opsional — absensi tetap berhasil tanpa lokasi/foto, identik dengan versi sebelum fitur ini ada.

## Testing

```bash
php artisan test
```

**59 test** — seluruh fitur dari MVP awal hingga geolocation/selfie tercakup:

| Grup | Jumlah | Cakupan |
|---|---|---|
| Auth (bawaan Breeze + custom) | 15 | Login, registrasi, reset password, verifikasi email |
| `AttendanceTest` | 5 | Check-in, cegah double check-in, check-out, cegah check-out tanpa check-in, cegah akses lintas peserta |
| `AttendanceLocationTest` | 6 | Check-in tanpa lokasi (flag mati), wajib lokasi (flag aktif), sukses dalam radius, ditolak di luar radius, ditolak akurasi buruk, check-out menyimpan data lokasi |
| `LocationServiceTest` | 8 | Haversine distance, validasi accuracy, validasi radius, office belum dikonfigurasi |
| `OfficeSettingTest` | 3 | Admin dapat ubah lokasi, role lain ditolak, satu lokasi aktif otomatis menonaktifkan lainnya |
| `AttendanceExportTest` | 3 | Admin dapat export, kolom lokasi ada di Excel, role lain ditolak |
| `AuthorizationTest` | 3 | Akses admin, penolakan akses lintas role |
| `LeaveRequestTest` | 5 | Pengajuan izin, approve, reject dengan alasan wajib, cegah mentor lintas bimbingan |
| `ProfileTest` (bawaan) | 5 | Update profil, hapus akun |

Test memakai SQLite in-memory, **terisolasi penuh** dari `.env` lokal — `phpunit.xml` mengunci `ATTENDANCE_REQUIRE_LOCATION=false` dan `ATTENDANCE_MAX_ACCURACY=100` secara eksplisit, sehingga perubahan `.env` untuk keperluan development manual (misalnya menaikkan `ATTENDANCE_MAX_ACCURACY` untuk testing browser di desktop) **tidak memengaruhi** hasil test otomatis.

---

## Keamanan

- **CSRF** — seluruh form native memakai `@csrf`; request `fetch()` (flow check-in/out) mengirim token lewat header `X-CSRF-TOKEN`.
- **Authorization** — middleware `role`/`permission` di level route, `Policy` (`AttendancePolicy`, `LeaveRequestPolicy`, `InternPolicy`) di level data spesifik.
- **File upload** — divalidasi MIME sungguhan (bukan ekstensi), whitelist `jpg,jpeg,png,webp` (SVG diblokir eksplisit), maksimal 5MB, disimpan di disk **privat** dengan nama file acak (bukan nama asli dari client).
- **Foto selfie & bukti izin** — hanya dapat diakses lewat endpoint terproteksi Policy (`FileController`), bukan URL publik langsung.
- **Lokasi** — backend **selalu** menghitung ulang jarak (Haversine) dan menjadi source of truth; koordinat dari browser tidak pernah dipercaya mentah-mentah untuk keputusan valid/invalid.
- **Race condition** — `DB::transaction()` + `lockForUpdate()` mencegah double check-in meski dua request datang bersamaan; ditegakkan juga lewat constraint `UNIQUE(intern_id, tanggal)` di database.
- **Rate limiting** — endpoint check-in/check-out dibatasi `throttle:10,1` (maks 10 request/menit per user); route login memakai throttle bawaan Breeze.
- **Mass assignment** — `intern_id`, `status`, waktu absensi tidak pernah diambil dari input request; selalu dari user terautentikasi (`auth()->user()->intern`) atau `now()` di server.

## Catatan Keamanan Sebelum Production

- [ ] Ganti seluruh password akun demo, atau jangan jalankan seeder demo sama sekali.
- [ ] Konfigurasi `MAIL_MAILER` dengan SMTP asli (saat ini `log`, link reset password hanya tertulis di `storage/logs/laravel.log`).
- [ ] Set `APP_ENV=production` dan `APP_DEBUG=false` di `.env`.
- [ ] Pastikan HTTPS aktif di server production — **wajib** agar fitur geolocation/kamera berfungsi di luar `localhost`.
- [ ] Isi `OfficeSetting` dengan koordinat kantor sungguhan sebelum mengaktifkan `ATTENDANCE_REQUIRE_LOCATION=true`.
- [ ] Review ulang permission Spatie jika ada penambahan role/fitur baru.
- [ ] Pertimbangkan menambah activity log untuk aksi sensitif (perubahan role, hapus data, koreksi absensi) — belum ada di versi ini.

## Known Limitations

- **GPS spoofing** — Browser Geolocation API tidak menyediakan mekanisme kriptografis untuk membuktikan koordinat berasal dari sensor GPS asli. Perangkat root/jailbreak atau ekstensi tertentu berpotensi memalsukan koordinat. Ini keterbatasan platform browser, bukan kelemahan implementasi — backend sudah memvalidasi ulang semua data yang diterima secara ketat.
- **Selfie tanpa verifikasi wajah** — validasi hanya memastikan file adalah gambar yang sah (MIME, ukuran), tidak memverifikasi kecocokan wajah dengan identitas peserta. Verifikasi wajah otomatis di luar cakupan MVP ini.
- **Kolom latitude/longitude berbagi check-in & check-out** — `attendances` hanya punya satu pasang kolom lokasi. Jika check-out dilakukan di lokasi berbeda dari check-in dan data baru dikirim, kolom akan terupdate; jika tidak, nilai check-out sama dengan check-in di laporan Excel.
- **HTTPS wajib** — geolocation/kamera tidak akan berfungsi diakses lewat IP jaringan lokal (`http://192.168.x.x`) tanpa tunnel HTTPS.
- **Belum diuji di perangkat iOS Safari asli** — atribut `playsinline` dan `muted` pada elemen video sudah dipasang preventif (wajib untuk iOS), namun belum diverifikasi langsung di perangkat fisik.

## Known Issues & Improvement

- Route `show` untuk `InternshipPeriodController`/`WorkScheduleController`/`OfficeSettingController` dikecualikan (`->except(['show'])`) karena tidak ada halaman detail terpisah.
- Penandaan `WorkSchedule`/`OfficeSetting` aktif ditegakkan di level aplikasi, belum ada constraint database (partial unique index) untuk mencegah dua record aktif bersamaan dalam skenario concurrent request murni.
- Beberapa filter form (tanggal/status/dropdown) terduplikasi antar halaman (Intern, Absensi Admin, Laporan) — kandidat untuk diekstrak jadi komponen Blade bersama.
- Belum ada index database eksplisit pada kolom `attendances.tanggal`/`status` dan `leave_requests.status` — disarankan ditambah seiring pertumbuhan data.
- Belum ada Feature Test untuk skenario edit `OfficeSettingController::update()` (baru `store()` yang diuji eksplisit).
- Belum ada pengujian E2E otomatis (Playwright/Cypress) untuk alur kamera+geolocation — saat ini murni manual testing, sesuai keputusan menunda framework E2E sampai MVP stabil.

## Fitur Opsional (Belum Aktif)

| Fitur | Status |
|---|---|
| Geolocation & Selfie saat check-in/out | ✅ **Sudah diimplementasikan**, dikontrol via `ATTENDANCE_REQUIRE_LOCATION` (lihat [Konfigurasi](#konfigurasi-fitur-geolocation--selfie)) |
| QR Code check-in | Belum ada persiapan struktur, perlu desain terpisah |
| Notifikasi realtime (email/in-app) | Belum diimplementasikan, disengaja ditunda sesuai prioritas MVP |
| Integrasi WhatsApp | Di luar cakupan MVP, memerlukan gateway pihak ketiga |
| Verifikasi wajah otomatis pada selfie | Di luar cakupan MVP, memerlukan library/API face-recognition pihak ketiga |
| Geofencing berbasis WiFi (BSSID) | Belum diimplementasikan, dipertimbangkan sebagai lapisan tambahan jika GPS spoofing terbukti jadi masalah nyata |

## Troubleshooting

| Gejala | Kemungkinan Penyebab | Solusi |
|---|---|---|
| "Fitur lokasi hanya dapat digunakan pada koneksi HTTPS" | Akses lewat IP selain `localhost`/`127.0.0.1` | Gunakan `http://127.0.0.1:8000`, atau tunnel HTTPS (`ngrok`) untuk akses dari perangkat lain |
| "Lokasi belum cukup akurat" terus muncul di desktop | Desktop/laptop tanpa GPS asli biasanya akurasi >100m (triangulasi WiFi/IP) | Naikkan sementara `ATTENDANCE_MAX_ACCURACY` di `.env` untuk testing, atau uji dari HP dengan GPS aktif |
| "Anda berada di luar area absensi" padahal sudah di kantor | Koordinat `OfficeSetting` aktif tidak sesuai lokasi sebenarnya | Login admin → Lokasi Absensi → edit koordinat lewat tombol "Gunakan Lokasi Saya Saat Ini" saat berada di lokasi kantor |
| Kamera tidak terbuka / "Kamera tidak dapat digunakan pada perangkat ini" | Bukan secure context, atau perangkat tanpa kamera, atau permission ditolak | Pastikan akses via HTTPS/localhost; cek permission kamera di pengaturan browser |
| Tombol "Ambil Foto" tidak muncul meski video terlihat | Cache browser lama | Hard refresh (`Ctrl+Shift+R`) setelah `npm run build` |
| Foto gagal tersimpan meski preview tampil normal | Biasanya masalah build frontend belum ter-update | Jalankan ulang `npm run build`, hard refresh |
| Redirect loop / `ERR_TOO_MANY_REDIRECTS` saat buka `/login` | Route `auth.php` ter-nest di dalam middleware `auth` | Pastikan `require __DIR__.'/auth.php';` berada **di luar** `Route::middleware('auth')->group()` di `routes/web.php` |
| Jam check-in tidak sesuai waktu lokal | `config('app.timezone')` tidak membaca `.env` | Pastikan `config/app.php` memakai `env('APP_TIMEZONE', 'UTC')`, bukan `'UTC'` hardcode, lalu `php artisan config:clear` |

---

## Lisensi

Internal — dikembangkan untuk kebutuhan pengelolaan absensi magang instansi.
