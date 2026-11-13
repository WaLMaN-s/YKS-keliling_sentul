<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class YksSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Default Admin Account untuk Login /admin/login
        $adminRole = Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin']);
        User::updateOrCreate(
            ['email' => 'admin@yks.id'],
            [
                'role_id' => $adminRole->id,
                'name' => 'Admin YKS',
                'phone' => '081210842032',
                'password' => Hash::make('admin123'),
                'is_blocked' => false,
            ]
        );
        User::updateOrCreate(
            ['email' => 'admin@kelilingsentul.id'],
            [
                'role_id' => $adminRole->id,
                'name' => 'Admin Utama YKS',
                'phone' => '085770715553',
                'password' => Hash::make('admin123'),
                'is_blocked' => false,
            ]
        );

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('yks_categories')->truncate();
        DB::table('yks_packages')->truncate();
        DB::table('yks_testimonials')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Categories (Sesuai Pricelist Resmi Yuk Keliling Sentul)
        $categories = [
            ['id' => 1, 'name' => 'Sewa Motor Trail & Trabas', 'slug' => 'sewa-motor-trail', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Wisata Jeep 4x4 & Trekking', 'slug' => 'wisata-jeep-trekking', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Trabas Motor Pribadi', 'slug' => 'trabas-motor-pribadi', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('yks_categories')->insert($categories);

        // 2. Packages (5 Paket Resmi YKS: Paket 1, Paket 2, Paket 3, Bawa Motor Pribadi, dan Edisi Wisata Jeep 4x4)
        $packages = [
            [
                'id' => 1,
                'category_id' => 1,
                'name' => 'Paket 1 · Trabas Motor Trail 150 CC (CRF / WR / KLX)',
                'slug' => 'paket-1-trabas-trail-150cc',
                'description' => 'Trabas Private Pemula menggunakan motor trail 150 CC pilihan (Honda CRF, Yamaha WR, atau Kawasaki KLX). Minimal pemesanan 2 orang. Sudah lengkap termasuk Guide/Leader, BBM sesuai rute, helm, sepatu safety, dan pinjaman jersey set.',
                'price' => 700000,
                'duration' => '4 - 6 Jam',
                'distance' => '15 s/d 25 Km',
                'difficulty' => 'Mudah',
                'meeting_point' => 'Basecamp Yuk Keliling Sentul, Hambalang / Sentul, Bogor',
                'includes' => "Unit Motor Trail 150 CC (Honda CRF / Yamaha WR / Kawasaki KLX)\nGuide / Leader\nBBM Sesuai Rute\nHelm + Sepatu Safety\nJersey Set (Dipinjamkan)\nJalur Trabas (15 s/d 25 Km)\nAir Mineral 600ml\nDokumentasi (HP)",
                'excludes' => "Baju Ganti\nTas Wadah Minum\nSarung Tangan\nUang Cash\nTipping Guide / Leader",
                'itinerary' => "07.30 - Berkumpul di Basecamp Hambalang & fitting perlengkapan (Jersey, Helm, Sepatu Safety)\n08.00 - Briefing safety riding & pengenalan jalur trabas pemula\n08.30 - Start perjalanan trabas menggunakan Motor Trail 150 CC (15 s/d 25 Km)\n11.30 - Istirahat di spot bukit/sungai, minum air mineral & dokumentasi\n13.30 - Kembali ke Basecamp Hambalang & selesai",
                'image' => '/galeri/Untitled.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'category_id' => 1,
                'name' => 'Paket 2 · Trabas Motor Trail Kawasaki KLX 140 CC',
                'slug' => 'paket-2-trabas-trail-klx-140cc',
                'description' => 'Trabas Private Pemula menggunakan motor trail built-up Kawasaki KLX 140 CC yang ringan, lincah, dan bertenaga di jalur tanjakan Hambalang–Sentul. Minimal pemesanan 1 orang (bisa solo private trip).',
                'price' => 1300000,
                'duration' => '4 - 6 Jam',
                'distance' => '15 s/d 25 Km',
                'difficulty' => 'Sedang',
                'meeting_point' => 'Basecamp Yuk Keliling Sentul, Hambalang / Sentul, Bogor',
                'includes' => "Unit Motor Trail Kawasaki KLX 140 CC\nGuide / Leader\nBBM Sesuai Rute\nHelm + Sepatu Safety\nJersey Set (Dipinjamkan)\nJalur Trabas (15 s/d 25 Km)\nAir Mineral 600ml\nDokumentasi (HP)",
                'excludes' => "Baju Ganti\nTas Wadah Minum\nSarung Tangan\nUang Cash\nTipping Guide / Leader",
                'itinerary' => "07.30 - Berkumpul di Basecamp Hambalang & pemakaian gear safety lengkap\n08.00 - Pengenalan unit Kawasaki KLX 140 CC & briefing jalur\n08.30 - Menjelajahi lintasan trabas tanah, bukit & sungai (15 s/d 25 Km)\n11.30 - Istirahat sejenak & sesi dokumentasi aksi trabas\n13.30 - Finish kembali di Basecamp Hambalang",
                'image' => '/galeri/Untitled6.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'category_id' => 1,
                'name' => 'Paket 3 · Trabas Motor Trail 300 CC (Husqvarna / KTM / Sherco)',
                'slug' => 'paket-3-trabas-trail-300cc',
                'description' => 'Sensasi trabas kelas premium menggunakan motor trail Enduro Eropa 300 CC (Husqvarna, KTM, atau Sherco). Minimal pemesanan 1 orang. Tenaga dan suspensi kelas kompetisi untuk menaklukkan jalur Hambalang–Sentul.',
                'price' => 2400000,
                'duration' => '4 - 6 Jam',
                'distance' => '15 s/d 25 Km',
                'difficulty' => 'Menantang',
                'meeting_point' => 'Basecamp Yuk Keliling Sentul, Hambalang / Sentul, Bogor',
                'includes' => "Unit Motor Trail 300 CC (Husqvarna / KTM / Sherco)\nGuide / Leader\nBBM Sesuai Rute\nHelm + Sepatu Safety\nJersey Set (Dipinjamkan)\nJalur Trabas (15 s/d 25 Km)\nAir Mineral 600ml\nDokumentasi (HP)",
                'excludes' => "Baju Ganti\nTas Wadah Minum\nSarung Tangan\nUang Cash\nTipping Guide / Leader",
                'itinerary' => "07.30 - Kumpul di Basecamp Hambalang, cek unit 300 CC (Husqvarna/KTM/Sherco) & fitting gear\n08.00 - Briefing rute enduro & teknik melewati rintangan\n08.30 - Gaspol melibas jalur trabas pilihan (15 s/d 25 Km)\n11.30 - Istirahat di puncak panorama & dokumentasi\n13.30 - Kembali ke Basecamp & trip selesai",
                'image' => '/galeri/Untitled9.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'category_id' => 3,
                'name' => 'Paket Trabas Bawa Motor Trail Pribadi',
                'slug' => 'paket-trabas-bawa-motor-pribadi',
                'description' => 'Punya motor trail sendiri dan ingin menjajal serunya jalur trabas Hambalang–Sentul bersama Leader lokal berpengalaman? Paket khusus peserta yang membawa motor pribadi dengan rute 15 s/d 25 Km.',
                'price' => 200000,
                'duration' => '4 - 6 Jam',
                'distance' => '15 s/d 25 Km',
                'difficulty' => 'Sedang',
                'meeting_point' => 'Basecamp Yuk Keliling Sentul, Hambalang / Sentul, Bogor',
                'includes' => "Guide / Leader Berpengalaman\nJalur Trabas Resmi (15 s/d 25 Km)\nAir Mineral 600ml\nDokumentasi (HP)\nPendampingan Jalur & Evakuasi Lapangan",
                'excludes' => "Unit Motor Trail & BBM Pribadi\nPerlengkapan Riding Pribadi (Helm, Sepatu, Jersey, Sarung Tangan)\nBaju Ganti & Tas Wadah Minum\nUang Cash\nTipping Guide / Leader",
                'itinerary' => "07.30 - Berkumpul di Basecamp Hambalang & pengecekan kesiapan motor pribadi\n08.00 - Briefing jalur sesuai karakter peserta (Pemula / Fun / Tanjakan)\n08.30 - Start trabas menyusuri jalur Hambalang - Sentul (15 s/d 25 Km)\n11.30 - Istirahat & dokumentasi bersama di spot bukit\n13.30 - Kembali ke Basecamp & selesai",
                'image' => '/galeri/Untitled10.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'category_id' => 2,
                'name' => 'Edisi Wisata Jeep 4x4 & Trekking Curug Sentul',
                'slug' => 'edisi-wisata-jeep-4x4-trekking-curug',
                'description' => 'Paket komplit Wisata Jeep 4x4 dipadukan dengan Trekking Curug Sentul (pilihan Curug Leuwi Asih atau Curug Leuwi Hejo). 1 unit Jeep berisi 3 s/d 4 orang bersama Driver Profesional (Akamsi) dan Guide Trekking selama 8 s/d 10 jam.',
                'price' => 1750000,
                'duration' => '8 s/d 10 Jam',
                'distance' => 'Offroad & Curug',
                'difficulty' => 'Mudah',
                'meeting_point' => 'Pos Jelajah YKS Sentul, Babakan Madang, Bogor',
                'includes' => "1 Unit Jeep berisi 3 s/d 4 Orang\nDriver Profesional (Akamsi)\nTiket Destinasi Trekking dan Wisata Jeep\nCurug Leuwi Asih / Curug Leuwi Hejo (Pilihan Trekking)\nDurasi 8 s/d 10 Jam Trekking dan Wisata Offroad\nJas Hujan (Jika Hujan)\nTrekking Pole\nAir Mineral 600ml\nGuide Trekking",
                'excludes' => "Baju Ganti & Keperluan Pribadi\nMakan Siang Tambahan di Luar Paket\nTipping Driver / Guide",
                'itinerary' => "07.30 - Berkumpul di Meeting Point Sentul & Briefing Keberangkatan\n08.00 - Wisata Offroad Jeep 4x4 melintasi jalur perbukitan, lumpur & sungai Sentul\n10.30 - Tiba di titik trekking, pembagian Trekking Pole & mulai jelajah menuju Curug Leuwi Asih / Curug Leuwi Hejo\n12.30 - Menikmati suasana curug, berenang & istirahat\n15.00 - Kembali ke armada Jeep 4x4 & perjalanan pulang\n16.30 - Tiba kembali di Meeting Point & selesai",
                'image' => '/galeri/Untitled3.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('yks_packages')->insert($packages);

        // 3. Testimonials
        $testimonials = [
            ['id' => 1, 'name' => 'Budi Santoso', 'city' => 'Jakarta Selatan', 'package_name' => 'Paket 1 · Trabas Motor Trail 150 CC', 'review' => 'Pertama kali nyobain trabas pake CRF 150 di Hambalang bareng tim YKS. Gear lengkap dipinjemin dari jersey sampe sepatu boot, leader-nya sabar banget ngarahin jalur pemula!', 'rating' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Sarah Amelia', 'city' => 'Tangerang', 'package_name' => 'Edisi Wisata Jeep 4x4 & Trekking Curug', 'review' => 'Seru banget satu jeep berempat lanjut trekking ke Curug Leuwi Hejo! Driver akamsi-nya ramah, basah-basahan di sungai terus lanjut curug seharian puas banget.', 'rating' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Rian Pratama', 'city' => 'Bekasi', 'package_name' => 'Paket Trabas Bawa Motor Trail Pribadi', 'review' => 'Cuma 200rb bawa motor trail sendiri udah dapet leader asli Hambalang yang hafal jalur keren 25 km plus difotoin sepanjang rute. Mantap YKS!', 'rating' => 5, 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('yks_testimonials')->insert($testimonials);

        // 4. Sample Bookings (Disesuaikan dengan 5 Paket Resmi YKS)
        DB::table('yks_bookings')->truncate();
        $sampleBookings = [
            [
                'booking_code' => 'YKS-261114-018',
                'package_id' => 1,
                'customer_name' => 'Rani Contoh',
                'customer_phone' => '081200000018',
                'customer_email' => 'rani@pelanggan.example',
                'trip_date' => '2026-11-14',
                'participant_count' => 2,
                'total_price' => 1400000,
                'payment_method' => 'Transfer Bank BCA',
                'payment_proof' => 'BUKTI_YKS-261114-018.jpg',
                'payment_status' => 'Menunggu Verifikasi',
                'notes' => '2 orang pemula (CRF & KLX 150). Mohon disiapkan sepatu ukuran 41 dan 42.',
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subMinutes(80),
            ],
            [
                'booking_code' => 'YKS-261114-019',
                'package_id' => 5,
                'customer_name' => 'Dimas Contoh',
                'customer_phone' => '081200000019',
                'customer_email' => 'dimas@pelanggan.example',
                'trip_date' => '2026-11-14',
                'participant_count' => 1,
                'total_price' => 1750000,
                'payment_method' => 'Transfer Bank BCA',
                'payment_proof' => 'BUKTI_YKS-261114-019.jpg',
                'payment_status' => 'Menunggu Verifikasi',
                'notes' => '1 unit Jeep 4x4 (4 orang keluarga), pilihan trekking Curug Leuwi Hejo.',
                'created_at' => now()->subHours(1),
                'updated_at' => now()->subMinutes(45),
            ],
            [
                'booking_code' => 'YKS-261114-020',
                'package_id' => 4,
                'customer_name' => 'Sinta Contoh',
                'customer_phone' => '081200000020',
                'customer_email' => 'sinta@pelanggan.example',
                'trip_date' => '2026-11-14',
                'participant_count' => 3,
                'total_price' => 600000,
                'payment_method' => 'Transfer Bank BCA',
                'payment_proof' => 'BUKTI_YKS-261114-020.jpg',
                'payment_status' => 'Menunggu Verifikasi',
                'notes' => 'Rombongan 3 motor trail pribadi.',
                'created_at' => now()->subMinutes(40),
                'updated_at' => now()->subMinutes(25),
            ],
            [
                'booking_code' => 'YKS-261114-016',
                'package_id' => 2,
                'customer_name' => 'Bima Contoh',
                'customer_phone' => '081200000016',
                'customer_email' => 'bima@pelanggan.example',
                'trip_date' => '2026-11-14',
                'participant_count' => 1,
                'total_price' => 1300000,
                'payment_method' => 'Transfer Bank BCA',
                'payment_proof' => 'BUKTI_YKS-261114-018.jpg',
                'payment_status' => 'Lunas',
                'notes' => 'Private 1 orang unit KLX 140 CC.',
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'booking_code' => 'YKS-261115-017',
                'package_id' => 3,
                'customer_name' => 'Laras Contoh',
                'customer_phone' => '081200000017',
                'customer_email' => 'laras@pelanggan.example',
                'trip_date' => '2026-11-15',
                'participant_count' => 1,
                'total_price' => 2400000,
                'payment_method' => 'Transfer Bank BCA',
                'payment_status' => 'Lunas',
                'notes' => 'Request unit Husqvarna 300 CC.',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'booking_code' => 'YKS-261116-021',
                'package_id' => 4,
                'customer_name' => 'Bayu Contoh',
                'customer_phone' => '081200000021',
                'customer_email' => 'bayu@pelanggan.example',
                'trip_date' => '2026-11-16',
                'participant_count' => 4,
                'total_price' => 800000,
                'payment_method' => 'Transfer Bank BCA',
                'payment_status' => 'Menunggu Pembayaran',
                'notes' => 'Bawa motor pribadi 4 orang.',
                'created_at' => now()->subHours(5),
                'updated_at' => now()->subHours(5),
            ],
        ];
        DB::table('yks_bookings')->insert($sampleBookings);
    }
}
