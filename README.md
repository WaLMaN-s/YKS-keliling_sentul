# 🚙 Yuk Keliling Sentul (YKS) — Adventure & Offroad Tour Platform

> **Platform Pemesanan Wisata Petualangan & Offroad 4x4 Sentul, Jawa Barat**  
> Dibangun menggunakan **Laravel 12** berdasarkan spesifikasi desain resmi Figma **`YKS_desain`**.

---

## 🎨 1. Referensi Desain Figma
* **Figma File:** [`YKS_desain`](https://www.figma.com/design/5WB0Bqa2deoGdpd8Y8QNMi/YKS_desain?node-id=0-1)
* **Node ID Utama:** `0:1`
* **File Key:** `5WB0Bqa2deoGdpd8Y8QNMi`

### 🌿 Design System & Visual Tokens
Berdasarkan ekstraksi langsung dari Figma token:

| Kategori | Token / Kode Hex | Keterangan & Penggunaan |
| :--- | :--- | :--- |
| **Primary (Forest Dark)** | `#1F3D2B` | Warna utama, header/sidebar, tombol primer |
| **Secondary (Forest Medium)** | `#304F3B` | State hover, card container, active nav item |
| **Accent (Adventure Orange)** | `#F26B1D` | Badge sorotan, CTA konfirmasi, indikator status |
| **Canvas / Background** | `#F8F5EF` | Background halaman bernuansa earthy warm |
| **Muted Sage** | `#C1D1BE` / `#DFE4DC` | Border pemisah, avatar placeholder, tag ringan |
| **Text Dark** | `#1A1A1A` / `#1F3D2B` | Heading dan teks utama |
| **Text Muted** | `#6B756E` / `#667066` | Teks keterangan, subtitle, instruksi |
| **Typography** | `Montserrat` & `Inter` | Montserrat (Headings & Brand), Inter (Body & Data) |
| **Border Radius** | `8px` (sm), `12px` (md) | Card, modal, container, tombol |

---

## 🏛️ 2. Arsitektur & Modul Sistem

Sistem dibagi menjadi **2 portal utama** sesuai dengan rancangan Figma:

```
                            ┌───────────────────────────────────┐
                            │    YUK KELILING SENTUL (YKS)      │
                            └─────────────────┬─────────────────┘
                                              │
                     ┌────────────────────────┴────────────────────────┐
                     ▼                                                 ▼
     ┌───────────────────────────────┐                 ┌───────────────────────────────┐
     │ 🌐 1. PORTAL PUBLIK (USER)    │                 │ 🛡️ 2. PORTAL ADMIN (BACKOFFICE│
     ├───────────────────────────────┤                 ├───────────────────────────────┤
     │ • Beranda (Landing Page)      │                 │ • Ringkasan (Dashboard KPI)   │
     │ • Katalog & Detail Paket      │                 │ • Verifikasi Pembayaran       │
     │ • 4-Step Checkout Wizard      │                 │ • Data Reservasi              │
     │ • Pembayaran & Upload Bukti   │                 │ • Jadwal & Kuota Slot Harian  │
     │ • Lacak Status Reservasi      │                 │ • Kelola Paket Petualangan    │
     │ • Akun & Riwayat Pemesanan    │                 │ • Data Pelanggan              │
     │ • Informasi & Galeri Sentul   │                 │ • Laporan Keuangan (CSV/PDF)  │
     │                               │                 │ • Jam Operasional & Info Pos  │
     └───────────────────────────────┘                 └───────────────────────────────┘
```

---

## 🧭 3. Rincian Modul & Alur Kerja Sesuai Figma

### A. Modul Pengunjung / Pelanggan (Front Office)
1. **Beranda (`/`)**:
   - Hero Section: *"SENTUL, JAWA BARAT · JELAJAH TANPA BIASA"*.
   - Filter & Quick Booking Widget (Tanggal & Jumlah Peserta).
   - Kartu Pilihan Paket (Jelajah Rimba, Jelajah Senja, Offroad 4x4).
   - Nilai Unggulan (Local Guides, Safety First, Rute Eksklusif).
   - Galeri Dokumentasi & Testimoni Pelanggan.
2. **Katalog & Detail Paket (`/paket`, `/paket/{slug}`)**:
   - Menampilkan harga dasar (e.g. *1 jip × Rp1.200.000*).
   - Rincian fasilitas (Include/Exclude), rute, itinerary per jam, meeting point.
3. **Alur Reservasi Interaktif 4 Langkah (`/booking`)**:
   - **Step 1 - Pilih Jadwal & Slot**: Kalender pemilihan tanggal, jam trip (Batch 08.00–13.00 WIB, 11.00, atau 14.00), dan jumlah armada jip.
   - **Step 2 - Data Pemesan**: Form kontak WhatsApp, nama, email, serta catatan preferensi (misal: *peserta pemula*).
   - **Step 3 - Pembayaran (`/pembayaran/{booking_code}`)**: Rekening resmi BCA YKS, rincian biaya tanpa admin, dan form upload bukti transfer.
   - **Step 4 - Konfirmasi**: Halaman ringkasan status *"Menunggu Verifikasi"* beserta instruksi titik temu & pengarahan.
4. **Lacak Pesanan (`/lacak-pesanan`)**:
   - Pengecekan status real-time cukup dengan memasukkan Kode Booking (e.g. `YKS-202611...`).
5. **Autentikasi & Akun (`/login`, `/register`, `/riwayat-saya`)**:
   - Login pelanggan untuk melihat riwayat perjalanan dan e-voucher.

---

### B. Modul Administrasi YKS (Back Office)
Akses melalui prefix `/admin`:
1. **Ringkasan (`/admin`)**:
   - Metrik total reservasi, rasio lunas (70%), antrean verifikasi bukti transfer, dan proyeksi pendapatan.
   - Tabel reservasi terbaru dengan aksi langsung.
2. **Verifikasi Pembayaran (`/admin/verifikasi-pembayaran`)**:
   - Antrean bukti transfer yang diunggah pelanggan.
   - Aksi cepat: **Setujui Pembayaran (Lunas)** atau **Tolak Bukti**.
3. **Data Reservasi (`/admin/reservasi`)**:
   - Filter status: *Semua, Lunas, Menunggu Verifikasi, Menunggu Pembayaran, Dibatalkan*.
   - Detail lengkap pemesan, rincian biaya, dan catatan khusus.
4. **Jadwal & Kuota Slot (`/admin/jadwal-slot`)**:
   - Manajemen kapasitas harian (standar 12 slot jip harian).
   - Monitoring kuota tersisa vs kuota terisi.
5. **Kelola Paket (`/admin/paket`)**:
   - Master data paket wisata (Jelajah Rimba, Jelajah Senja, Curug, dsb.).
   - Pengaturan harga, durasi, dan status aktif/nonaktif.
6. **Data Pelanggan (`/admin/pelanggan`)**:
   - Direktori pelanggan, total frekuensi trip, dan akumulasi transaksi.
7. **Laporan & Ekspor (`/admin/laporan`)**:
   - Laporan transaksi berdasarkan periode tanggal pesanan.
   - Tombol **Ekspor CSV** (data mentah) dan **Unduh PDF** (ringkasan cetak).
8. **Jam Operasional & Pos (`/admin/jam-operasional`)**:
   - Konfigurasi informasi pos jelajah Babakan Madang Sentul dan jam respon admin.

---

## 🗄️ 4. Struktur Database & Model

```
┌──────────────────┐       1:N       ┌────────────────────────┐
│  yks_categories  │◄────────────────┤      yks_packages      │
│──────────────────│                 │────────────────────────│
│ id               │                 │ id                     │
│ name             │                 │ category_id            │
│ slug             │                 │ name, slug             │
└──────────────────┘                 │ description            │
                                     │ price, duration        │
                                     │ difficulty             │
                                     │ includes, excludes     │
                                     │ itinerary, image       │
                                     │ is_active              │
                                     └───────────┬────────────┘
                                                 │ 1:N
                                                 ▼
┌──────────────────┐       1:N       ┌────────────────────────┐
│      users       │◄────────────────┤      yks_bookings      │
│──────────────────│                 │────────────────────────│
│ id               │                 │ id                     │
│ name, email      │                 │ booking_code (UNIQUE)  │
│ phone, password  │                 │ user_id, package_id    │
│ role_id          │                 │ customer_name, phone   │
└──────────────────┘                 │ trip_date, slot_time   │
                                     │ participant_count      │
                                     │ total_price            │
                                     │ payment_method         │
                                     │ payment_proof          │
                                     │ payment_status         │
                                     │ notes                  │
                                     └────────────────────────┘
```

---

## 📋 5. Roadmap & Checklist Pengerjaan Sesuai Figma

- [x] **Fase 1: Setup Framework & Design System Tokens**
  - [x] Konfigurasi environment Laravel 12 & basis data.
  - [x] Token CSS Figma di `public/assets/css/style.css` (Warna `#1F3D2B`, `#F8F5EF`, `#F26B1D`).
  - [x] Layout utama publik (`layouts/app.blade.php`) & layout admin (`layouts/admin.blade.php`).

- [x] **Fase 2: Portal Publik (Role User)**
  - [x] Slicing Beranda sesuai Figma (Hero, Value Prop, Highlight).
  - [x] Halaman Katalog & Detail Paket Jelajah.
  - [x] Form 4-Langkah Reservasi Booking.
  - [x] Upload bukti pembayaran & halaman konfirmasi.
  - [x] Fitur Lacak Pesanan mandiri dengan Kode Booking.

- [x] **Fase 3: Kerangka Administrasi YKS (Role Admin)**
  - [x] Controller & Routing Administrasi YKS (`/admin/*`).
  - [x] Dashboard Ringkasan & Widget KPI Operasional.
  - [x] Halaman Antrean Verifikasi Pembayaran dengan tombol Approve/Reject.
  - [x] Data Reservasi & Filter status.
  - [x] Halaman Kuota Jadwal & Slot Jip.
  - [x] Kelola Paket Petualangan.
  - [x] Direktori Data Pelanggan.
  - [x] Modul Laporan dengan fitur Ekspor CSV.
  - [x] Pengaturan Jam Operasional & Pos Jelajah.

- [ ] **Fase 4: Integrasi Lanjutan & Penyempurnaan (Next Steps)**
  - [ ] Integrasi otomatisasi pesan WhatsApp notifikasi konfirmasi booking.
  - [ ] Generate otomatis E-Ticket PDF dengan QR Code booking.
  - [ ] Sinkronisasi kalender ketersediaan jip harian berbasis real-time datepicker.

---

## 🚀 6. Panduan Menjalankan Proyek

### Kebutuhan Sistem:
* PHP $\ge$ 8.2
* Composer $\ge$ 2.0
* Node.js & NPM
* Database MySQL atau SQLite

### Langkah Menjalankan:
```bash
# 1. Masuk ke direktori proyek
cd /home/man/WEB/YKS-keliling_sentul

# 2. Instalasi dependensi
composer install
npm install

# 3. Konfigurasi Environment & Generate Key (jika baru)
cp .env.example .env
php artisan key:generate

# 4. Migrasi & Seed Database Contoh
php artisan migrate --seed

# 5. Jalankan Server Pengembangan
php artisan serve
```

Aplikasi dapat diakses pada browser:
* **Portal Publik:** `http://localhost:8000`
* **Portal Administrasi:** `http://localhost:8000/admin`
