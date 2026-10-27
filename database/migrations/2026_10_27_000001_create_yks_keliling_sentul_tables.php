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
            $table->unsignedInteger('price');
            $table->string('duration')->default('4 - 5 Jam');
            $table->string('distance')->default('4.5 Km PP');
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
            $table->foreignId('package_id')->constrained('yks_packages')->cascadeOnDelete();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email');
            $table->date('trip_date');
            $table->unsignedSmallInteger('participant_count')->default(1);
            $table->unsignedInteger('total_price');
            $table->string('payment_method')->default('Transfer Bank BCA');
            $table->string('payment_proof')->nullable();
            $table->enum('payment_status', ['Menunggu Pembayaran', 'Menunggu Verifikasi', 'Lunas', 'Dibatalkan'])->default('Menunggu Pembayaran');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('yks_testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('city');
            $table->string('package_name');
            $table->text('review');
            $table->unsignedTinyInteger('rating')->default(5);
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
