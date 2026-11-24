@extends('layouts.app', ['title' => $package->name])

@section('content')
@php
    $includes = array_filter(explode("\n", str_replace("\r", "", $package->includes)));
@endphp

<!-- Pembuka Detail Paket (Figma ON GOING #73:5543) -->
<div class="container" style="padding-top: 32px; padding-bottom: 24px;">
    <div style="font-size: 0.82rem; color: #667066; margin-bottom: 14px;">
        <a href="{{ route('home') }}" style="color: #667066; text-decoration: none;">Beranda</a> &nbsp;/&nbsp;
        <a href="{{ route('packages.index') }}" style="color: #667066; text-decoration: none;">Katalog Paket</a> &nbsp;/&nbsp;
        <span>{{ $package->name }}</span>
    </div>

    <h1 style="font-family: var(--font-heading); font-size: 3.2rem; font-weight: 800; color: #1F3D2B; line-height: 1.15; margin-bottom: 16px;">
        {{ $package->name }}
    </h1>

    <div style="display: flex; flex-wrap: wrap; gap: 18px; align-items: center; font-size: 0.85rem; color: #667066;">
        <span><i class="bi bi-geo-alt"></i> Sentul, Bogor</span>
        <span><i class="bi bi-clock"></i> {{ $package->duration }}</span>
        <span><i class="bi bi-people"></i> {{ $package->capacity_label ?: $package->distance }}</span>
    </div>
</div>

<!-- Galeri Paket (#73:5561) -->
<div class="container" style="margin-bottom: 48px;">
    <div class="pkg-hero-gallery">
        <div class="pkg-hero-main-img" style="position: relative; border-radius: 16px; overflow: hidden;">
            <img src="{{ $package->image ?: '/galeri/Untitled3.jpg' }}" alt="{{ $package->name }}" style="width: 100%; height: 100%; object-fit: cover;">
            <a href="{{ route('gallery') }}"
               style="position: absolute; bottom: 16px; right: 16px; background: #FFFFFF; border: 1px solid #DEDED4; border-radius: 12px; padding: 12px 20px; font-size: 0.88rem; font-weight: 700; color: #1F3D2B; text-decoration: none;">
                Lihat Semua Foto &nbsp;↗
            </a>
        </div>
        <div class="pkg-hero-side-grid">
            <div class="pkg-hero-side-img" style="border-radius: 16px; overflow: hidden;">
                <img src="/galeri/Untitled6.jpg" alt="Jalur Sentul YKS" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div class="pkg-hero-side-img" style="border-radius: 16px; overflow: hidden;">
                <img src="/galeri/Untitled8.jpg" alt="Petualangan YKS" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
        </div>
    </div>
</div>

<!-- Informasi dan Reservasi (#73:5570) -->
<section class="section-wrapper" style="padding-top: 0;">
    <div class="container">
        <div class="booking-layout-grid" style="gap: 48px;">
            <!-- Kolom Kiri: Rincian Perjalanan (#73:5571) -->
            <div style="display: flex; flex-direction: column; gap: 40px;">
                <!-- Tentang Paket -->
                <div>
                    <div style="font-size: 0.72rem; font-weight: 700; color: #6B4F3A; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 12px;">
                        <span style="color: #F26B1D;">━ &nbsp;</span>KENALAN DENGAN JALURNYA
                    </div>
                    <div style="font-size: 1rem; color: #667066; line-height: 1.7;">
                        {!! nl2br(e($package->description)) !!}
                    </div>
                </div>

                <!-- Sudah Termasuk (#73:5577) -->
                <div>
                    <h2 style="font-family: var(--font-heading); font-size: 1.75rem; font-weight: 800; color: #1F3D2B; margin-bottom: 22px;">
                        Sudah termasuk
                    </h2>
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        @foreach ($includes as $idx => $inc)
                            @php
                                $icons = ['bi-truck', 'bi-compass', 'bi-droplet', 'bi-camera', 'bi-shield-check', 'bi-fuel-pump', 'bi-check2-circle'];
                                $icon = $icons[$idx % count($icons)];
                            @endphp
                            <div style="display: flex; gap: 16px; align-items: flex-start;">
                                <div style="width: 44px; height: 44px; border-radius: 12px; background: #EAF0E7; color: #1F3D2B; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">
                                    <i class="bi {{ $icon }}"></i>
                                </div>
                                <div>
                                    <div style="font-size: 0.96rem; font-weight: 600; color: #1F3D2B; margin-bottom: 2px;">
                                        {{ $inc }}
                                    </div>
                                    <div style="font-size: 0.82rem; color: #667066;">
                                        Disediakan sesuai standar keselamatan dan kenyamanan perjalanan YKS.
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Pilihan Tambahan · Bawa Motor Pribadi (Figma ON GOING #202:123) -->
                <div style="background: #1F3D2B; border-radius: 16px; padding: 32px; color: #FFFFFF; display: flex; flex-direction: column; gap: 14px;">
                    <div style="font-size: 0.75rem; font-weight: 600; color: #F3B77A; letter-spacing: 0.06em;">
                        PILIHAN PAKET LAINNYA
                    </div>
                    <h3 style="font-size: 1.75rem; font-weight: 700; color: #FFFFFF; margin: 0;">
                        Bawa Motor Pribadi
                    </h3>
                    <p style="font-size: 0.94rem; color: #DFE7DF; line-height: 1.55; margin: 0;">
                        Harga dasar Rp200.000. Motor dan BBM disiapkan peserta. Rute dikonfirmasi admin; add-on berikut opsional.
                    </p>

                    <div style="border-top: 1px solid rgba(255,255,255,0.12); padding-top: 12px; display: flex; flex-direction: column; gap: 12px;">
                        <div style="display: flex; justify-content: space-between; font-size: 1.05rem; font-weight: 600;">
                            <span>Sepatu</span>
                            <span>Rp100.000</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 1.05rem; font-weight: 600;">
                            <span>Guide</span>
                            <span>Rp200.000</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 1.05rem; font-weight: 600;">
                            <span>Paket protector</span>
                            <span>Rp250.000</span>
                        </div>
                    </div>

                    <div style="font-size: 1rem; font-weight: 600; color: #F3B77A; margin-top: 4px;">
                        Total semua add-on: Rp550.000
                    </div>
                    <p style="font-size: 0.82rem; color: #DFE7DF; margin: 0; line-height: 1.5;">
                        Harga dasar + semua add-on: Rp750.000. Guide dan perlengkapan opsional. Fasilitas sewa trail tetap mengikuti katalog.
                    </p>

                    <a href="https://wa.me/{{ env('WHATSAPP_NUMBER', '6281210842032') }}?text=Halo%20Admin%20YKS,%20saya%20ingin%20tanya%20paket%20Bawa%20Motor%20Pribadi%20beserta%20add-on"
                       target="_blank"
                       style="display: flex; align-items: center; justify-content: center; min-height: 50px; padding: 14px 20px; background: #FFFFFF; border: 1px solid #DEDED4; border-radius: 12px; color: #1F3D2B; font-size: 0.9rem; font-weight: 700; text-decoration: none; margin-top: 4px;">
                        Tanya Admin untuk Motor Pribadi &nbsp;→
                    </a>
                </div>

                <!-- Ketentuan dan Persiapan (#73:5630 - Siap sebelum berangkat.) -->
                <div>
                    <h2 style="font-family: var(--font-heading); font-size: 1.75rem; font-weight: 800; color: #1F3D2B; margin-bottom: 12px;">
                        Siap sebelum berangkat.
                    </h2>

                    <div style="border-bottom: 1px solid #DEDED4; padding: 20px 0;">
                        <div style="font-size: 0.95rem; font-weight: 600; color: #1F3D2B; margin-bottom: 8px; display: flex; justify-content: space-between;">
                            <span>Ketentuan peserta</span>
                            <span style="color: #6B4F3A;">−</span>
                        </div>
                        <p style="font-size: 0.88rem; color: #667066; line-height: 1.65; margin: 0;">
                            Maksimal 4 peserta per jeep (untuk paket jeep) atau sesuai ketentuan minimal peserta paket trail. Sampaikan usia anak, kondisi kesehatan, kehamilan, atau kebutuhan khusus kepada admin agar kesesuaian perjalanan bisa dinilai sebelum reservasi.
                        </p>
                    </div>

                    <div style="border-bottom: 1px solid #DEDED4; padding: 20px 0;">
                        <div style="font-size: 0.95rem; font-weight: 600; color: #1F3D2B; margin-bottom: 8px; display: flex; justify-content: space-between;">
                            <span>Aturan reservasi & pembayaran</span>
                            <span style="color: #6B4F3A;">−</span>
                        </div>
                        <p style="font-size: 0.88rem; color: #667066; line-height: 1.65; margin: 0;">
                            Pilih jadwal, isi data pemesan, lalu transfer sesuai ringkasan. Jadwal baru dikunci setelah pembayaran diperiksa dan reservasi dikonfirmasi admin. Jangan transfer ke rekening yang belum dikonfirmasi.
                        </p>
                    </div>

                    <div style="border-bottom: 1px solid #DEDED4; padding: 20px 0;">
                        <div style="font-size: 0.95rem; font-weight: 600; color: #6B4F3A; margin-bottom: 8px; display: flex; justify-content: space-between;">
                            <span>Perubahan jadwal, pembatalan & cuaca</span>
                            <span>+</span>
                        </div>
                        <p style="font-size: 0.88rem; color: #667066; line-height: 1.65; margin: 0;">
                            Perubahan jadwal dapat dilakukan maksimal H-2 sebelum tanggal keberangkatan. Pembatalan pada hari keberangkatan (H) menyebabkan pembayaran hangus dan tidak dapat dikembalikan. Apabila terdapat perubahan kondisi cuaca, silakan menghubungi admin untuk mendapatkan informasi lebih lanjut terkait perjalanan.
                        </p>
                    </div>

                    <div style="border-bottom: 1px solid #DEDED4; padding: 20px 0;">
                        <div style="font-size: 0.95rem; font-weight: 600; color: #1F3D2B; margin-bottom: 8px; display: flex; justify-content: space-between;">
                            <span>Perlengkapan yang perlu dibawa</span>
                            <span style="color: #6B4F3A;">+</span>
                        </div>
                        <p style="font-size: 0.88rem; color: #667066; line-height: 1.65; margin: 0;">
                            Peserta disarankan membawa pakaian yang nyaman, alas kaki yang sesuai untuk aktivitas alam, pakaian ganti, serta perlengkapan pribadi lainnya. Fasilitas basecamp meliputi toilet, musala, warung, serta area parkir motor dan mobil.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Kartu Pemesanan Melekat (#73:5642) -->
            <div>
                <div style="background: #FFFFFF; border: 1px solid #DEDED4; border-radius: 16px; padding: 28px; box-shadow: 0 8px 28px rgba(31,61,43,0.05); position: sticky; top: 108px; display: flex; flex-direction: column; gap: 20px;">
                    <div style="font-size: 0.7rem; font-weight: 700; color: #6B4F3A; letter-spacing: 1.8px; text-transform: uppercase;">
                        <span style="color: #F26B1D;">━ &nbsp;</span>PAKET {{ strtoupper($package->name) }}
                    </div>

                    <div>
                        @if($package->is_private_trip)
                            <div style="font-family: var(--font-heading); font-size: 1.5rem; font-weight: 800; color: #1F3D2B;">
                                Harga & tanggal: konsultasi admin
                            </div>
                            <div style="font-size: 0.8rem; color: #667066; margin-top: 4px;">
                                Tidak tersedia booking langsung · Konsultasi via WhatsApp
                            </div>
                        @else
                            <div style="font-family: var(--font-heading); font-size: 2rem; font-weight: 800; color: #1F3D2B;">
                                Rp{{ number_format($package->price, 0, ',', '.') }}
                            </div>
                            <div style="font-size: 0.8rem; color: #667066; margin-top: 4px;">
                                per {{ $package->price_unit ?: 'orang' }} · {{ $package->capacity_label ?: $package->distance }}
                            </div>
                        @endif
                    </div>

                    <div style="height: 1px; background: #DEDED4;"></div>

                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <i class="bi bi-clock" style="font-size: 1.15rem; color: #1F3D2B;"></i>
                        <div>
                            <div style="font-size: 0.72rem; color: #667066;">Durasi perjalanan</div>
                            <div style="font-size: 0.9rem; font-weight: 600; color: #1F3D2B;">{{ $package->duration }}</div>
                        </div>
                    </div>

                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <i class="bi bi-people" style="font-size: 1.15rem; color: #1F3D2B;"></i>
                        <div>
                            <div style="font-size: 0.72rem; color: #667066;">Kapasitas</div>
                            <div style="font-size: 0.9rem; font-weight: 600; color: #1F3D2B;">{{ $package->capacity_label ?: $package->distance }}</div>
                        </div>
                    </div>

                    @if($package->is_private_trip)
                        <a href="https://wa.me/{{ env('WHATSAPP_NUMBER', '6281210842032') }}?text=Halo%20Admin%20YKS,%20saya%20ingin%20konsultasi%20Private%20Trip"
                           target="_blank"
                           style="display: flex; align-items: center; justify-content: center; min-height: 50px; padding: 14px 20px; border-radius: 12px; background: #1F3D2B; color: #FFFFFF; font-size: 0.9rem; font-weight: 700; text-decoration: none;">
                            Chat Admin via WhatsApp &nbsp;↗
                        </a>
                    @else
                        <a href="{{ route('booking.create', ['paket_id' => $package->id]) }}"
                           style="display: flex; align-items: center; justify-content: center; min-height: 50px; padding: 14px 20px; border-radius: 12px; background: #F26B1D; color: #1A1A1A; font-size: 0.9rem; font-weight: 700; text-decoration: none;">
                            Pilih Jadwal &nbsp;↗
                        </a>
                    @endif

                    <a href="https://wa.me/{{ env('WHATSAPP_NUMBER', '6281210842032') }}?text=Halo%20Admin%20YKS,%20saya%20ingin%20tanya%20paket%20{{ urlencode($package->name) }}"
                       target="_blank"
                       style="display: flex; align-items: center; justify-content: center; min-height: 50px; padding: 14px 20px; border-radius: 12px; background: #FFFFFF; border: 1px solid #DEDED4; color: #1F3D2B; font-size: 0.9rem; font-weight: 700; text-decoration: none;">
                        Tanya Admin &nbsp;↗
                    </a>

                    <p style="font-size: 0.78rem; color: #667066; line-height: 1.6; margin: 0;">
                        Belum ada tagihan saat memilih jadwal. Ketersediaan mengikuti konfirmasi admin.
                    </p>

                    <div style="background: #F3E6D5; border-radius: 12px; padding: 14px 16px; font-size: 0.78rem; color: #6B4F3A; line-height: 1.6;">
                        ⓘ &nbsp;Rute dapat berubah demi keselamatan. Makanan, transportasi ke lokasi, dan kebutuhan pribadi belum termasuk.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
