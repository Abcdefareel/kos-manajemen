# huni — Sistem Manajemen Kos

Aplikasi web Laravel untuk Admin dan Superadmin dalam mengelola kamar, penghuni, dan booking kos. MVP memakai Blade dan sesi web; belum mencakup REST API, pembayaran selain DP booking, kuitansi, ekspor laporan, check-in/out, dashboard, atau pengelolaan akun.

## Persyaratan

- PHP 8.2 atau lebih baru dengan ekstensi `pdo_pgsql`
- Composer
- PostgreSQL
- Node.js/npm untuk membangun asset Tailwind/Vite

## Menyiapkan proyek

1. Salin `.env.example` menjadi `.env`, lalu isi konfigurasi PostgreSQL dan `APP_KEY`:

   ```sh
   cp .env.example .env
   composer install
   php artisan key:generate
   npm install
   npm run build
   ```

2. Buat database PostgreSQL `kos_manajemen`. Atur `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di `.env`.
3. Tetapkan `SUPERADMIN_EMAIL`, `SUPERADMIN_PASSWORD`, `ADMIN_EMAIL`, dan `ADMIN_PASSWORD` di `.env` sebelum menjalankan seeder. Di environment lokal/testing, bila password tidak diisi, seeder memakai kredensial pengembangan `superadmin@example.test` / `GantiPasswordSuperadmin123!` dan `admin@example.test` / `GantiPasswordAdmin123!`. Ganti password tersebut sebelum aplikasi dipakai sungguhan. Seeder tidak menyediakan fallback kredensial untuk production.
4. Jalankan migrasi dan seeder:

   ```sh
   php artisan migrate --seed
   ```

5. Jalankan aplikasi:

   ```sh
   php artisan serve
   ```

Selama pengembangan front-end, jalankan `npm run dev` di terminal terpisah agar Vite memuat ulang asset.

Buka `http://127.0.0.1:8000/login`. KTP disimpan pada disk privat Laravel, bukan folder publik, dan hanya dapat diunduh setelah login.

## Fitur MVP

- Login/logout berbasis sesi dan pembatasan akses role admin/superadmin.
- CRUD kamar, dengan status kamar yang diubah hanya oleh proses booking.
- CRUD penghuni dan unggah foto KTP privat.
- Buat, ubah, cari, filter, pagination, dan batalkan booking. Pembuatan, perubahan kamar, dan pembatalan menjaga konsistensi status kamar dalam transaksi database.
- Kamar dan penghuni yang memiliki riwayat booking tidak dapat dihapus.

## Pengujian

Suite pengujian memakai SQLite in-memory agar tidak mengubah database pengembangan:

```sh
php artisan test
```
