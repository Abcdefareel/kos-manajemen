# Dokumentasi Sistem Manajemen Kos

Dokumen ini menjelaskan alur, struktur, aturan data, dan cara menjalankan MVP Sistem Manajemen Kos. Aplikasi dibuat dengan Laravel 12, PHP 8.2+, Blade, dan PostgreSQL untuk penggunaan melalui browser.

## 1. Tujuan dan ruang lingkup

MVP membantu Admin dan Superadmin mencatat kamar, penghuni, dan pemesanan kamar pada satu lokasi kos. Penghuni tidak memiliki akun dan tidak dapat masuk ke aplikasi.

### Fitur yang tersedia

- Login dan logout dengan sesi web.
- CRUD kamar.
- CRUD penghuni dan unggah foto KTP privat.
- Membuat, melihat, mencari, memfilter, mengubah, dan membatalkan booking.
- Mencatat uang muka (DP) pada booking.
- Mengubah status kamar melalui proses booking secara transaksional.

### Belum termasuk

REST API atau token, pembayaran sewa selain DP booking, kuitansi, ekspor laporan, check-in/check-out, perpanjangan sewa, dashboard, pengelolaan akun Admin, pengaturan kos, dan log aktivitas.

## 2. Teknologi dan prasyarat

- PHP 8.2 atau lebih baru.
- Ekstensi PHP `pdo_pgsql` untuk koneksi PostgreSQL.
- Composer.
- PostgreSQL.
- Node.js/npm hanya jika ingin membangun asset standar Laravel.

Periksa PHP CLI yang dipakai oleh Artisan, bukan hanya PHP yang digunakan web server:

```bash
php -v
php --ini
php -m
```

Pastikan daftar modul menampilkan `pdo_pgsql`. Pada instalasi XAMPP Windows, aktifkan `extension=pdo_pgsql` di `php.ini` yang ditunjukkan oleh `php --ini`, lalu buka terminal baru dan periksa kembali `php -m`.

## 3. Menyiapkan proyek

### 3.1 Memasang dependency dan membuat environment

Di PowerShell pada direktori proyek:

```powershell
Copy-Item .env.example .env
composer install
php artisan key:generate
```

Jika `.env` sudah ada dan berisi konfigurasi lokal, jangan menimpanya dengan `.env.example`.

### 3.2 Menyiapkan PostgreSQL

Buat database, misalnya `kos_manajemen`, lalu sesuaikan nilai berikut di `.env` dengan instalasi PostgreSQL setempat:

```dotenv
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=kos_manajemen
DB_USERNAME=nama_role_postgresql
DB_PASSWORD=password_role_postgresql
```

Port PostgreSQL dapat berbeda, misalnya `8001`. Gunakan port tempat server PostgreSQL benar-benar mendengarkan. Pastikan role PostgreSQL memiliki izin untuk terhubung ke database dan membuat tabel. Jangan memasukkan kredensial database ke Git atau membagikannya.

Isi juga kredensial akun aplikasi melalui `.env`:

```dotenv
SUPERADMIN_EMAIL=superadmin@example.test
SUPERADMIN_PASSWORD=password-superadmin-yang-kuat
ADMIN_EMAIL=admin@example.test
ADMIN_PASSWORD=password-admin-yang-kuat
```

Gunakan email dan password milik tim sendiri, terutama sebelum aplikasi dipakai di lingkungan bersama atau production. Seeder tidak menyediakan fallback email/password di production dan akan gagal jika variabel wajib belum diisi.

### 3.3 Menjalankan migrasi dan seeder

```powershell
php artisan config:clear
php artisan migrate --seed
```

Laravel membuat tabel migrasi saat perintah `migrate` pertama kali dijalankan. Karena itu, `php artisan migrate:status` pada database baru dapat melaporkan `Migration table not found`; jalankan `php artisan migrate --seed` untuk membuat tabel tersebut sekaligus menerapkan migrasi.

Seeder membuat atau memperbarui dua akun berdasarkan email dari environment, serta membuat 20 kamar dummy jika nomor kamar tersebut belum ada. Menjalankan seeder lagi tidak menggandakan akun atau kamar dummy. Password akun di-hash sebelum disimpan.

### 3.4 Menjalankan aplikasi

```powershell
php artisan serve
```

Buka `http://127.0.0.1:8000/login`. Untuk menghapus cache konfigurasi setelah perubahan `.env`:

```powershell
php artisan config:clear
```

## 4. Peran dan autentikasi

Hanya akun dengan role `admin` atau `superadmin` yang dapat memakai fitur operasional. Kedua role saat ini mendapat akses yang sama untuk kamar, penghuni, booking, dan foto KTP. Fitur khusus Superadmin seperti kelola akun belum termasuk MVP.

Alur autentikasi:

1. Pengguna membuka halaman yang dilindungi middleware `auth`.
2. Jika belum login, Laravel mengarahkannya ke halaman `/login`.
3. Pengguna mengirim email dan password melalui formulir. Percobaan login dibatasi maksimal lima kali per menit.
4. Jika kredensial valid, sesi diregenerasi dan pengguna diarahkan ke halaman yang diminta atau daftar booking.
5. Middleware `role:admin,superadmin` memeriksa role di sisi server. Role lain menerima respons HTTP 403.
6. Logout mengakhiri autentikasi, menginvalidasi sesi, dan meregenerasi token CSRF.

Halaman login tersedia bagi guest. Halaman kamar, penghuni, booking, logout, dan foto KTP dilindungi autentikasi dan role.

## 5. Alur penggunaan aplikasi

### 5.1 Menyiapkan kamar dan penghuni

Sebelum booking, buat setidaknya satu kamar kosong dan satu profil penghuni:

1. Buka menu **Kamar**, lalu pilih **Tambah kamar**.
2. Isi nomor kamar, tipe, harga per bulan, dan fasilitas.
3. Status baru dimulai sebagai `kosong`. Form kamar tidak dapat digunakan untuk mengubah status.
4. Buka menu **Penghuni**, lalu pilih **Tambah penghuni**.
5. Isi nama, nomor HP, nomor identitas, dan tanggal masuk/keluar bila diketahui.
6. Foto KTP bersifat opsional. Jika diunggah, aplikasi menerima gambar maksimal 5 MB.

Pencarian daftar penghuni dilakukan berdasarkan nama. Kamar dan penghuni memiliki pagination 15 baris per halaman.

### 5.2 Membuat booking

1. Buka menu **Booking** dan pilih **Buat booking**.
2. Pilih kamar berstatus `kosong`.
3. Pilih penghuni penanggung jawab.
4. Isi tanggal mulai, durasi dalam bulan, dan DP. DP wajib berupa bilangan bulat rupiah dan boleh bernilai nol.
5. Simpan. Aplikasi memeriksa lagi status kamar di server, lalu dalam satu transaksi membuat booking berstatus `dipesan` dan mengubah kamar menjadi `dipesan`.

Jika kamar sudah tidak kosong saat penyimpanan, booking ditolak dan pengguna menerima pesan validasi. Pemeriksaan dilakukan ulang saat simpan agar dua permintaan bersamaan tidak sama-sama memesan kamar yang sama.

### 5.3 Melihat dan mencari booking

Daftar booking diurutkan dari tanggal mulai terbaru dan menampilkan 15 data per halaman. Filter yang tersedia:

- Status: `dipesan` atau `dibatalkan`.
- Tanggal mulai dari dan sampai.
- Kata kunci nama penghuni atau nomor kamar.

Rentang tanggal divalidasi agar tanggal akhir tidak mendahului tanggal awal. Pencarian menggunakan `LOWER(...) LIKE` sehingga pencocokan nama dan nomor kamar tidak membedakan huruf besar-kecil, termasuk pada PostgreSQL yang operator `LIKE`-nya peka huruf besar-kecil.

### 5.4 Mengubah booking

Booking aktif (`dipesan`) dapat diubah. Form memungkinkan perubahan kamar, penghuni penanggung jawab, tanggal mulai, durasi, dan DP.

Jika kamar berubah, aplikasi mengunci data booking dan kamar terkait dalam transaksi. Kamar lama menjadi `kosong`, kamar baru menjadi `dipesan`, lalu booking diperbarui. Kamar baru harus masih kosong. Booking berstatus `dibatalkan` tidak dapat diubah.

### 5.5 Membatalkan booking

Pembatalan tersedia pada booking aktif dan meminta konfirmasi di browser. Aplikasi mempertahankan record booking dengan status `dibatalkan` agar riwayat tidak hilang. Dalam transaksi yang sama, kamar dikembalikan menjadi `kosong` jika statusnya masih `dipesan`.

Tidak ada penghapusan booking melalui antarmuka. Kamar atau penghuni yang memiliki riwayat booking juga tidak dapat dihapus. Aturan foreign key `restrictOnDelete` melindungi relasi booking pada tingkat database.

### 5.6 Mengakses foto KTP

Foto disimpan pada disk privat Laravel, di bawah `storage/app/private`, bukan di direktori publik. Aplikasi tidak membuat URL publik untuk foto. Rute preview mengirim gambar sebagai konten inline agar browser menampilkannya tanpa mengunduh terlebih dahulu; tombol unduh terpisah tetap tersedia. Kedua rute hanya dapat digunakan Admin/Superadmin yang telah login. Respons preview menggunakan cache privat/non-cache dan `X-Content-Type-Options: nosniff`.

Jika foto diganti, file lama dihapus setelah data penghuni berhasil diperbarui. Jika profil penghuni tanpa booking dihapus, file foto terkait juga dihapus.

## 6. Status dan aturan bisnis

### Status kamar

| Nilai database | Arti |
|---|---|
| `kosong` | Dapat dipilih untuk booking |
| `dipesan` | Sedang memiliki booking aktif |
| `terisi` | Disediakan untuk alur operasional berikutnya; check-in belum dikerjakan |
| `perbaikan` | Tidak tersedia karena perbaikan |

Di MVP, perubahan status kamar dilakukan melalui logika booking. Form CRUD kamar tidak menerima status dari pengguna.

### Status booking

| Nilai database | Arti |
|---|---|
| `dipesan` | Booking aktif |
| `dibatalkan` | Booking dibatalkan, riwayat tetap disimpan |

MVP memakai status kamar sebagai kunci ketersediaan: satu kamar yang sudah `dipesan` tidak dapat menerima booking aktif lain, meskipun tanggal booking kedua berbeda. Pemeriksaan tumpang-tindih tanggal dan pemesanan berulang setelah periode sewa belum menjadi alur terpisah di MVP.

### Aturan data lain

- Nomor kamar unik.
- Nomor identitas penghuni unik.
- Harga per bulan dan DP disimpan sebagai bilangan bulat rupiah non-negatif (`unsignedBigInteger`).
- Nomor HP dan nomor identitas disimpan sebagai string agar format/awalan nol tetap terjaga.
- Tanggal masuk dan keluar penghuni boleh kosong. Jika keduanya diisi, tanggal keluar tidak boleh sebelum tanggal masuk.
- Durasi booking wajib bilangan bulat antara 1 dan 120 bulan.
- Penghapusan kamar/penghuni dengan relasi booking diblokir.

## 7. Struktur data dan relasi

| Tabel | Kolom utama | Relasi/keterangan |
|---|---|---|
| `users` | `id`, `name`, `email`, `role`, `password`, timestamps | Untuk akun Admin dan Superadmin. Password tersimpan sebagai hash. |
| `rooms` | `id`, `room_number`, `type`, `price_per_month`, `facilities`, `status`, timestamps | `room_number` unik. Satu kamar memiliki banyak record booking sepanjang waktu. |
| `tenants` | `id`, `name`, `phone`, `id_number`, `id_photo_path`, `entry_date`, `exit_date`, timestamps | `id_number` unik. Satu penghuni dapat tercatat di banyak booking sepanjang waktu. |
| `bookings` | `id`, `room_id`, `tenant_id`, `start_date`, `duration_months`, `down_payment`, `status`, timestamps | Foreign key ke `rooms` dan `tenants`; penghapusan parent ditolak bila masih direferensikan. |

Relasi Eloquent:

- `Room hasMany Booking`; `Booking belongsTo Room`.
- `Tenant hasMany Booking`; `Booking belongsTo Tenant`.

Tabel `payments` belum dibuat karena pencatatan pembayaran selain DP merupakan fitur fase berikutnya.

## 8. Rute web utama

| Method | URL | Kegunaan | Akses |
|---|---|---|---|
| GET | `/` | Mengarahkan pengguna terautentikasi ke booking | Login |
| GET, POST | `/login` | Form dan proses login | Guest |
| POST | `/logout` | Mengakhiri sesi | Admin/Superadmin |
| GET, POST, PUT/PATCH, DELETE | `/rooms` | Daftar, tambah, ubah, dan hapus kamar | Admin/Superadmin |
| GET, POST, PUT/PATCH, DELETE | `/tenants` | Daftar, tambah, ubah, dan hapus penghuni | Admin/Superadmin |
| GET | `/tenants/{tenant}/id-photo` | Mengunduh foto KTP privat | Admin/Superadmin |
| GET, POST | `/bookings` | Daftar/filter dan membuat booking | Admin/Superadmin |
| GET | `/bookings/create` | Form booking | Admin/Superadmin |
| GET, PUT | `/bookings/{booking}/edit` | Form dan proses perubahan booking | Admin/Superadmin |
| PATCH | `/bookings/{booking}/cancel` | Membatalkan booking | Admin/Superadmin |

Rute tersebut adalah rute web Laravel berbasis sesi dan CSRF, bukan REST API untuk klien mobile.

## 9. Lokasi kode penting

| Lokasi | Tanggung jawab |
|---|---|
| `routes/web.php` | Rute browser dan middleware akses |
| `app/Http/Controllers/Auth/AuthenticatedSessionController.php` | Login dan logout |
| `app/Http/Middleware/EnsureUserHasRole.php` | Pemeriksaan role |
| `app/Http/Controllers/RoomController.php` | CRUD kamar dan proteksi penghapusan |
| `app/Http/Controllers/TenantController.php` | CRUD penghuni, validasi, unggah/hapus foto |
| `app/Http/Controllers/TenantIdPhotoController.php` | Unduhan foto KTP privat |
| `app/Http/Controllers/BookingController.php` | Pencarian, filter, pagination, transaksi booking |
| `app/Models/` | Model dan relasi Eloquent |
| `database/migrations/` | Skema dan foreign key |
| `database/seeders/DatabaseSeeder.php` | Akun awal dan 20 kamar dummy |
| `resources/views/` | Layout Blade dan halaman login/kamar/penghuni/booking |
| `tests/Feature/MvpWorkflowTest.php` | Tes alur autentikasi, booking, KTP, dan seeder |

### Sistem desain antarmuka

Antarmuka Blade menggunakan Tailwind CSS v4 melalui Vite. Brand aplikasi adalah **huni**. Token desain didefinisikan satu kali pada `resources/css/app.css`; komponen UI reusable berada di `resources/views/components/`. Layout utama `resources/views/layouts/app.blade.php` menyediakan navigasi, pencarian, profil, dan notifikasi. Jalankan `npm install` lalu `npm run build` untuk membangun asset produksi atau `npm run dev` selama pengembangan.

## 10. Pengujian

Jalankan seluruh tes:

```powershell
php artisan test
```

Suite pengujian disetel menggunakan SQLite in-memory agar tidak mengubah database PostgreSQL pengembangan. Tes mencakup akses login dan role, logout, CRUD kamar dan batasan penghapusan, alur perubahan status booking, pencegahan booking ganda, filter/pencarian booking, penyimpanan privat foto KTP, tanggal penghuni opsional, serta seeder idempoten.

Tes SQLite membantu memeriksa logika aplikasi, tetapi tidak menggantikan pengujian integrasi pada PostgreSQL sebelum rilis.

## 11. Pemecahan masalah

### `could not find driver` untuk koneksi `pgsql`

Aktifkan ekstensi `pdo_pgsql` pada `php.ini` milik PHP CLI. Temukan file konfigurasi dengan `php --ini`, lalu mulai terminal baru dan pastikan `php -m` menampilkan `pdo_pgsql`.

### `password authentication failed`

Periksa `DB_USERNAME`, `DB_PASSWORD`, `DB_HOST`, dan `DB_PORT` di `.env`. Pastikan kredensial adalah milik role PostgreSQL yang dapat mengakses database.

### `Migration table not found`

Perintah `migrate:status` hanya memeriksa riwayat dan dapat menampilkan pesan ini pada database kosong. Jalankan:

```powershell
php artisan config:clear
php artisan migrate --seed
```

### Perubahan `.env` tidak terlihat oleh Laravel

Jalankan `php artisan config:clear`, lalu ulangi perintah Artisan. Jangan membagikan isi `.env` karena file tersebut dapat memuat rahasia aplikasi.

## 12. Pengembangan lanjutan

Sebelum mengembangkan fase berikutnya, cakupan yang belum tersedia perlu direncanakan terpisah: alur check-in/check-out dan transisi status `terisi`, pengelolaan akun Admin, pembayaran berulang, kuitansi, ekspor, dashboard, dan API berversi. Jangan menganggap bahwa tabel atau endpoint fitur-fitur tersebut telah tersedia pada MVP.
