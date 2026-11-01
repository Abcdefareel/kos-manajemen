# PRD Sistem Manajemen Kos

Oct 10, 2026 · @Abcdefareel

## 1. Ringkasan dan Latar Belakang

Sistem ini menggantikan pencatatan manual ibu kos dengan aplikasi web yang mencatat kamar, penghuni, booking, dan pembayaran untuk satu kos berisi sekitar 20 kamar.

Saat ini ibu kos mengelola data penghuni dan kamar secara manual. Pencatatan manual membuat data sulit dicari, status kamar sulit dipantau, dan riwayat pembayaran sulit ditelusuri (asumsi, perlu dikonfirmasi ke ibu kos).

| Item | Keterangan |
| --- | --- |
| Nama produk | Sistem Manajemen Kos (versi web admin) |
| Pengguna | Ibu kos (Admin) dan Superadmin |
| Skala | 1 lokasi, sekitar 20 kamar |
| Pengguna akhir lain | Penghuni tidak punya akses; data mereka dicatat oleh Admin |
| Fokus MVP | CRUD booking pesanan kamar |
| Rencana lanjutan | Data dikirim lewat REST API untuk perangkat mobile |

## 2. Tujuan dan Metrik Keberhasilan

Tujuan utama: Admin dapat mencatat dan mencari data kamar, penghuni, dan booking tanpa buku catatan manual.

| Tujuan | Metrik (usulan target) |
| --- | --- |
| Pencatatan terpusat | 100% kamar (20 unit) dan penghuni aktif tercatat di sistem |
| Pencatatan booking cepat | Satu booking baru dapat dibuat dalam kurang dari 2 menit |
| Status kamar akurat | Status kamar (kosong, terisi, dipesan) sesuai kondisi nyata |
| Bukti pembayaran | Setiap pembayaran yang dicatat dapat dicetak sebagai kuitansi |
| Pelaporan | Data booking dan pembayaran dapat diekspor ke Excel dalam sekali klik |

Angka target bersifat usulan dan dapat disesuaikan setelah uji coba dengan ibu kos.

## 3. Pengguna dan Role

Sistem punya dua role yang bisa login: Admin (ibu kos) dan Superadmin. Penghuni tidak punya akun dan tidak mengakses sistem.

| Role | Siapa | Tanggung jawab | Hak akses utama |
| --- | --- | --- | --- |
| Admin | Ibu kos | Mengelola operasional harian kos | CRUD kamar, penghuni, booking, pembayaran; cetak kuitansi; ekspor Excel |
| Superadmin | Pengelola sistem | Mengelola akun dan pengaturan sistem | Semua hak Admin, ditambah CRUD akun Admin, reset password, pengaturan kos (nama, alamat, format nomor kuitansi), dan melihat log aktivitas |

Peran Superadmin ditetapkan sebagai pengelola sistem yang mengatur akun, pengaturan kos, dan pengawasan; operasional harian tetap di tangan Admin. Pada MVP, akun Superadmin dibuat lewat seeder database, sehingga layar kelola akun baru dibuat di fase 3.

**Persona Admin.** Ibu kos mengelola sekitar 20 kamar sendiri, memakai HP atau laptop, dan butuh tampilan sederhana dengan sedikit langkah per tugas (asumsi).

## 4. Ruang Lingkup

MVP berfokus pada CRUD booking pesanan kamar; fitur lain masuk fase berikutnya, dan beberapa fitur sengaja tidak dikerjakan.

| Fase | Isi | Status |
| --- | --- | --- |
| MVP | Login Admin dan Superadmin; CRUD booking pesanan; data kamar dan penghuni sebagai data pendukung booking | Dikerjakan sekarang |
| Fase 2 | Transaksi sewa (check-in, perpanjang, check-out, deposit); pencatatan pembayaran manual; cetak kuitansi atau invoice; ekspor laporan ke Excel | Setelah MVP |
| Fase 3 | REST API untuk aplikasi mobile; kelola akun Admin oleh Superadmin | Rencana lanjutan |
| Di luar lingkup | Akses penghuni, payment gateway, notifikasi otomatis, keluhan fasilitas, pencatatan pengeluaran, pengumuman, multi-cabang | Tidak dikerjakan |

Data kamar dan penghuni masuk MVP karena sebuah booking tidak bisa dibuat tanpa memilih kamar dan penghuni.

## 5. Kebutuhan Fungsional

Sistem punya 16 kebutuhan fungsional: 7 untuk MVP, 7 untuk fase 2, dan 2 untuk fase 3.

| ID | Modul | User story | Kriteria penerimaan | Fase |
| --- | --- | --- | --- | --- |
| FR-01 | Autentikasi | Sebagai Admin atau Superadmin, saya login dengan email dan password agar hanya pengguna sah yang masuk. | Login gagal menampilkan pesan error; logout mengakhiri sesi; halaman admin tidak bisa dibuka tanpa login. | MVP |
| FR-02 | Kamar | Sebagai Admin, saya mengelola data kamar agar status dan harga kamar selalu terbaru. | Nomor kamar unik; atribut: tipe, harga per bulan, fasilitas, status (kosong, dipesan, terisi, perbaikan); bisa tambah, ubah, hapus, dan lihat. | MVP |
| FR-03 | Penghuni | Sebagai Admin, saya mengelola data penghuni agar identitas dan kontak mudah dicari. | Atribut: nama, nomor HP, nomor identitas, foto KTP, tanggal masuk dan keluar; ada pencarian berdasarkan nama; satu kamar punya satu profil penghuni sebagai penanggung jawab, walau dihuni lebih dari satu orang. | MVP |
| FR-04 | Booking | Sebagai Admin, saya membuat booking agar kamar tercatat sudah dipesan penghuni. | Hanya kamar berstatus kosong yang bisa dipilih; booking menyimpan penghuni, kamar, tanggal mulai, durasi dalam bulan, dan uang muka (DP) yang wajib diisi; status kamar menjadi dipesan. | MVP |
| FR-05 | Booking | Sebagai Admin, saya melihat dan mencari daftar booking agar mudah menemukan pesanan. | Daftar bisa difilter berdasarkan status dan tanggal serta dicari dengan nama penghuni atau nomor kamar. | MVP |
| FR-06 | Booking | Sebagai Admin, saya mengubah booking agar bisa memperbaiki data yang salah. | Tanggal, durasi, atau kamar dapat diubah selama booking belum check-in; perubahan kamar memperbarui status kedua kamar. | MVP |
| FR-07 | Booking | Sebagai Admin, saya membatalkan atau menghapus booking agar kamar bisa dipakai lagi. | Ada dialog konfirmasi; setelah dibatalkan, status kamar kembali kosong. | MVP |
| FR-08 | Dashboard | Sebagai Admin, saya melihat ringkasan kamar agar tahu berapa yang kosong. | Menampilkan jumlah kamar kosong, dipesan, terisi, dan perbaikan. | Fase 2 |
| FR-09 | Transaksi sewa | Sebagai Admin, saya mencatat check-in agar booking menjadi sewa aktif. | Booking berubah menjadi sewa aktif; kamar menjadi terisi; deposit dapat dicatat. | Fase 2 |
| FR-10 | Transaksi sewa | Sebagai Admin, saya memperpanjang sewa agar penghuni bisa menetap lebih lama. | Tanggal akhir sewa bertambah; riwayat perpanjangan tersimpan. | Fase 2 |
| FR-11 | Transaksi sewa | Sebagai Admin, saya mencatat check-out agar kamar tersedia kembali. | Status kamar kembali kosong; pengembalian deposit dicatat. | Fase 2 |
| FR-12 | Pembayaran | Sebagai Admin, saya mencatat pembayaran manual agar riwayat bayar jelas. | Catat tanggal, jumlah, metode (tunai atau transfer), dan periode yang dibayar; tidak ada integrasi payment gateway. | Fase 2 |
| FR-13 | Kuitansi | Sebagai Admin, saya mencetak kuitansi atau invoice agar penghuni punya bukti bayar. | Kuitansi memuat nomor unik, nama penghuni, kamar, jumlah, tanggal; dapat dicetak atau disimpan sebagai PDF. | Fase 2 |
| FR-14 | Laporan | Sebagai Admin, saya mengekspor data ke Excel agar bisa diolah atau diarsipkan. | Ekspor booking dan pembayaran per rentang tanggal ke file .xlsx. | Fase 2 |
| FR-15 | Akun | Sebagai Superadmin, saya mengelola akun Admin agar akses terkontrol. | Tambah, nonaktifkan, dan reset password akun Admin; Superadmin juga bisa melihat log aktivitas. | Fase 3 |
| FR-16 | API | Sebagai pengembang mobile, saya memakai REST API agar data kos bisa diakses dari perangkat mobile. | Endpoint terautentikasi untuk kamar, penghuni, dan booking; respons berformat JSON. | Fase 3 |

## 6. Alur Bisnis Utama

Status kamar adalah inti alur: booking, check-in, dan check-out yang mengubahnya.

&#91;embedded content: status kamar · 3 status, 3 transisi plus pembatalan\]

Pembatalan mengembalikan kamar ke kosong; pembayaran manual dicatat selama kamar terisi dan kuitansi dicetak dari data pembayaran itu.

## 7. Kebutuhan Non-Fungsional

Sistem harus aman, mudah dipakai ibu kos, dan cukup ringan untuk skala satu kos dengan 20 kamar.

| Kategori | Kebutuhan |
| --- | --- |
| Keamanan | Password disimpan dalam bentuk hash; semua halaman admin wajib login; role dicek di sisi server; foto KTP hanya bisa diakses pengguna yang login |
| Kegunaan | Antarmuka berbahasa Indonesia; tampilan responsif agar nyaman di HP dan laptop; tugas utama (buat booking) selesai dalam 5 langkah atau kurang |
| Performa | Halaman daftar terbuka kurang dari 3 detik untuk data hingga ratusan booking |
| Ketersediaan | Dapat diakses kapan saja lewat browser; backup database berkala (usulan: harian) |
| Integritas data | Kamar tidak bisa dibooking ganda pada periode yang tumpang tindih; data yang terkait booking tidak bisa dihapus sembarangan |
| Kompatibilitas | Berjalan di browser modern (Chrome, Edge, Firefox) |
| Keterpeliharaan | Struktur kode terpisah (model, controller, view); API dibuat dengan versi (misalnya /api/v1) agar aman dikembangkan untuk mobile |

## 8. Model Data

Lima tabel menyimpan data kos; booking menghubungkan kamar dan penghuni, dan pembayaran menempel pada booking.

&#91;embedded content: model data · 5 tabel, 3 relasi\]

Tabel users hanya dipakai untuk login Admin dan Superadmin. Tabel payments baru dibuat di fase 2. Diagram ini bahan awal untuk ER-Diagram lengkap.

## 9. Rancangan API (Versi Awal)

Backend menyediakan REST API berformat JSON dengan prefiks /api/v1, dipakai web dan nanti aplikasi mobile. Daftar ini masih usulan.

| Metode | Endpoint | Fungsi | Role | Fase |
| --- | --- | --- | --- | --- |
| POST | /api/v1/login | Login dan mendapatkan token | Admin, Superadmin | MVP |
| POST | /api/v1/logout | Mengakhiri sesi | Admin, Superadmin | MVP |
| GET | /api/v1/rooms | Daftar kamar (filter status) | Admin, Superadmin | MVP |
| POST | /api/v1/rooms | Tambah kamar | Admin, Superadmin | MVP |
| PUT | /api/v1/rooms/{id} | Ubah kamar | Admin, Superadmin | MVP |
| DELETE | /api/v1/rooms/{id} | Hapus kamar | Admin, Superadmin | MVP |
| GET, POST, PUT, DELETE | /api/v1/tenants | CRUD penghuni | Admin, Superadmin | MVP |
| GET | /api/v1/bookings | Daftar booking (filter status, tanggal, kata kunci) | Admin, Superadmin | MVP |
| POST | /api/v1/bookings | Buat booking | Admin, Superadmin | MVP |
| PUT | /api/v1/bookings/{id} | Ubah booking | Admin, Superadmin | MVP |
| DELETE | /api/v1/bookings/{id} | Batalkan atau hapus booking | Admin, Superadmin | MVP |
| GET, POST | /api/v1/payments | Daftar dan catat pembayaran | Admin, Superadmin | Fase 2 |
| GET | /api/v1/payments/{id}/receipt | Unduh kuitansi PDF | Admin, Superadmin | Fase 2 |
| GET | /api/v1/reports/export | Ekspor Excel | Admin, Superadmin | Fase 2 |
| GET, POST, PUT | /api/v1/admins | Kelola akun Admin | Superadmin | Fase 3 |

Format respons seragam: kode status HTTP yang sesuai, isi data dalam JSON, dan pesan error yang jelas untuk validasi gagal.

## 10. Asumsi, Risiko, dan Pertanyaan Terbuka

Asumsi dan risiko di bawah ini sudah mengikuti keputusan terbaru; keempat pertanyaan terbuka sebelumnya sudah dijawab.

**Asumsi**

- Satu kamar punya satu profil penghuni sebagai penanggung jawab, meski dihuni lebih dari satu orang (misalnya keluarga).
- Sewa selalu bulanan; tidak ada sewa mingguan atau tahunan.
- Admin hanya satu orang (ibu kos); Superadmin adalah pengelola sistem.
- Tidak ada ketentuan format PRD dari dosen, sehingga struktur dokumen ini dipakai.

| Risiko | Dampak | Mitigasi |
| --- | --- | --- |
| Waktu pengumpulan hanya satu minggu | Fitur fase 2 tidak sempat dikerjakan | Batasi MVP pada CRUD booking, kamar, dan penghuni |
| Peran Superadmin bisa membengkak | Kebutuhan akun dan hak akses berubah | Batasi pada kelola akun, pengaturan kos, dan log aktivitas |
| Ibu kos tidak terbiasa memakai sistem | Data tidak terisi atau terlambat diisi | Antarmuka sederhana dan sesi pengenalan singkat |

**Keputusan atas pertanyaan terbuka**

- [x] Peran Superadmin: pengelola sistem (kelola akun Admin, pengaturan kos, log aktivitas); akun dibuat lewat seeder pada MVP.
- [x] Sewa selalu bulanan.
- [x] Booking memerlukan uang muka (DP) yang dicatat saat booking dibuat.
- [x] Satu kamar punya satu profil penghuni sebagai penanggung jawab, walau dihuni beberapa orang.

## 11. Rencana Rilis

Pengerjaan dibagi tiga tahap berurutan; tahap 1 mencakup seluruh MVP dan diselesaikan lebih dulu.

1. **Tahap 1, MVP:** login, CRUD kamar, CRUD penghuni, CRUD booking. Selesai bila semua kriteria FR-01 sampai FR-07 terpenuhi.
2. **Tahap 2:** dashboard, check-in, perpanjang, check-out, pembayaran manual, kuitansi, ekspor Excel (FR-08 sampai FR-14).
3. **Tahap 3:** kelola akun Admin dan REST API untuk aplikasi mobile (FR-15 dan FR-16).

Setiap tahap diuji bersama ibu kos atau dengan data contoh sebelum lanjut ke tahap berikutnya.
