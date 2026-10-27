<?php $__env->startSection('content'); ?>
<section class="section" style="min-height: 75vh; display: flex; align-items: center;">
    <div class="container" style="max-width: 440px;">
        <div style="background: #fff; border-radius: var(--radius-lg); border: 1px solid var(--border); padding: 36px 30px; box-shadow: var(--shadow-md);">
            <div style="text-align: center; margin-bottom: 24px;">
                <div class="logo-icon" style="margin: 0 auto 12px; width: 48px; height: 48px;">
                    <i class="bi bi-person-fill"></i>
                </div>
                <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--dark); margin-bottom: 4px;">Masuk Akun</h2>
                <p style="font-size: 0.88rem; color: var(--text-muted);">Akses riwayat trip dan status booking Anda.</p>
            </div>

            <form method="POST" action="<?php echo e(route('login')); ?>">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label class="form-label">Alamat Email</label>
                    <input type="email" name="email" class="form-control" placeholder="nama@email.com" required autofocus value="<?php echo e(old('email')); ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg mt-2">
                    <i class="bi bi-box-arrow-in-right"></i> Masuk Sekarang
                </button>
            </form>

            <div style="text-align: center; margin-top: 24px; padding-top: 18px; border-top: 1px solid var(--border-light); font-size: 0.88rem; color: var(--text-muted);">
                Belum punya akun? <a href="<?php echo e(route('register')); ?>" style="color: var(--primary); font-weight: 700;">Daftar di sini</a>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Masuk Akun'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/man/WEB/YKS-keliling_sentul/resources/views/auth/login.blade.php ENDPATH**/ ?>