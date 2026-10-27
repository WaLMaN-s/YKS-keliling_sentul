<?php $__env->startSection('content'); ?>
<div style="background: var(--dark); color: #fff; padding: 48px 0; text-align: center;">
    <div class="container">
        <h1 style="font-size: 2.3rem; font-weight: 800; margin-bottom: 10px;">Lacak Status Booking</h1>
        <p style="color: #cbd5e1; max-width: 550px; margin: 0 auto;">Cek status pembayaran, jadwal guide, dan detail meeting point Anda secara real-time.</p>
    </div>
</div>

<section class="section">
    <div class="container" style="max-width: 760px;">
        <!-- Search Box -->
        <div style="background: #fff; border-radius: var(--radius-lg); border: 1px solid var(--border); padding: 24px; box-shadow: var(--shadow-sm); margin-bottom: 32px;">
            <form method="GET" action="<?php echo e(route('booking.track')); ?>">
                <label class="form-label">Masukkan Kode Booking atau Nomor WhatsApp</label>
                <div style="display: flex; gap: 10px;">
                    <input type="text" name="kode" class="form-control" placeholder="Contoh: YKS-20261027-A8C2 atau 08123456789" value="<?php echo e($kode ?? ''); ?>" required>
                    <button type="submit" class="btn btn-primary" style="padding: 0 24px; white-space: nowrap;">
                        <i class="bi bi-search"></i> Cari
                    </button>
                </div>
                <small class="text-muted" style="display:block; margin-top: 6px;">*Kode booking dapat dilihat pada instruksi pembayaran atau pesan konfirmasi.</small>
            </form>
        </div>

        <?php if($kode && !$booking): ?>
            <div class="alert alert-warning" style="justify-content: center; text-align: center;">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>Pesanan dengan kode / nomor <strong><?php echo e($kode); ?></strong> tidak ditemukan. Pastikan Anda mengetik dengan benar.</span>
            </div>
        <?php endif; ?>

        <?php if($booking): ?>
            <div style="background: #fff; border-radius: var(--radius-lg); border: 1px solid var(--border); overflow: hidden; box-shadow: var(--shadow-md);">
                <!-- Header Card -->
                <div style="background: var(--dark); color: #fff; padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <div>
                        <span style="font-size: 0.8rem; color: #94a3b8; text-transform: uppercase; font-weight: 700;">Kode Booking:</span>
                        <h3 style="font-size: 1.3rem; font-weight: 800; color: #fde047; margin: 0;"><?php echo e($booking->booking_code); ?></h3>
                    </div>
                    <div>
                        <span class="badge <?php echo e($booking->payment_status === 'Lunas' ? 'badge-success' : ($booking->payment_status === 'Menunggu Verifikasi' ? 'badge-warning' : 'badge-pending')); ?>">
                            <?php echo e($booking->payment_status); ?>

                        </span>
                    </div>
                </div>

                <!-- Body Card -->
                <div style="padding: 28px;">
                    <div style="margin-bottom: 24px;">
                        <span style="font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Paket Petualangan:</span>
                        <h2 style="font-size: 1.4rem; font-weight: 800; color: var(--primary); margin: 4px 0 8px;">
                            <?php echo e($booking->package->name ?? 'Paket Wisata Sentul'); ?>

                        </h2>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; background: var(--bg-body); padding: 20px; border-radius: var(--radius-md); margin-bottom: 24px;">
                        <div>
                            <span style="font-size: 0.82rem; color: var(--text-muted); display: block;">Tanggal Keberangkatan:</span>
                            <strong style="font-size: 1rem; color: var(--dark);"><i class="bi bi-calendar-check text-primary"></i> <?php echo e(\Carbon\Carbon::parse($booking->trip_date)->translatedFormat('d F Y')); ?></strong>
                        </div>
                        <div>
                            <span style="font-size: 0.82rem; color: var(--text-muted); display: block;">Jumlah Peserta:</span>
                            <strong style="font-size: 1rem; color: var(--dark);"><i class="bi bi-people text-primary"></i> <?php echo e($booking->participant_count); ?> Orang</strong>
                        </div>
                        <div>
                            <span style="font-size: 0.82rem; color: var(--text-muted); display: block;">Nama Pemesan:</span>
                            <strong style="font-size: 1rem; color: var(--dark);"><?php echo e($booking->customer_name); ?></strong>
                        </div>
                        <div>
                            <span style="font-size: 0.82rem; color: var(--text-muted); display: block;">Nomor WhatsApp:</span>
                            <strong style="font-size: 1rem; color: var(--dark);"><?php echo e($booking->customer_phone); ?></strong>
                        </div>
                        <div>
                            <span style="font-size: 0.82rem; color: var(--text-muted); display: block;">Total Tagihan:</span>
                            <strong style="font-size: 1.15rem; color: var(--primary);"><?php echo e($booking->formatted_total); ?></strong>
                        </div>
                        <div>
                            <span style="font-size: 0.82rem; color: var(--text-muted); display: block;">Metode Bayar:</span>
                            <strong style="font-size: 0.95rem; color: var(--dark);"><?php echo e($booking->payment_method); ?></strong>
                        </div>
                    </div>

                    <!-- Meeting Point Info -->
                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: var(--radius-md); padding: 16px; margin-bottom: 24px;">
                        <strong style="color: #166534; display: block; margin-bottom: 4px;"><i class="bi bi-geo-alt-fill text-primary"></i> Titik Kumpul (Meeting Point):</strong>
                        <p style="color: #14532d; font-size: 0.92rem; margin: 0;"><?php echo e($booking->package->meeting_point ?? 'Sentul, Bogor'); ?></p>
                    </div>

                    <!-- Bukti Transfer State -->
                    <div style="margin-bottom: 24px;">
                        <span style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 6px;">Bukti Pembayaran:</span>
                        <?php if($booking->payment_proof): ?>
                            <div style="display: flex; align-items: center; gap: 10px; color: var(--success); font-weight: 600;">
                                <i class="bi bi-file-earmark-check-fill" style="font-size: 20px;"></i>
                                <span>Sudah diupload (<?php echo e($booking->payment_proof); ?>)</span>
                            </div>
                        <?php else: ?>
                            <div style="display: flex; justify-content: space-between; align-items: center; background: #fffbeb; border: 1px solid #fde68a; padding: 12px 16px; border-radius: var(--radius-md);">
                                <span style="color: #92400e; font-size: 0.88rem;"><i class="bi bi-info-circle-fill"></i> Anda belum mengupload bukti transfer.</span>
                                <a href="<?php echo e(route('payment.show', $booking->booking_code)); ?>" class="btn btn-accent btn-sm">Upload Sekarang</a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Actions -->
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        <a href="https://wa.me/<?php echo e(env('WHATSAPP_NUMBER', '6281234567890')); ?>?text=Halo%20Admin%20YKS,%20saya%20mau%20konfirmasi%20jadwal%20trip%20untuk%20Kode%20Booking:%20<?php echo e(urlencode($booking->booking_code)); ?>" 
                           target="_blank" 
                           class="btn btn-primary" style="flex: 1;">
                            <i class="bi bi-whatsapp"></i> Hubungi Guide / Admin WA
                        </a>
                        <a href="<?php echo e(route('payment.show', $booking->booking_code)); ?>" class="btn btn-outline">
                            <i class="bi bi-wallet2"></i> Buka Rincian Bayar
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Lacak Status Pesanan'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/man/WEB/YKS-keliling_sentul/resources/views/bookings/track.blade.php ENDPATH**/ ?>