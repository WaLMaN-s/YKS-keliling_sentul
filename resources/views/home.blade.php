@extends('layouts.app')

@section('content')
<!-- Beranda · Desktop (Figma ON GOING #73:6977): Hero layar penuh + 2 sorotan menyatu dengan footer -->
<section class="home-hero">
    <div class="container home-hero-inner">
        <div class="home-hero-tag">
            <i class="bi bi-geo-alt"></i>
            <span>SENTUL, JAWA BARAT · JELAJAH TANPA BIASA</span>
        </div>

        <h1 class="home-hero-title">
            Jelajahi Lebih bebas
            <span>Dengan Pilihan motorMu</span>
        </h1>

        <p class="home-hero-lead">
            Lumpur di ban, cerita di ingatan. Jelajahi Sentul dengan sewa trail, jeep, atau motor pribadimu.
        </p>

        <div class="home-hero-actions">
            <a href="{{ route('booking.create') }}" class="btn btn-primary">
                Reservasi Sekarang &nbsp;↗
            </a>
            <a href="{{ route('packages.index') }}" class="btn btn-outline-white">
                Jelajahi Paket &nbsp;↗
            </a>
        </div>

        <div class="home-hero-meta">
            <span><i class="bi bi-geo-alt"></i> Sentul · Bogor · Indonesia</span>
            <span>01 — 03</span>
        </div>
    </div>
</section>

<!-- Sorotan (menyatu dengan warna footer) -->
<section class="home-highlights">
    <div class="container home-highlights-inner">
        <div class="home-highlight-item">
            <i class="bi bi-signpost-split"></i>
            <div>
                <strong>Jalur alam pilihan</strong>
                <span>Hutan, sungai, dan perbukitan</span>
            </div>
        </div>
        <div class="home-highlight-item">
            <i class="bi bi-shield-check"></i>
            <div>
                <strong>Berangkat lebih tenang</strong>
                <span>Pengarahan sebelum perjalanan</span>
            </div>
        </div>
    </div>
</section>
@endsection
