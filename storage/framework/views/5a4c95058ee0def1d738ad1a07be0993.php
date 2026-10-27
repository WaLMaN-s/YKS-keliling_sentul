<?php $__env->startSection('content'); ?>
<div style="background: var(--dark); color: #fff; padding: 48px 0; text-align: center;">
    <div class="container">
        <h1 style="font-size: 2.4rem; font-weight: 800; margin-bottom: 10px;">Semua Paket Wisata Sentul</h1>
        <p style="color: #cbd5e1; max-width: 600px; margin: 0 auto;">Pilih paket petualangan terbaik yang paling cocok dengan waktu dan preferensi fisik Anda.</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <!-- Filter Tabs -->
        <div class="filter-pills" style="margin-bottom: 36px;">
            <a href="<?php echo e(route('packages.index')); ?>" class="filter-pill <?php echo e(!$categorySlug || $categorySlug === 'all' ? 'active' : ''); ?>">Semua Kategori</a>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('packages.index', ['kategori' => $cat->slug])); ?>" 
                   class="filter-pill <?php echo e($categorySlug === $cat->slug ? 'active' : ''); ?>">
                    <?php echo e($cat->name); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <?php if($packages->isEmpty()): ?>
            <div style="text-align: center; padding: 60px 20px; background: #fff; border-radius: var(--radius-lg); border: 1px dashed var(--border);">
                <i class="bi bi-inbox text-muted" style="font-size: 48px;"></i>
                <h3 style="margin-top: 14px; color: var(--dark);">Belum Ada Paket di Kategori Ini</h3>
                <p style="color: var(--text-muted); margin-bottom: 20px;">Silakan pilih kategori lain atau hubungi kami via WhatsApp.</p>
                <a href="<?php echo e(route('packages.index')); ?>" class="btn btn-primary">Lihat Semua Paket</a>
            </div>
        <?php else: ?>
            <div class="cards-grid">
                <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pkg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="tour-card">
                        <div class="tour-card-img">
                            <img src="<?php echo e($pkg->image ?: 'https://images.unsplash.com/photo-1544644181-1484b3fdfc62?auto=format&fit=crop&w=800&q=80'); ?>" alt="<?php echo e($pkg->name); ?>" loading="lazy">
                            <div class="card-badge-top">
                                <?php if($pkg->difficulty === 'Mudah'): ?>
                                    <span class="badge badge-easy"><i class="bi bi-shield-check"></i> Mudah / Pemula</span>
                                <?php elseif($pkg->difficulty === 'Sedang'): ?>
                                    <span class="badge badge-medium"><i class="bi bi-lightning-charge"></i> Sedang</span>
                                <?php else: ?>
                                    <span class="badge badge-hard"><i class="bi bi-fire"></i> Menantang</span>
                                <?php endif; ?>
                            </div>
                            <div class="card-price-tag">
                                Rp <?php echo e(number_format($pkg->price, 0, ',', '.')); ?> <small>/ orang</small>
                            </div>
                        </div>
                        <div class="tour-card-body">
                            <span style="font-size: 0.78rem; text-transform: uppercase; font-weight: 800; color: var(--primary); letter-spacing: 0.05em; margin-bottom: 4px;">
                                <?php echo e($pkg->category->name ?? 'Wisata'); ?>

                            </span>
                            <h3 class="card-title"><?php echo e($pkg->name); ?></h3>
                            <p class="card-desc"><?php echo e($pkg->description); ?></p>
                            
                            <div class="card-meta">
                                <span class="card-meta-item"><i class="bi bi-clock-history"></i> <?php echo e($pkg->duration); ?></span>
                                <span class="card-meta-item"><i class="bi bi-signpost-2"></i> <?php echo e($pkg->distance); ?></span>
                                <span class="card-meta-item"><i class="bi bi-geo-alt"></i> Sentul</span>
                            </div>

                            <div class="card-action">
                                <a href="<?php echo e(route('packages.show', $pkg->slug)); ?>" class="btn btn-outline btn-block">Detail Trip</a>
                                <a href="<?php echo e(route('booking.create', ['paket_id' => $pkg->id])); ?>" class="btn btn-primary btn-block">Pesan</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/man/WEB/YKS-keliling_sentul/resources/views/packages/index.blade.php ENDPATH**/ ?>