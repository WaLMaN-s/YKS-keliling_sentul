@extends('layouts.app', ['title' => 'Katalog Paket'])

@section('content')
<!-- Pembuka Halaman (Figma ON GOING #127:10923 - Katalog Paket · Desktop) -->
<div class="container" style="padding-top: 32px;">
    <div style="font-size: 0.8rem; color: #667066; margin-bottom: 12px;">
        <a href="{{ route('home') }}" style="color: #667066; text-decoration: none;">Beranda</a> &nbsp;/&nbsp; <span>Katalog Paket</span>
    </div>
    <div style="font-weight: 700; font-size: 1.8rem; color: #6B4F3A; margin-bottom: 24px;">
        <span style="color: #F26B1D;">━ &nbsp;</span>KATALOG PAKET
    </div>

    <!-- Kolom Pencarian Paket (#127:10943) -->
    <form method="GET" action="{{ route('packages.index') }}" style="margin-bottom: 24px;">
        @if(request('kategori'))
            <input type="hidden" name="kategori" value="{{ request('kategori') }}">
        @endif
        <div style="background: #FFFFFF; border: 1px solid #DEDED4; border-radius: 12px; height: 56px; display: flex; align-items: center; padding: 0 18px; gap: 12px;">
            <i class="bi bi-search" style="font-size: 1.15rem; color: #667066;"></i>
            <input type="text" name="cari" value="{{ $search ?? request('cari') }}"
                   placeholder="Cari paket atau pengalaman..."
                   style="border: none; outline: none; background: transparent; width: 100%; font-size: 1.05rem; color: #1F3D2B;">
            @if(!empty($search) || request('cari'))
                <a href="{{ route('packages.index', request()->only('kategori')) }}" style="font-size: 0.85rem; color: #667066; text-decoration: none;">Reset</a>
            @endif
            <button type="submit" class="btn btn-forest btn-sm" style="padding: 8px 18px; border-radius: 8px;">Cari</button>
        </div>
    </form>

    <!-- Filter Kategori Paket (#294:17: Semua paket, Trail, Jeep, Motor pribadi, Private Trip) -->
    <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 16px;">
        <a href="{{ route('packages.index', request()->except('kategori')) }}"
           style="padding: 7px 14px; border-radius: 100px; font-size: 0.78rem; font-weight: 600; text-decoration: none;
                  background: {{ !$categorySlug || $categorySlug === 'all' ? '#1F3D2B' : '#EAF0E7' }};
                  color: {{ !$categorySlug || $categorySlug === 'all' ? '#FFFFFF' : '#1F3D2B' }};">
            Semua paket
        </a>
        @foreach ($categories as $cat)
            <a href="{{ route('packages.index', array_merge(request()->all(), ['kategori' => $cat->slug])) }}"
               style="padding: 7px 14px; border-radius: 100px; font-size: 0.78rem; font-weight: 600; text-decoration: none;
                      background: {{ $categorySlug === $cat->slug ? '#1F3D2B' : '#EAF0E7' }};
                      color: {{ $categorySlug === $cat->slug ? '#FFFFFF' : '#1F3D2B' }};">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>

    <!-- Jumlah dan Urutan (#127:11004) -->
    <div style="font-size: 0.85rem; color: #667066; margin-bottom: 24px;">
        Menampilkan {{ $packages->count() }} paket · Trail, jeep, motor pribadi & Private Trip
    </div>
</div>

<!-- Hasil Katalog Paket 2 Kolom per Baris (#127:11010) -->
<section class="section-wrapper" style="padding-top: 0;">
    <div class="container">
        @if ($packages->isEmpty())
            <div style="text-align: center; padding: 64px 20px; background: #FFFFFF; border-radius: 16px; border: 1px dashed #DEDED4;">
                <i class="bi bi-compass" style="font-size: 48px; color: #6B4F3A;"></i>
                <h3 style="margin-top: 14px; color: #1F3D2B;">Tidak ada paket yang sesuai kriteria</h3>
                <p style="color: #667066; margin-bottom: 20px;">Silakan atur ulang kata kunci pencarian atau filter kategori di atas.</p>
                <a href="{{ route('packages.index') }}" class="btn btn-forest">Lihat Semua Paket</a>
            </div>
        @else
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 24px;">
                @foreach ($packages as $pkg)
                    @php
                        $badgeText = $pkg->name;
                        if (stripos($pkg->name, 'Paket 1') !== false) $badgeText = 'Paket 1 · 150cc';
                        elseif (stripos($pkg->name, 'Paket 2') !== false) $badgeText = 'Paket 2 · 140cc';
                        elseif (stripos($pkg->name, 'Paket 3') !== false) $badgeText = 'Paket 3 · 300cc';
                        elseif (stripos($pkg->name, 'Jeep') !== false) $badgeText = 'Jeep';
                        elseif ($pkg->is_motor_pribadi) $badgeText = 'Bawa motor sendiri';
                        elseif ($pkg->is_private_trip) $badgeText = 'Private Trip';
                    @endphp
                    <div style="background: #FFFFFF; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 28px rgba(31,61,43,0.05); border: 1px solid #ECECE4; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <!-- Sampul Foto (242px) -->
                            <div style="height: 242px; position: relative; overflow: hidden;">
                                <img src="{{ $pkg->image ?: '/galeri/Untitled.jpg' }}" alt="{{ $pkg->name }}"
                                     style="width: 100%; height: 100%; object-fit: cover;" loading="lazy">
                                <div style="position: absolute; top: 16px; left: 16px; background: rgba(31,61,43,0.92); color: #FFFFFF; font-size: 0.72rem; font-weight: 600; padding: 7px 11px; border-radius: 8px;">
                                    {{ $badgeText }}
                                </div>
                            </div>

                            <!-- Isi Paket -->
                            <div style="padding: 24px; display: flex; flex-direction: column; gap: 14px;">
                                <div style="font-size: 0.78rem; color: #667066;">
                                    @if($pkg->is_private_trip)
                                        Rombongan privat · Rencana fleksibel
                                    @elseif($pkg->is_motor_pribadi)
                                        Motor pribadi · Rute sesuai konfirmasi
                                    @elseif(stripos($pkg->name, 'Jeep') !== false)
                                        Maks. 4 peserta / jip
                                    @else
                                        {{ $pkg->duration }} · {{ $pkg->distance }} · {{ $pkg->capacity_label ?: 'Min. 1 orang' }}
                                    @endif
                                </div>

                                <h3 style="font-family: var(--font-heading); font-size: 1.5rem; font-weight: 800; color: #1F3D2B; line-height: 1.15; margin: 0;">
                                    {{ $pkg->name }}
                                </h3>

                                <p style="font-size: 0.9rem; color: #667066; line-height: 1.65; margin: 0;">
                                    {{ Str::limit($pkg->description, 155) }}
                                </p>

                                <div style="font-size: 0.72rem; color: #667066;">
                                    @if($pkg->is_private_trip)
                                        Tidak tersedia booking langsung
                                    @elseif($pkg->is_motor_pribadi)
                                        Motor & BBM disiapkan peserta · Add-on opsional
                                    @elseif(stripos($pkg->name, 'Jeep') !== false)
                                        Jip · Pemandu · Air mineral
                                    @else
                                        Guide · Helm + Sepatu Safety · Jersey Set · BBM · Air Mineral · Dokumentasi
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div style="padding: 0 24px 24px; display: flex; flex-direction: column; gap: 16px;">
                            <div style="height: 1px; background: #DEDED4;"></div>

                            @if($pkg->is_private_trip)
                                <div style="font-family: var(--font-heading); font-size: 1.15rem; font-weight: 800; color: #1F3D2B;">
                                    Harga & tanggal: konsultasi admin
                                </div>
                                <a href="https://wa.me/{{ env('WHATSAPP_NUMBER', '6281210842032') }}?text=Halo%20Admin%20YKS,%20saya%20ingin%20konsultasi%20harga%20dan%20tanggal%20untuk%20Private%20Trip"
                                   target="_blank"
                                   style="display: flex; align-items: center; justify-content: center; min-height: 50px; padding: 14px 20px; border-radius: 12px; background: #1F3D2B; border: 1px solid #DEDED4; color: #FFFFFF; font-size: 0.9rem; font-weight: 700; text-decoration: none;">
                                    Chat Admin via WhatsApp &nbsp;↗
                                </a>
                            @else
                                <div style="font-family: var(--font-heading); font-weight: 800; color: #1F3D2B;">
                                    <span style="font-size: 1.35rem;">Rp{{ number_format($pkg->price, 0, ',', '.') }}</span>
                                    @if(!$pkg->is_motor_pribadi)
                                        <span style="font-family: var(--font-body); font-size: 0.76rem; font-weight: 400; color: #667066;">
                                            / {{ $pkg->price_unit === 'jeep' ? 'jeep' : 'orang' }}
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ route('packages.show', $pkg->slug) }}"
                                   style="display: flex; align-items: center; justify-content: center; min-height: 50px; padding: 14px 20px; border-radius: 12px; background: #FFFFFF; border: 1px solid #DEDED4; color: #1F3D2B; font-size: 0.9rem; font-weight: 700; text-decoration: none;">
                                    Lihat Detail &nbsp;→
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Catatan Penting (#127:11072) -->
        <div style="background: #F3E6D5; border-radius: 12px; padding: 16px 20px; margin-top: 28px; font-size: 0.8rem; color: #6B4F3A; line-height: 1.65;">
            ⓘ &nbsp;Trail dihitung per orang; jeep per jeep (maks. 4 peserta). Paket sewa trail tetap termasuk guide, BBM, helm + sepatu safety, jersey set, air mineral, dan dokumentasi. Bawa Motor Pribadi Rp200.000; add-on terpisah dan opsional.
        </div>

        <!-- Penomoran Halaman (#127:11074) -->
        <div style="display: flex; align-items: center; gap: 12px; margin-top: 20px;">
            <span style="background: #1F3D2B; color: #FFFFFF; font-size: 0.74rem; font-weight: 600; padding: 6px 12px; border-radius: 100px;">1</span>
            <span style="font-size: 0.8rem; color: #667066;">Semua paket sudah ditampilkan.</span>
        </div>
    </div>
</section>
@endsection
