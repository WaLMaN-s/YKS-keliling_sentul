# YKS — Yuk Keliling Sentul (Laravel 12)

Aplikasi web reservasi, katalog paket, pelacakan status booking, dan ruang administrasi resmi **Yuk Keliling Sentul (YKS)** — layanan sewa motor trail & trabas Hambalang, wisata Jeep 4x4, dan trekking curug di kawasan **Sentul, Kabupaten Bogor, Jawa Barat**.

Dibangun menggunakan **Laravel 12** mengacu pada spesifikasi desain Figma [**`YKS_desain`**](https://www.figma.com/design/5WB0Bqa2deoGdpd8Y8QNMi/YKS_desain?node-id=0-1) (mencakup tampilan Desktop & Mobile Seluler).

---

## Daftar Paket Resmi Yuk Keliling Sentul

Seluruh data paket otomatis terisi melalui seeder (`database/seeders/YksSeeder.php`):

| No | Nama Paket | Kategori | Harga Resmi | Durasi & Jarak | Ketentuan & Fasilitas Utama (*Include*) |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **1** | **Paket 1 · Trabas Motor Trail 150 CC (CRF / WR / KLX)** | Sewa Motor Trail & Trabas | **Rp 700.000** / orang | `4 - 6 Jam` · `15 s/d 25 Km` | Minimal pemesanan 2 orang. Unit Trail 150 CC (Honda CRF / Yamaha WR / Kawasaki KLX), Guide/Leader, BBM sesuai rute, Helm + Sepatu Safety, Jersey Set (dipinjamkan), Jalur Trabas, Air Mineral 600ml, Dokumentasi (HP). |
| **2** | **Paket 2 · Trabas Motor Trail Kawasaki KLX 140 CC** | Sewa Motor Trail & Trabas | **Rp 1.300.000** / orang | `4 - 6 Jam` · `15 s/d 25 Km` | Minimal pemesanan 1 orang. Unit Kawasaki KLX 140 CC, Guide/Leader, BBM sesuai rute, Helm + Sepatu Safety, Jersey Set (dipinjamkan), Jalur Trabas, Air Mineral 600ml, Dokumentasi (HP). |
| **3** | **Paket 3 · Trabas Motor Trail 300 CC (Husqvarna / KTM / Sherco)** | Sewa Motor Trail & Trabas | **Rp 2.400.000** / orang | `4 - 6 Jam` · `15 s/d 25 Km` | Minimal pemesanan 1 orang. Unit Trail Enduro 300 CC (Husqvarna / KTM / Sherco), Guide/Leader, BBM sesuai rute, Helm + Sepatu Safety, Jersey Set (dipinjamkan), Jalur Trabas, Air Mineral 600ml, Dokumentasi (HP). |
| **4** | **Paket Trabas Bawa Motor Trail Pribadi** | Trabas Motor Pribadi | **Rp 200.000** / orang | `4 - 6 Jam` · `15 s/d 25 Km` | Khusus peserta yang membawa motor trail sendiri. Termasuk Guide/Leader lokal berpengalaman, Jalur Trabas 15–25 Km, Air Mineral 600ml, Dokumentasi (HP), & pendampingan jalur. |
| **5** | **Edisi Wisata Jeep 4x4 & Trekking Curug Sentul** | Wisata Jeep 4x4 & Trekking | **Rp 1.750.000** / unit | `8 s/d 10 Jam` · `Offroad & Curug` | 1 Unit Jeep berisi 3 s/d 4 orang, Driver Profesional (Akamsi), Tiket destinasi trekking & wisata jeep, Pilihan Curug Leuwi Asih / Curug Leuwi Hejo, Jas Hujan (jika hujan), Trekking Pole, Air Mineral 600ml, Guide Trekking. |

---

## Fitur Utama Aplikasi

### 1. Portal Pelanggan / Publik (Tanpa Perlu Login User)
- **Beranda (`/`):** Hero banner interaktif, paket populer, keunggulan YKS, alur 4 langkah reservasi, cuplikan galeri, ulasan peserta, dan akordion FAQ.
- **Katalog & Detail Paket (`/paket` & `/paket/{slug}`):** Filter pencarian & kategori paket, rincian fasilitas *Include* / *Exclude*, jadwal *itinerary*, titik kumpul (*Meeting Point*), dan poster pricelist resmi.
- **Alur Reservasi 4 Langkah (`/booking` -> `/pembayaran/{kode}` -> `/lacak-pesanan`):**
  1. Pilih Paket & Jadwal Keberangkatan
  2. Isi Identitas Pemesan (Nama, Nomor WhatsApp, Email, Catatan)
  3. Instruksi Transfer Resmi (BCA, Mandiri, QRIS) & Unggah Bukti Pembayaran
  4. Konfirmasi & Lacak Status Reservasi
- **Lacak Status Booking Tanpa Login (`/lacak-pesanan`):**
  - Pelanggan dapat mengecek status reservasi kapan saja tanpa perlu membuat akun/login cukup dengan memasukkan **Kode Booking** (contoh: `YKS-261114-018`), **Nomor WhatsApp** (contoh: `081200000018`), atau **Email**.
  - Mendukung unggah bukti transfer langsung dari halaman pelacakan, salin kode referensi, dan tombol konfirmasi cepat ke WhatsApp Admin (`0812-1084-2032` / `0857-7071-5553`).
- **Galeri & Testimoni (`/galeri-testimoni`):** Menampilkan **11 foto dokumentasi asli YKS** dari folder `public/galeri/` dengan filter kategori (*Trabas Motor Trail*, *Wisata Jeep 4x4*, *Jalur Sungai & Curug*) serta fitur **Lightbox Preview**.
- **Tampilan Mobile Responsif:** Header mobile dengan menu *slide-over drawer* penuh serta indikator progres 4 langkah reservasi seluler sesuai desain Figma.

### 2. Ruang Administrasi (`/admin` — Wajib Login Admin)
Seluruh halaman `/admin/*` dilindungi oleh middleware `EnsureAdmin`. Jika belum login, pengunjung otomatis diarahkan ke **`/admin/login`**.

- **8 Modul Ruang Administrasi Sesuai Figma:**
  1. **Ringkasan (`/admin`):** 4 kartu metrik utama, antrean verifikasi bukti bayar, jadwal keberangkatan terdekat, dan kapasitas paket.
  2. **Kelola Paket (`/admin/paket`):** Tambah paket baru, edit paket, **upload foto paket dari perangkat**, *live preview* kartu paket, *chip* fasilitas cepat, serta *toggle* status **Aktif / Nonaktif**.
  3. **Jadwal & Slot (`/admin/jadwal-slot`):** Kalender interaktif bulanan, pengaturan kapasitas sesi pagi/siang, serta fitur **Tutup / Buka Tanggal (Blokir Tanggal)**.
  4. **Data Reservasi (`/admin/reservasi`):** Filter tab status, pencarian kode/nama/WA, tabel master-detail dengan panel rincian pesanan di kanan, lini masa status, cetak ringkasan, dan ubah status.
  5. **Verifikasi Pembayaran (`/admin/verifikasi`):** Antrean bukti transfer dengan fitur **Zoom Bukti Bayar (`− 100% +`)**, perbandingan nominal tagihan vs bukti, alasan penolakan, serta tombol **Setujui & Konfirmasi** / **Tolak Bukti**.
  6. **Data Pelanggan (`/admin/pelanggan`):** Direktori pelanggan unik otomatis dari riwayat reservasi, kartu profil pelanggan, riwayat perjalanan, tombol hubungi WhatsApp, dan **Unduh CSV**.
  7. **Laporan (`/admin/laporan`):** Filter periode & paket, grafik tren reservasi & pendapatan, rincian status, tabel performa per paket, **Unduh CSV**, dan **Cetak / Simpan PDF**.
  8. **Jam Operasional (`/admin/jam-operasional`):** Pengaturan jam buka mingguan (Senin–Minggu), tombol *Salin Senin ke semua hari*, serta daftar pengecualian tanggal libur/perawatan armada.

---

## Akun Login Admin Default

Saat menjalankan `php artisan migrate --seed` (atau `php artisan db:seed`), akun Admin otomatis dibuat:

| Role | URL Login | Email | Kata Sandi (Password) |
| :--- | :--- | :--- | :--- |
| **Admin Utama** | `http://localhost:8000/admin/login` | `admin@yks.id` | `admin123` |
| **Admin Cadangan** | `http://localhost:8000/admin/login` | `admin@kelilingsentul.id` | `admin123` |

---

## Struktur Folder Utama

```text
YKS-keliling_sentul/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── HomeController.php             # Beranda, Tentang, Galeri, Informasi
│   │   │   ├── PackageController.php          # Katalog & Detail Paket Pelanggan
│   │   │   ├── BookingController.php          # Booking, Upload Bukti & Lacak Status (Tanpa Login)
│   │   │   └── Admin/                         # 8 Modul Ruang Administrasi + AuthController
│   │   └── Middleware/
│   │       └── EnsureAdmin.php                # Proteksi wajib login untuk rute /admin/*
│   └── Models/
│       ├── User.php                           # Model akun Admin
│       ├── Role.php                           # Model role pengguna
│       ├── YksCategory.php                    # Kategori paket wisata
│       ├── YksPackage.php                     # Data 5 paket resmi YKS
│       ├── YksBooking.php                     # Data reservasi & bukti pembayaran
│       └── YksTestimonial.php                 # Data ulasan pelanggan
├── database/
│   ├── migrations/                            # Skema tabel database
│   └── seeders/
│       ├── DatabaseSeeder.php                 # Pemanggil seeder utama
│       ├── RoleSeeder.php                     # Seeder role
│       └── YksSeeder.php                      # Seeder Akun Admin, 5 Paket Resmi, & Contoh Booking
├── public/
│   ├── assets/css/style.css                   # Stylesheet utama & responsif mobile sesuai Figma
│   ├── galeri/                                # 11 Foto dokumentasi asli YKS (Trail, Jeep, Sungai)
│   └── uploads/
│       ├── packages/                          # Poster & foto paket yang diunggah dari Admin
│       └── bukti/                             # Bukti transfer yang diunggah pelanggan
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php                      # Layout utama pelanggan (Desktop & Drawer Mobile)
│   │   └── admin.blade.php                    # Layout sidebar & header Ruang Administrasi
│   ├── bookings/                              # Halaman form booking, pembayaran, & lacak status
│   ├── packages/                              # Halaman katalog & detail paket
│   └── admin/                                 # Seluruh halaman modul Admin & Login Admin
└── routes/
    └── web.php                                # Definisi rute Publik & Admin
```

---

## 🚀 Panduan Setup & Cara Menjalankan Proyek (Untuk Teman / Tim Clone)

Panduan ini ditujukan bagi rekan tim/teman yang ingin meng-clone repositori ini ke komputer lokal mereka (Windows, Linux, atau macOS) hingga website dan panel admin siap dijalankan.

---

### 1. Prasyarat Sistem
Pastikan di komputer sudah terpasang:
- **PHP** minimal versi `8.2` (PHP 8.2, 8.3, atau 8.4)
- **Composer** ([Unduh di getcomposer.org](https://getcomposer.org/download/))
- **Database MySQL / MariaDB** (melalui **XAMPP**, **Laragon**, atau MySQL native)
- **Git** ([Unduh di git-scm.com](https://git-scm.com/))

---

### 2. Langkah-Langkah Clone & Instalasi

#### Langkah 1: Clone Repositori dari GitHub
Buka Terminal / Command Prompt / Git Bash, lalu jalankan:

```bash
git clone https://github.com/WaLMaN-s/YKS-keliling_sentul.git
cd YKS-keliling_sentul
```

---

#### Langkah 2: Install Dependensi Laravel (Composer)
```bash
composer install
```

---

#### Langkah 3: Siapkan File Konfigurasi `.env` & Generate App Key
Salin file template `.env.example` menjadi `.env`, lalu buat encryption key aplikasi:

- **Di Windows (CMD):**
  ```cmd
  copy .env.example .env
  php artisan key:generate
  ```

- **Di Linux / macOS / Git Bash:**
  ```bash
  cp .env.example .env
  php artisan key:generate
  ```

---

#### Langkah 4: Buat Database Baru
Buat database bernama **`yks_sentul`**:

- **Jika Menggunakan XAMPP / Laragon (Windows):**
  1. Nyalakan service **Apache** dan **MySQL** di XAMPP / Laragon.
  2. Buka browser ke [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
  3. Klik menu **Databases (Basis Data)** -> buat database baru bernama **`yks_sentul`** -> klik **Create**.
  4. Buka file `.env`, sesuaikan pengaturan MySQL default (biasanya username `root` tanpa password):
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=yks_sentul
     DB_USERNAME=root
     DB_PASSWORD=
     ```

- **Jika Menggunakan Linux / MySQL Terminal:**
  ```bash
  sudo mysql -e "CREATE DATABASE IF NOT EXISTS yks_sentul CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
  ```
  Lalu sesuaikan `DB_USERNAME` dan `DB_PASSWORD` di file `.env` dengan kredensial MySQL server Anda.

---

#### Langkah 5: Jalankan Migrasi & Seeder Data Awal
Jalankan perintah ini untuk membuat seluruh tabel, akun Admin default, 5 paket wisata resmi YKS, serta data reservasi contoh:

```bash
php artisan migrate:fresh --seed
```

*(Opsional: hubungkan symlink storage)*
```bash
php artisan storage:link
```

---

#### Langkah 6: Jalankan Server Lokal
```bash
php artisan serve
```

Server lokal akan aktif di port default `8000`.

---

### 3. URL Akses & Kredensial Login

Buka browser dan akses URL berikut:

| Halaman | URL Akses | Keterangan |
| :--- | :--- | :--- |
| **Website Utama (Publik)** | [http://127.0.0.1:8000](http://127.0.0.1:8000) | Beranda, katalog paket, booking, & galeri |
| **Lacak Pesanan (User)** | [http://127.0.0.1:8000/lacak-pesanan](http://127.0.0.1:8000/lacak-pesanan) | Cek status pesanan tanpa login |
| **Panel Login Admin** | [http://127.0.0.1:8000/admin/login](http://127.0.0.1:8000/admin/login) | Dashboard pengelolaan operasional YKS |

#### Kredensial Login Administrator:
- **Email**: `admin@yks.id` *(atau `admin@kelilingsentul.id`)*
- **Password**: `admin123`

---

### 4. Panduan Jika Ingin Mengambil Pembaruan Terbaru (Update Kode)

Jika di kemudian hari ada pembaruan kode di GitHub dan rekan tim ingin menyinkronkan kodenya, cukup jalankan:

```bash
git pull origin main
php artisan migrate --force
php artisan optimize:clear
```

---

### 5. Troubleshooting & Solusi Kendala Umum

1. **Error: `could not find driver` saat migrasi:**
   - Masalah: Ekstensi PDO MySQL di PHP belum aktif.
   - Solusi: Buka file `php.ini`, cari baris `;extension=pdo_mysql` dan `;extension=fileinfo`, hilangkan tanda titik koma (`;`) di depannya, lalu restart Apache/PHP.

2. **Error: `Permission denied` pada folder `storage` (Khusus Linux):**
   - Solusi: Berikan izin tulis ke folder storage:
     ```bash
     chmod -R 775 storage bootstrap/cache
     ```

3. **Tampilan CSS / Gambar terasa tertahan cache lama:**
   - Solusi: Bersihkan cache view Laravel:
     ```bash
     php artisan optimize:clear
     ```
