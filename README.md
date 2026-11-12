# YKS - Keliling Sentul

Platform website pemesanan dan katalog wisata petualangan alam di **Sentul, Bogor**, Jawa Barat. Menyediakan layanan reservasi paket trekking curug, petualangan fun offroad 4x4, gowes/cycling tour guide, serta camping keluarga bersama pemandu lokal (Local Tour Guide) berlisensi.

> **Catatan Rilis**: Sesuai kebutuhan tahap awal, versi ini berfokus 100% pada **Role Pengunjung (User)**. Role Admin akan dikembangkan pada tahap berikutnya.

---

## Fitur Utama (Role User / Pengunjung)

1. **Beranda & Eksplorasi Interaktif**:
   - Filter paket instan berdasarkan kategori (*Trekking Curug*, *Fun Offroad 4x4*, *Cycling/Gowes*, *Camping & Outbound*).
   - Badge tingkat kesulitan jalur (*Mudah/Pemula*, *Sedang*, *Menantang*).
   - Ringkasan statistik & testimoni nyata wisatawan.
   - FAQ interaktif seputar keamanan anak/lansia, titik kumpul, dan perlengkapan.
2. **Katalog & Detail Paket Wisata**:
   - Informasi lengkap jarak (km), durasi trip, dan titik kumpul (Meeting Point).
   - Jadwal & rencana perjalanan jam demi jam (*Itinerary*).
   - Rincian fasilitas yang termasuk (*Include*) dan tidak termasuk (*Exclude*).
3. **Formulir Pemesanan (Booking)**:
   - Pemilihan tanggal trip fleksibel & counter jumlah peserta.
   - Perhitungan estimasi total harga secara *real-time* di sisi browser.
   - Catatan request khusus (misal bawa anak, request jam kumpul, dsb).
4. **Instruksi & Konfirmasi Pembayaran**:
   - Pilihan metode: **Transfer Bank BCA**, **Bank Mandiri**, dan **QRIS Digital** instan.
   - Tombol *One-Click Copy* nomor rekening.
   - Form upload bukti transfer / struk pembayaran (JPG, PNG, WEBP, PDF).
5. **Pelacak Status Booking (Order Tracker)**:
   - Pencarian pesanan menggunakan **Kode Booking** (contoh: `YKS-20261027-A8C2`) atau Nomor WhatsApp.
   - Menampilkan status booking (*Belum Bayar*, *Menunggu Verifikasi*, *Lunas*).
   - Tombol integrasi langsung ke WhatsApp Admin & Guide lapangan.
6. **Autentikasi & Akun Pengguna**:
   - Registrasi & Login simpel bagi pengguna yang ingin menyimpan riwayat trip.
   - Halaman **Riwayat Pesanan** untuk melihat daftar trip yang pernah dipesan.

---

## Struktur Folder

```text
YKS-keliling_sentul/
├── includes/
│   ├── config.php          # Konfigurasi aplikasi & database (Auto-detect SQLite / MySQL)
│   ├── functions.php       # Fungsi format rupiah, tanggal Indo, badge, flash alerts
│   ├── session.php         # Manajemen sesi & autentikasi pengguna
│   ├── header.php          # Komponen navbar responsif & top bar kontak
│   └── footer.php          # Footer, quick links & floating button WhatsApp
├── public/
│   ├── index.php           # Halaman utama (Hero, Filter, Testimoni, FAQ)
│   ├── paket.php           # Katalog lengkap paket wisata
│   ├── paket-detail.php    # Detail rute, itinerary, fasilitas & meeting point
│   ├── booking.php         # Form reservasi paket & kalkulasi harga
│   ├── pembayaran.php      # Halaman rekening bayar & upload bukti transfer
│   ├── status-pesanan.php  # Cek status pesanan & kode booking
│   ├── riwayat.php         # Riwayat pesanan untuk akun pengguna
│   ├── login.php               # Masuk akun user
│   ├── register.php        # Pendaftaran akun user baru
│   ├── logout.php          # Keluar sesi
│   ├── uploads/            # Direktori penyimpanan bukti transfer
│   └── assets/
│       ├── css/style.css   # Custom CSS modern (Nature theme: Emerald, Slate, Amber)
│       └── js/main.js      # Filter kategori, accordion FAQ, kalkulator booking
├── sql/
│   └── init_db.sql         # Skema database & data awal untuk MySQL / XAMPP
└── README.md
```

---

## Cara Menjalankan Aplikasi

### Opsi 1: Paling Cepat (PHP Built-in Server — Zero Config)
Proyek ini dilengkapi konfigurasi database otomatis (*auto-seed SQLite*). Anda bisa langsung menjalankannya tanpa perlu setting database manual:

```bash

cd /home/man/WEB/YKS-keliling_sentul
php -S localhost:8000 -t public
```
Buka browser di: **`http://localhost:8000`**

### Opsi 2: Menggunakan XAMPP / MariaDB / Apache
1. Pastikan Apache & MySQL di XAMPP menyala.
2. Buka **phpMyAdmin** (`http://localhost/phpmyadmin`).
3. Buat database baru bernama `yks_sentul`.
4. Import file SQL: [`sql/init_db.sql`](sql/init_db.sql).
5. Atur koneksi di file [`includes/config.php`](includes/config.php) bila password database Anda berbeda.
