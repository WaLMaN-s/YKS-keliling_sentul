@extends('layouts.admin', ['title' => 'Kelola Paket'])

@section('content')
<style>
    .pkg-grid {
        display: grid;
        grid-template-columns: 1.15fr 1fr;
        gap: 24px;
        align-items: start;
    }
    @media (max-width: 1100px) {
        .pkg-grid {
            grid-template-columns: 1fr;
        }
    }
    .pkg-card {
        background: #FFFFFF;
        border: 1px solid var(--admin-border);
        border-radius: 12px;
        padding: 24px;
    }
    .pkg-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 14px;
        border: 1px solid #ECECE6;
        border-radius: 10px;
        background: #FFFFFF;
        transition: all 0.18s ease;
    }
    .pkg-item:hover {
        border-color: #C1D1BE;
        background: #FCFCFA;
    }
    .pkg-item.selected {
        border: 1.5px solid #1F3D2B;
        background: #F8F5EF;
    }
    .pkg-thumb {
        width: 58px;
        height: 58px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid #DFE4DC;
        flex-shrink: 0;
        background: #EDEEE9;
    }
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 99px;
        font-size: 0.74rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .status-pill.active {
        background: #EAF0E7;
        color: #1F3D2B;
    }
    .status-pill.active:hover {
        background: #d5e3cf;
    }
    .status-pill.inactive {
        background: #EDEEE9;
        color: #6B756E;
    }
    .status-pill.inactive:hover {
        background: #dfe1da;
    }
    .form-group {
        margin-bottom: 16px;
    }
    .form-label {
        display: block;
        font-size: 0.82rem;
        font-weight: 600;
        color: #1F3D2B;
        margin-bottom: 6px;
    }
    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #DFE4DC;
        border-radius: 8px;
        font-family: inherit;
        font-size: 0.875rem;
        color: #1F3D2B;
        background: #FFFFFF;
        transition: border-color 0.15s;
    }
    .form-control:focus {
        outline: none;
        border-color: #1F3D2B;
    }
    .facility-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 10px;
        background: #F8F5EF;
        border: 1px solid #DFE4DC;
        border-radius: 99px;
        font-size: 0.78rem;
        color: #1F3D2B;
        font-weight: 500;
    }
    .facility-chip button {
        background: none;
        border: none;
        color: #6B756E;
        cursor: pointer;
        font-size: 0.9rem;
        line-height: 1;
        padding: 0;
    }
    .facility-chip button:hover {
        color: #D32F2F;
    }
    /* Toggle Switch */
    .switch-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        background: #F8F5EF;
        border: 1px solid #DFE4DC;
        border-radius: 10px;
        margin-top: 8px;
    }
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 46px;
        height: 26px;
    }
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        inset: 0;
        background-color: #C5CCC6;
        transition: 0.25s;
        border-radius: 34px;
    }
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.25s;
        border-radius: 50%;
    }
    .toggle-switch input:checked + .toggle-slider {
        background-color: #1F3D2B;
    }
    .toggle-switch input:checked + .toggle-slider:before {
        transform: translateX(20px);
    }
</style>

<!-- Header Bar (Figma: Administrasi / Kelola Paket + Tombol Tambah Paket) -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 4px;">Kelola Paket</h1>
        <p style="color: #6B756E; font-size: 0.9rem;">Atur pengalaman, harga, dan ketersediaan paket petualangan.</p>
    </div>

    <a href="{{ route('admin.packages.index', ['mode' => 'create']) }}#form-panel" class="btn-primary-yks" style="background: #F26B1D; padding: 10px 18px; font-weight: 600;">
        <i class="bi bi-plus-lg"></i> Tambah paket
    </a>
</div>

@if($errors->any())
    <div style="background: #FEEFE6; border-left: 4px solid #D32F2F; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem; color: #B71C1C;">
        <strong>Periksa kembali isian form:</strong>
        <ul style="margin-top: 6px; padding-left: 18px;">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="pkg-grid">
    <!-- KOLOM KIRI: Katalog Paket & Ringkasan Publikasi -->
    <div style="display: flex; flex-direction: column; gap: 24px;">
        <!-- Card 1: Katalog Paket -->
        <div class="pkg-card">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 16px;">
                <div>
                    <h3 style="font-size: 1.05rem; font-weight: 700;">Katalog paket</h3>
                    <span style="font-size: 0.8rem; color: #6B756E;">{{ $totalCount }} paket · {{ $activeCount }} aktif · {{ $inactiveCount }} nonaktif</span>
                </div>

                <!-- Form Pencarian Nama Paket -->
                <form action="{{ route('admin.packages.index') }}" method="GET" style="display: flex; gap: 6px;">
                    @if($selectedPackage)
                        <input type="hidden" name="edit" value="{{ $selectedPackage->id }}">
                    @endif
                    <div style="position: relative;">
                        <i class="bi bi-search" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #6B756E; font-size: 0.8rem;"></i>
                        <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama paket…" style="padding: 7px 12px 7px 30px; border: 1px solid #DFE4DC; border-radius: 8px; font-size: 0.82rem; width: 200px;">
                    </div>
                    @if($search)
                        <a href="{{ route('admin.packages.index') }}" style="padding: 7px 10px; border: 1px solid #DFE4DC; border-radius: 8px; font-size: 0.78rem; color: #6B756E; text-decoration: none;">Reset</a>
                    @endif
                </form>
            </div>

            <!-- Daftar Item Paket -->
            <div style="display: flex; flex-direction: column; gap: 10px;">
                @forelse($packages as $p)
                    @php
                        $isSelected = ($mode !== 'create' && $selectedPackage && $selectedPackage->id === $p->id);
                    @endphp
                    <div class="pkg-item {{ $isSelected ? 'selected' : '' }}">
                        <div style="display: flex; align-items: center; gap: 12px; min-width: 0;">
                            <img src="{{ $p->image ?: 'https://images.unsplash.com/photo-1544644181-1484b3fdfc62?auto=format&fit=crop&w=300&q=80' }}" alt="{{ $p->name }}" class="pkg-thumb">
                            <div style="min-width: 0;">
                                <div style="font-weight: 700; font-size: 0.92rem; color: #1F3D2B; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $p->name }}
                                </div>
                                <div style="font-size: 0.78rem; color: #6B756E; margin-top: 2px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                    <span>{{ $p->category->name ?? 'Petualangan' }}</span>
                                    <span>•</span>
                                    <span>{{ $p->duration }}</span>
                                    <span>•</span>
                                    <span>{{ $p->difficulty }}</span>
                                </div>
                                <div style="margin-top: 4px; font-size: 0.88rem; font-weight: 700; color: #1F3D2B;">
                                    Rp{{ number_format($p->price, 0, ',', '.') }}
                                    <span style="font-weight: 400; font-size: 0.75rem; color: #6B756E;">/ paket</span>
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0;">
                            <!-- Form Toggle Cepat Aktif / Nonaktif -->
                            <form action="{{ route('admin.packages.toggle_status', $p->id) }}" method="POST" title="Klik untuk mengubah status Aktif / Nonaktif">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="status-pill {{ $p->is_active ? 'active' : 'inactive' }}">
                                    <span style="width: 6px; height: 6px; border-radius: 50%; background: {{ $p->is_active ? '#1F3D2B' : '#6B756E' }};"></span>
                                    {{ $p->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>

                            <!-- Tombol Ubah -->
                            <a href="{{ route('admin.packages.index', ['edit' => $p->id, 'q' => $search]) }}#form-panel"
                               style="padding: 6px 12px; border-radius: 8px; border: 1px solid #DFE4DC; background: {{ $isSelected ? '#1F3D2B' : '#FFFFFF' }}; color: {{ $isSelected ? '#FFFFFF' : '#1F3D2B' }}; font-size: 0.78rem; font-weight: 600; text-decoration: none;">
                                Ubah ↗
                            </a>
                        </div>
                    </div>
                @empty
                    <div style="padding: 32px; text-align: center; color: #6B756E; font-size: 0.875rem;">
                        Tidak ada paket yang cocok dengan pencarian "{{ $search }}".
                    </div>
                @endforelse
            </div>

            <!-- Catatan Katalog (Figma) -->
            <div style="margin-top: 16px; padding-top: 14px; border-top: 1px solid #ECECE6; font-size: 0.78rem; color: #6B756E; display: flex; flex-direction: column; gap: 6px;">
                <div>Seluruh paket ditampilkan · Harga mengikuti katalog pelanggan.</div>
                <div style="background: #F8F5EF; padding: 10px 12px; border-radius: 8px; border-left: 3px solid #F26B1D;">
                    Paket <strong>Nonaktif</strong> tidak menerima pemesanan baru di halaman publik. Reservasi yang sudah tercatat tetap dapat dikelola.
                </div>
            </div>
        </div>

        <!-- Card 2: Ringkasan Publikasi (Figma) -->
        <div class="pkg-card">
            <h3 style="font-size: 1rem; font-weight: 700; margin-bottom: 4px;">Ringkasan publikasi</h3>
            <p style="font-size: 0.8rem; color: #6B756E; margin-bottom: 16px;">Pastikan informasi paket siap sebelum dipublikasikan.</p>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 14px;">
                <div style="background: #F8F5EF; padding: 12px; border-radius: 8px; border: 1px solid #DFE4DC;">
                    <div style="font-size: 1.25rem; font-weight: 800; color: #1F3D2B;">{{ $photoReadyCount }}/{{ $totalCount }}</div>
                    <div style="font-size: 0.75rem; color: #6B756E; margin-top: 2px;">Foto tersedia</div>
                </div>
                <div style="background: #F8F5EF; padding: 12px; border-radius: 8px; border: 1px solid #DFE4DC;">
                    <div style="font-size: 1.25rem; font-weight: 800; color: #1F3D2B;">{{ $priceReadyCount }}/{{ $totalCount }}</div>
                    <div style="font-size: 0.75rem; color: #6B756E; margin-top: 2px;">Harga terisi</div>
                </div>
                <div style="background: #EAF0E7; padding: 12px; border-radius: 8px; border: 1px solid #C1D1BE;">
                    <div style="font-size: 1.25rem; font-weight: 800; color: #1F3D2B;">{{ $activeCount }}</div>
                    <div style="font-size: 0.75rem; color: #1F3D2B; margin-top: 2px;">Tayang di katalog</div>
                </div>
            </div>

            <div style="font-size: 0.75rem; color: #6B756E;">
                Terakhir diperbarui: {{ $lastUpdated ? \Carbon\Carbon::parse($lastUpdated)->format('d M Y, H.i') . ' WIB' : date('d M Y, H.i') . ' WIB' }} · Admin YKS
            </div>
        </div>
    </div>

    <!-- KOLOM KANAN: Form Ubah Paket / Tambah Paket Baru (Figma) -->
    @php
        $isCreate = ($mode === 'create' || !$selectedPackage);
        $formAction = $isCreate
            ? route('admin.packages.store')
            : route('admin.packages.update', $selectedPackage->id);
        $pkgData = $isCreate ? null : $selectedPackage;
    @endphp

    <div class="pkg-card" id="form-panel">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 18px; padding-bottom: 14px; border-bottom: 1px solid #ECECE6;">
            <div>
                <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.06em; color: {{ $isCreate ? '#F26B1D' : '#6B756E' }}; font-weight: 700;">
                    {{ $isCreate ? '+ Tambah Paket Baru' : 'Ubah Paket' }}
                </span>
                <h3 style="font-size: 1.2rem; font-weight: 700; color: #1F3D2B; margin-top: 2px;">
                    {{ $isCreate ? 'Buat Paket Petualangan Baru' : $pkgData->name }}
                </h3>
            </div>

            <span style="background: {{ $isCreate ? '#FEEFE6' : '#EAF0E7' }}; color: {{ $isCreate ? '#F26B1D' : '#1F3D2B' }}; font-size: 0.74rem; font-weight: 600; padding: 4px 10px; border-radius: 99px;">
                {{ $isCreate ? 'Mode Tambah' : 'ID #' . $pkgData->id }}
            </span>
        </div>

        <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(!$isCreate)
                @method('PUT')
            @endif

            <!-- Nama Paket -->
            <div class="form-group">
                <label class="form-label">Nama paket <span style="color: #F26B1D;">*</span></label>
                <input type="text" name="name" class="form-control" required
                       value="{{ old('name', $pkgData->name ?? '') }}"
                       placeholder="Contoh: Jelajah Rimba · Fun Offroad 4x4">
            </div>

            <!-- Deskripsi -->
            <div class="form-group">
                <label class="form-label">Deskripsi <span style="color: #F26B1D;">*</span></label>
                <textarea name="description" rows="3" class="form-control" required
                          placeholder="Ceritakan daya tarik rute, pengalaman, dan kecocokan peserta...">{{ old('description', $pkgData->description ?? '') }}</textarea>
            </div>

            <!-- Harga & Durasi -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Harga (Rp) <span style="color: #F26B1D;">*</span></label>
                    <input type="number" name="price" class="form-control" required min="0" step="5000"
                           value="{{ old('price', $pkgData->price ?? 350000) }}"
                           placeholder="700000">
                </div>
                <div class="form-group">
                    <label class="form-label">Durasi <span style="color: #F26B1D;">*</span></label>
                    <input type="text" name="duration" class="form-control" required
                           value="{{ old('duration', $pkgData->duration ?? '4 - 6 Jam') }}"
                           placeholder="4 - 6 Jam">
                </div>
            </div>

            <!-- Kategori, Tingkat Kesulitan, Jarak -->
            <div style="display: grid; grid-template-columns: 1.2fr 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-control">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ (old('category_id', $pkgData->category_id ?? '') == $cat->id) ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Tingkat Kesulitan</label>
                    <select name="difficulty" class="form-control" required>
                        @foreach(['Mudah', 'Sedang', 'Menantang'] as $diff)
                            <option value="{{ $diff }}" {{ (old('difficulty', $pkgData->difficulty ?? 'Mudah') === $diff) ? 'selected' : '' }}>
                                {{ $diff }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Jarak Rute</label>
                    <input type="text" name="distance" class="form-control"
                           value="{{ old('distance', $pkgData->distance ?? '4.5 Km PP') }}"
                           placeholder="4.5 Km PP">
                </div>
            </div>

            <!-- Meeting Point -->
            <div class="form-group">
                <label class="form-label">Titik Temu (Meeting Point)</label>
                <input type="text" name="meeting_point" class="form-control"
                       value="{{ old('meeting_point', $pkgData->meeting_point ?? 'Pos Jelajah YKS, Kawasan Sentul, Babakan Madang, Bogor') }}"
                       placeholder="Pos Jelajah YKS, Sentul">
            </div>

            <!-- Fasilitas (Interactive Chips + Textarea) -->
            <div class="form-group">
                <label class="form-label">Fasilitas Paket (Includes)</label>
                <div id="facilityChipsContainer" style="display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 8px;"></div>
                <div style="display: flex; gap: 8px; margin-bottom: 6px;">
                    <input type="text" id="newFacilityInput" class="form-control" placeholder="Ketik fasilitas lalu klik + Tambah fasilitas (misal: Pemandu Lokal)" style="font-size: 0.8rem; padding: 7px 10px;">
                    <button type="button" onclick="addFacilityChip()" style="white-space: nowrap; padding: 7px 12px; border-radius: 8px; border: 1px solid #1F3D2B; background: #EAF0E7; color: #1F3D2B; font-size: 0.78rem; font-weight: 600; cursor: pointer;">
                        + Tambah fasilitas
                    </button>
                </div>
                <textarea name="includes" id="includesTextarea" rows="3" class="form-control" style="font-size: 0.8rem;"
                          placeholder="Satu fasilitas per baris...">{{ old('includes', $pkgData->includes ?? "Pemandu Lokal Berpengalaman\nPerlengkapan Keselamatan\nAir Mineral & Snack\nDokumentasi Foto") }}</textarea>
            </div>

            <!-- Foto Paket (Upload File & Preview seperti Figma) -->
            <div class="form-group">
                <label class="form-label">Foto paket <span style="color: #F26B1D;">*</span></label>
                <div style="border: 1.5px dashed #C1D1BE; border-radius: 10px; padding: 14px; background: #F8F5EF;">
                    <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                        <img id="imagePreviewBox"
                             src="{{ $pkgData && $pkgData->image ? $pkgData->image : 'https://images.unsplash.com/photo-1544644181-1484b3fdfc62?auto=format&fit=crop&w=400&q=80' }}"
                             alt="Preview Foto Paket"
                             style="width: 96px; height: 72px; object-fit: cover; border-radius: 8px; border: 1px solid #DFE4DC; background: #FFF;">

                        <div style="flex: 1; min-width: 180px;">
                            <div id="selectedFileName" style="font-size: 0.84rem; font-weight: 600; color: #1F3D2B; word-break: break-all;">
                                {{ $pkgData && $pkgData->image ? basename(parse_url($pkgData->image, PHP_URL_PATH)) : 'Belum ada file dipilih' }}
                            </div>
                            <div style="font-size: 0.75rem; color: #6B756E; margin-top: 2px;">
                                JPG / PNG / WEBP · maksimal 5 MB
                            </div>
                            <div style="margin-top: 8px; display: flex; gap: 8px; align-items: center;">
                                <label for="imageFileInput" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #1F3D2B; color: #FFFFFF; border-radius: 6px; font-size: 0.78rem; font-weight: 600; cursor: pointer;">
                                    <i class="bi bi-upload"></i> ↑ {{ $isCreate ? 'Unggah Foto' : 'Ganti Foto' }}
                                </label>
                                <input type="file" name="image_file" id="imageFileInput" accept="image/*" style="display: none;" onchange="previewUploadedImage(this)">
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #E2E6DF;">
                        <label style="font-size: 0.74rem; color: #6B756E; display: block; margin-bottom: 4px;">Atau gunakan URL gambar eksternal (opsional):</label>
                        <input type="text" name="image_url" id="imageUrlInput" class="form-control" style="font-size: 0.78rem; padding: 6px 10px;"
                               value="{{ old('image_url', $pkgData->image ?? '') }}"
                               placeholder="https://images.unsplash.com/..."
                               oninput="if(this.value) document.getElementById('imagePreviewBox').src = this.value">
                    </div>
                </div>
            </div>

            <!-- Syarat & Ketentuan / Rute Itinerary -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Rute / Itinerary Perjalanan</label>
                    <textarea name="itinerary" rows="3" class="form-control" style="font-size: 0.8rem;"
                              placeholder="07.30 - Kumpul di Meeting Point...">{{ old('itinerary', $pkgData->itinerary ?? "07.30 - Berkumpul & Pengarahan\n08.00 - Mulai Penjelajahan\n12.00 - Kembali ke Pos & Selesai") }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Syarat & Ketentuan / Excludes</label>
                    <textarea name="excludes" rows="3" class="form-control" style="font-size: 0.8rem;"
                              placeholder="Minimal 2 peserta. Wajib mengikuti pengarahan...">{{ old('excludes', $pkgData->excludes ?? "Minimal 2 peserta\nWajib mengikuti pengarahan keselamatan\nTiba 30 menit sebelum sesi") }}</textarea>
                </div>
            </div>

            <!-- Toggle Switch Status Aktif / Nonaktif (Figma: Tampilkan di katalog) -->
            <div class="switch-wrapper">
                <div>
                    <div style="font-weight: 700; font-size: 0.88rem; color: #1F3D2B;">Tampilkan di katalog (Status Aktif)</div>
                    <div style="font-size: 0.76rem; color: #6B756E;">Paket dapat dilihat dan dipesan langsung oleh pelanggan.</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $isCreate ? true : $pkgData->is_active) ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <!-- Tombol Aksi Simpan / Batal -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 22px; padding-top: 16px; border-top: 1px solid #ECECE6; gap: 10px;">
                <div>
                    @if(!$isCreate && $pkgData)
                        <button type="button" onclick="if(confirm('Hapus paket {{ addslashes($pkgData->name) }} dari katalog?')) document.getElementById('delete-pkg-form').submit();"
                                style="background: #FFF; border: 1px solid #E57373; color: #D32F2F; padding: 9px 14px; border-radius: 8px; font-size: 0.82rem; font-weight: 600; cursor: pointer;">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    @endif
                </div>

                <div style="display: flex; gap: 10px;">
                    @if($isCreate)
                        <a href="{{ route('admin.packages.index') }}" style="padding: 9px 16px; border: 1px solid #DFE4DC; border-radius: 8px; color: #6B756E; text-decoration: none; font-size: 0.85rem; font-weight: 600;">
                            Batal
                        </a>
                    @endif
                    <button type="submit" class="btn-primary-yks" style="padding: 10px 20px; font-weight: 600;">
                        <i class="bi bi-check2-circle"></i>
                        {{ $isCreate ? 'Simpan Paket Baru' : 'Simpan Perubahan' }}
                    </button>
                </div>
            </div>
        </form>

        @if(!$isCreate && $pkgData)
            <form id="delete-pkg-form" action="{{ route('admin.packages.destroy', $pkgData->id) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        @endif
    </div>
</div>

<script>
    // Live Preview saat memilih file foto paket baru
    function previewUploadedImage(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            document.getElementById('selectedFileName').textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreviewBox').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    // Sinkronisasi Chip Fasilitas (Includes) seperti di Figma
    const includesTextarea = document.getElementById('includesTextarea');
    const chipsContainer = document.getElementById('facilityChipsContainer');
    const newFacilityInput = document.getElementById('newFacilityInput');

    function renderFacilityChips() {
        if (!includesTextarea || !chipsContainer) return;
        const lines = includesTextarea.value.split('\n').map(s => s.trim()).filter(Boolean);
        chipsContainer.innerHTML = '';
        lines.forEach((item, idx) => {
            const chip = document.createElement('span');
            chip.className = 'facility-chip';
            chip.innerHTML = `${item} <button type="button" onclick="removeFacilityChip(${idx})" title="Hapus">×</button>`;
            chipsContainer.appendChild(chip);
        });
    }

    function addFacilityChip() {
        const val = newFacilityInput.value.trim();
        if (!val) return;
        const current = includesTextarea.value.trim();
        includesTextarea.value = current ? (current + '\n' + val) : val;
        newFacilityInput.value = '';
        renderFacilityChips();
    }

    function removeFacilityChip(index) {
        const lines = includesTextarea.value.split('\n').map(s => s.trim()).filter(Boolean);
        lines.splice(index, 1);
        includesTextarea.value = lines.join('\n');
        renderFacilityChips();
    }

    if (newFacilityInput) {
        newFacilityInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addFacilityChip();
            }
        });
    }

    if (includesTextarea) {
        includesTextarea.addEventListener('input', renderFacilityChips);
        renderFacilityChips();
    }
</script>
@endsection
