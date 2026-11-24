<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('yks_packages', function (Blueprint $table) {
            if (!Schema::hasColumn('yks_packages', 'price_unit')) {
                $table->string('price_unit')->default('orang')->after('price');
            }
            if (!Schema::hasColumn('yks_packages', 'booking_mode')) {
                $table->string('booking_mode')->default('direct')->after('price_unit');
            }
            if (!Schema::hasColumn('yks_packages', 'consultation_target')) {
                $table->string('consultation_target')->nullable()->after('booking_mode');
            }
            if (!Schema::hasColumn('yks_packages', 'capacity_label')) {
                $table->string('capacity_label')->nullable()->after('distance');
            }
        });

        Schema::table('yks_bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('yks_bookings', 'custom_package_name')) {
                $table->string('custom_package_name')->nullable()->after('package_id');
            }
            if (!Schema::hasColumn('yks_bookings', 'booking_source')) {
                $table->string('booking_source')->default('Website')->after('custom_package_name');
            }
            if (!Schema::hasColumn('yks_bookings', 'booking_channel')) {
                $table->string('booking_channel')->default('Website')->after('booking_source');
            }
            if (!Schema::hasColumn('yks_bookings', 'group_name')) {
                $table->string('group_name')->nullable()->after('customer_email');
            }
            if (!Schema::hasColumn('yks_bookings', 'start_time')) {
                $table->string('start_time')->default('08.00–12.00')->after('trip_date');
            }
            if (!Schema::hasColumn('yks_bookings', 'vehicle_count')) {
                $table->unsignedSmallInteger('vehicle_count')->default(1)->after('participant_count');
            }
            if (!Schema::hasColumn('yks_bookings', 'meeting_point')) {
                $table->string('meeting_point')->nullable()->after('vehicle_count');
            }
            if (!Schema::hasColumn('yks_bookings', 'package_agreed_price')) {
                $table->unsignedInteger('package_agreed_price')->default(0)->after('meeting_point');
            }
            if (!Schema::hasColumn('yks_bookings', 'addon_price')) {
                $table->unsignedInteger('addon_price')->default(0)->after('package_agreed_price');
            }
            if (!Schema::hasColumn('yks_bookings', 'addon_notes')) {
                $table->string('addon_notes')->nullable()->after('addon_price');
            }
            if (!Schema::hasColumn('yks_bookings', 'paid_amount')) {
                $table->unsignedInteger('paid_amount')->default(0)->after('total_price');
            }
            if (!Schema::hasColumn('yks_bookings', 'reservation_status')) {
                $table->string('reservation_status')->default('Menunggu pembayaran')->after('payment_status');
            }
            if (!Schema::hasColumn('yks_bookings', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('notes');
            }
        });

        Schema::table('yks_testimonials', function (Blueprint $table) {
            if (!Schema::hasColumn('yks_testimonials', 'trip_date')) {
                $table->date('trip_date')->nullable()->after('package_name');
            }
            if (!Schema::hasColumn('yks_testimonials', 'is_approved')) {
                $table->boolean('is_approved')->default(true)->after('rating');
            }
        });
    }

    public function down(): void
    {
        //
    }
};
