<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('yks_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('yks_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('yks_categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->unsignedInteger('price')->default(0);
            $table->string('price_unit')->default('orang');
            $table->string('booking_mode')->default('direct'); // 'direct' or 'whatsapp_only'
            $table->string('consultation_target')->nullable();
            $table->string('duration')->default('4 - 5 Jam');
            $table->string('distance')->default('4.5 Km PP');
            $table->string('capacity_label')->nullable();
            $table->enum('difficulty', ['Mudah', 'Sedang', 'Menantang'])->default('Mudah');
            $table->string('meeting_point');
            $table->text('includes');
            $table->text('excludes');
            $table->text('itinerary');
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('yks_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('package_id')->nullable()->constrained('yks_packages')->nullOnDelete();
            $table->string('custom_package_name')->nullable();
            $table->string('booking_source')->default('Website'); // 'Website' or 'Non-Website'
            $table->string('booking_channel')->default('Website'); // 'Website', 'WhatsApp', 'Telepon', 'Datang langsung'
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->string('group_name')->nullable();
            $table->date('trip_date');
            $table->string('start_time')->default('08.00–12.00');
            $table->unsignedSmallInteger('participant_count')->default(1);
            $table->unsignedSmallInteger('vehicle_count')->default(1);
            $table->string('meeting_point')->nullable();
            $table->unsignedInteger('package_agreed_price')->default(0);
            $table->unsignedInteger('addon_price')->default(0);
            $table->string('addon_notes')->nullable();
            $table->unsignedInteger('total_price');
            $table->unsignedInteger('paid_amount')->default(0);
            $table->string('payment_method')->default('Transfer Bank BCA');
            $table->string('payment_proof')->nullable();
            $table->string('payment_status')->default('Menunggu Pembayaran');
            $table->string('reservation_status')->default('Menunggu pembayaran');
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('yks_testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('city')->nullable();
            $table->string('package_name');
            $table->date('trip_date')->nullable();
            $table->text('review');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yks_testimonials');
        Schema::dropIfExists('yks_bookings');
        Schema::dropIfExists('yks_packages');
        Schema::dropIfExists('yks_categories');
    }
};
