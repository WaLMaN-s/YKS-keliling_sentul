<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom SDD pada tabel yks_packages (paket)
        Schema::table('yks_packages', function (Blueprint $table) {
            if (!Schema::hasColumn('yks_packages', 'satuan_harga')) {
                $table->enum('satuan_harga', ['per_unit', 'per_peserta'])->default('per_peserta')->after('price');
            }
            if (!Schema::hasColumn('yks_packages', 'kapasitas_peserta')) {
                $table->unsignedSmallInteger('kapasitas_peserta')->default(1)->after('satuan_harga');
            }
        });

        // 2. Tambah kolom SDD pada tabel yks_bookings (reservasi)
        Schema::table('yks_bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('yks_bookings', 'jenis_trip')) {
                $table->enum('jenis_trip', ['open', 'private'])->default('private')->after('package_id');
            }
            if (!Schema::hasColumn('yks_bookings', 'motor_pribadi')) {
                $table->boolean('motor_pribadi')->default(false)->after('participant_count');
            }
            if (!Schema::hasColumn('yks_bookings', 'subtotal_package')) {
                $table->unsignedInteger('subtotal_package')->default(0)->after('motor_pribadi');
            }
            if (!Schema::hasColumn('yks_bookings', 'subtotal_addons')) {
                $table->unsignedInteger('subtotal_addons')->default(0)->after('subtotal_package');
            }
            if (!Schema::hasColumn('yks_bookings', 'selected_addons')) {
                $table->text('selected_addons')->nullable()->after('subtotal_addons');
            }
            if (!Schema::hasColumn('yks_bookings', 'selected_units')) {
                $table->string('selected_units')->nullable()->after('selected_addons');
            }
        });

        // 3. Tambah kolom is_tampil pada tabel yks_testimonials (testimoni)
        Schema::table('yks_testimonials', function (Blueprint $table) {
            if (!Schema::hasColumn('yks_testimonials', 'is_tampil')) {
                $table->boolean('is_tampil')->default(true)->after('rating');
            }
        });

        // 4. Tabel addon (Layanan Tambahan: Sepatu, Guide, Protector, dll.)
        if (!Schema::hasTable('yks_addons')) {
            Schema::create('yks_addons', function (Blueprint $table) {
                $table->id();
                $table->string('nama_addon', 100);
                $table->unsignedInteger('harga');
                $table->text('keterangan')->nullable();
                $table->boolean('is_aktif')->default(true);
                $table->timestamps();
            });
        }

        // 5. Tabel unit (Armada Jeep & Motor Trail)
        if (!Schema::hasTable('yks_units')) {
            Schema::create('yks_units', function (Blueprint $table) {
                $table->id();
                $table->string('kode_unit', 30)->unique();
                $table->enum('jenis_unit', ['jeep', 'trail'])->default('trail');
                $table->string('nama_unit', 100)->nullable();
                $table->unsignedSmallInteger('kapasitas')->default(1);
                $table->enum('status', ['aktif', 'perawatan'])->default('aktif');
                $table->timestamps();
            });
        }

        // 6. Tabel reservasi_unit (Relasi Reservasi dengan Unit Kendaraan)
        if (!Schema::hasTable('yks_booking_units')) {
            Schema::create('yks_booking_units', function (Blueprint $table) {
                $table->id();
                $table->foreignId('booking_id')->constrained('yks_bookings')->cascadeOnDelete();
                $table->foreignId('unit_id')->constrained('yks_units')->cascadeOnDelete();
                $table->date('trip_date');
                $table->timestamps();
            });
        }

        // 7. Tabel reservasi_addon (Relasi Reservasi dengan Add-On & Snapshot Harga)
        if (!Schema::hasTable('yks_booking_addons')) {
            Schema::create('yks_booking_addons', function (Blueprint $table) {
                $table->id();
                $table->foreignId('booking_id')->constrained('yks_bookings')->cascadeOnDelete();
                $table->foreignId('addon_id')->constrained('yks_addons')->cascadeOnDelete();
                $table->unsignedSmallInteger('jumlah')->default(1);
                $table->unsignedInteger('harga_satuan');
                $table->unsignedInteger('subtotal');
                $table->timestamps();
            });
        }

        // 8. Tabel pembayaran (Riwayat Unggah & Verifikasi Bukti Pembayaran 1:N)
        if (!Schema::hasTable('yks_payments')) {
            Schema::create('yks_payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('booking_id')->constrained('yks_bookings')->cascadeOnDelete();
                $table->unsignedInteger('nominal');
                $table->string('bukti_path');
                $table->enum('status', ['menunggu_verifikasi', 'diterima', 'ditolak'])->default('menunggu_verifikasi');
                $table->text('catatan_admin')->nullable();
                $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
                $table->dateTime('tgl_upload')->nullable();
                $table->dateTime('tgl_verifikasi')->nullable();
                $table->timestamps();
            });
        }

        // 9. Tabel rekening (Rekening Resmi yang Dikelola Admin)
        if (!Schema::hasTable('yks_bank_accounts')) {
            Schema::create('yks_bank_accounts', function (Blueprint $table) {
                $table->id();
                $table->string('nama_bank', 50);
                $table->string('no_rekening', 40);
                $table->string('atas_nama', 100);
                $table->boolean('is_aktif')->default(true);
                $table->timestamps();
            });
        }

        // 10. Tabel ketentuan (Perubahan Jadwal, Pembatalan, Kerusakan Unit)
        if (!Schema::hasTable('yks_terms')) {
            Schema::create('yks_terms', function (Blueprint $table) {
                $table->id();
                $table->enum('jenis', ['perubahan_jadwal', 'pembatalan', 'kerusakan_unit']);
                $table->string('judul', 120);
                $table->text('isi');
                $table->timestamps();
            });
        }

        // 11. Tabel konten_situs (Profil, Lokasi, Jam Operasional, Fasilitas Basecamp, Kontak WA)
        if (!Schema::hasTable('yks_site_contents')) {
            Schema::create('yks_site_contents', function (Blueprint $table) {
                $table->id();
                $table->enum('jenis', ['profil', 'lokasi', 'jam_operasional', 'fasilitas_basecamp', 'kontak_wa']);
                $table->string('judul', 120);
                $table->text('isi');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('yks_site_contents');
        Schema::dropIfExists('yks_terms');
        Schema::dropIfExists('yks_bank_accounts');
        Schema::dropIfExists('yks_payments');
        Schema::dropIfExists('yks_booking_addons');
        Schema::dropIfExists('yks_booking_units');
        Schema::dropIfExists('yks_units');
        Schema::dropIfExists('yks_addons');
    }
};
