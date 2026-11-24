@extends('layouts.admin', ['title' => 'Paket & Layanan', 'breadcrumb' => 'Paket & Layanan'])

@section('content')
<style>
    .pkg-layout {
        display: grid;
        grid-template-columns: 1fr 480px;
        gap: 24px;
        align-items: start;
    }
    @media (max-width: 1180px) {
        .pkg-layout {
            grid-template-columns: 1fr;
        }
    }
    .pkg-item-card {
        background: #FFFFFF;
        border: 1px solid #E1E4DA;
        border-radius: 12px;
        padding: 14px 22px;
        display: flex;
        flex-direction: column;
        gap: 14px;
        transition: all 0.16s ease;
    }
    .pkg-item-card:hover {
        border-color: #B9C7B7;
    }
    .pkg-item-card.selected {
        border: 1.5px solid #1F3D2B;
        background: #FCFBF8;
    }
    .pkg-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: #EAF0E7;
        color: #1F3D2B;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .form-panel-card {
        background: #FFFFFF;
        border: 1px solid #E1E4DA;
        border-radius: 12px;
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 18px;
    }
    .fld-group {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }
    .fld-label {
        font-size: 12px;
        font-weight: 600;
        color: #24382D;
    }
    .fld-input {
        width: 100%;
        min-height: 44px;
        padding: 10px 12px;
        border: 1px solid #E1E4DA;
        border-radius: 8px;
        font-family: inherit;
        font-size: 13px;
        color: #24382D;
        background: #FFFFFF;
    }
    .fld-input:focus {
        outline: none;
        border-color: #1F3D2B;
    }
    .fld-locked {
        background: #F2F2ED;
        border: 1px solid #E1E4DA;
        border-radius: 8px;
        padding: 11px 12px;
        font-size: 13px;
        color: #718074;
        min-height: 44px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .chk-row {
        display: flex;
        align-items: center;
        gap: 9px;
        font-size: 12px;
        color: #24382D;
        cursor: pointer;
    }
    .chk-row input[type="checkbox"] {
        width: 17px;
        height: 17px;
        accent-color: #1F3D2B;
        cursor: pointer;
    }
    /* Toggle Switch */
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 38px;
        height: 22px;
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
        background-color: #E1E4DA;
        transition: 0.2s;
        border-radius: 99px;
    }
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 3px;
        bottom: 3px;
        background-color: #FFFFFF;
        transition: 0.2s;
        border-radius: 50%;
    }
    .toggle-switch input:checked + .toggle-slider {
        background-color: #1F3D2B;
    }
    .toggle-switch input:checked + .toggle-slider:before {
        transform: translateX(16px);
    }
</style>

<!-- Identitas Halaman (Figma Frame 249:62) -->
<div style="display: flex; justify-content: space-between; align-items: center; gap: 20px; margin-bottom: 16px; flex-wrap: wrap;">
    <div>
        <h1 style="font-size: 28px; font-weight: 800; color: #1F3D2B; letter-spacing: -0.6px; margin-bottom: 6px;">Paket &amp; Layanan</h1>
        <p style="font-size: 13px; color: #718074;">Atur katalog perjalanan, harga, dan informasi yang dilihat pelanggan.</p>
    </div>

    <a href="{{ route('admin.packages.index', ['mode' => 'create']) }}#form-panel"
       style="background: #F26B1D; color: #1F3D2B; height: 42px; padding: 0 16px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
        <i class="bi bi-plus-lg"></i> Tambah paket
    </a>
</div>

<!-- Ringkasan Katalog Pill (Figma Frame 249:70) -->
<div style="margin-bottom: 24px;">
    <span style="display: inline-block; background: #EAF0E7; color: #1F3D2B; font-size: 11px; font-weight: 600; padding: 5px 11px; border-radius: 100px;">
        {{ $totalCount }} pilihan paket · 3 add-on
    </span>
</div>

@if($errors->any())
    <div style="background: #FBEDEB; border: 1px solid #E5B4AE; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 12px; color: #AD4940;">
        <strong>Periksa kembali isian formulir:</strong>
        <ul style="margin-top: 6px; padding-left: 18px;">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="pkg-layout">
    <!-- KOLOM KIRI: Daftar Paket & Add-on Bawa Motor Pribadi (Figma Frame 249:74) -->
    <div style="display: flex; flex-direction: column; gap: 18px;">
        <!-- Filter Katalog -->
        <form action="{{ route('admin.packages.index') }}" method="GET" style="display: flex; gap: 12px; flex-wrap: wrap;">
            @if($selectedPackage)
                <input type="hidden" name="edit" value="{{ $selectedPackage->id }}">
                <input type="hidden" name="mode" value="edit">
            @endif
            <div style="position: relative;">
                <i class="bi bi-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #718074; font-size: 13px;"></i>
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama paket..."
                       style="height: 42px; padding: 0 14px 0 34px; border: 1px solid #E1E4DA; border-radius: 8px; background: #FFFFFF; font-size: 13px; color: #24382D; width: 220px;">
            </div>

            <select name="status" onchange="this.form.submit()"
                    style="height: 42px; padding: 0 14px; border: 1px solid #E1E4DA; border-radius: 8px; background: #FFFFFF; font-size: 13px; color: #24382D; cursor: pointer;">
                <option value="">Semua status</option>
                <option value="aktif" {{ $statusFilter === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ $statusFilter === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>

            @if($search || $statusFilter)
                <a href="{{ route('admin.packages.index') }}"
                   style="height: 42px; padding: 0 14px; border: 1px solid #E1E4DA; border-radius: 8px; background: #FFFFFF; font-size: 12px; color: #718074; text-decoration: none; display: inline-flex; align-items: center;">
                    Reset
                </a>
            @endif
        </form>

        <!-- Daftar Kartu Paket (6 Paket ON GOING) -->
        @forelse($packages as $idx => $p)
            @php
                $isSelected = ($mode === 'edit' && $selectedPackage && $selectedPackage->id === $p->id);
                $isConsult = ($p->booking_mode === 'whatsapp_consultation' || stripos($p->name, 'Private Trip') !== false);
                $iconClass = 'bi-compass';
                if (stripos($p->name, 'Paket 2') !== false) {
                    $iconClass = 'bi-water';
                } elseif (stripos($p->name, 'Paket 3') !== false) {
                    $iconClass = 'bi-sunrise';
                }
            @endphp
            <div class="pkg-item-card {{ $isSelected ? 'selected' : '' }}">
                <!-- Baris Atas: Ikon + Nama Paket + Status Pill -->
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div class="pkg-icon-box">
                        <i class="bi {{ $iconClass }}"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-family: 'Montserrat', sans-serif; font-size: 16px; font-weight: 600; color: #1F3D2B;">
                            {{ $p->name }}
                        </div>
                    </div>

                    <form action="{{ route('admin.packages.toggle_status', $p->id) }}" method="POST" style="margin: 0;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" title="Klik untuk ubah status"
                                style="border: none; cursor: pointer; background: {{ $p->is_active ? '#EAF0E7' : '#F4F6F1' }}; color: {{ $p->is_active ? '#1F3D2B' : '#718074' }}; font-size: 11px; font-weight: 600; padding: 5px 10px; border-radius: 100px;">
                            {{ $p->is_active ? 'Aktif' : 'Nonaktif' }}
                        </button>
                    </form>
                </div>

                <!-- Baris Bawah: Tarif & Tombol Edit paket -->
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                    <div>
                        <div style="font-size: 17px; font-weight: 600; color: #24382D;">
                            @if($isConsult)
                                Konsultasi admin
                            @else
                                Rp{{ number_format($p->price, 0, ',', '.') }}
                            @endif
                        </div>
                        <div style="font-size: 11px; color: #718074; margin-top: 2px;">
                            @if($p->capacity_label)
                                {{ $p->capacity_label }}
                            @elseif($isConsult)
                                Harga &amp; tanggal via WhatsApp · Tanpa booking langsung
                            @else
                                {{ $p->price_unit ?? 'per orang' }} · {{ $p->duration }}
                            @endif
                        </div>
                    </div>

                    <a href="{{ route('admin.packages.index', ['edit' => $p->id, 'mode' => 'edit', 'q' => $search, 'status' => $statusFilter]) }}#form-panel"
                       style="height: 42px; padding: 0 16px; border-radius: 8px; border: 1px solid #E1E4DA; background: {{ $isSelected ? '#1F3D2B' : '#FFFFFF' }}; color: {{ $isSelected ? '#FFFFFF' : '#1F3D2B' }}; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="bi bi-pencil" style="font-size: 13px;"></i> Edit paket
                    </a>
                </div>
            </div>
        @empty
            <div style="background: #FFFFFF; border: 1px solid #E1E4DA; border-radius: 12px; padding: 32px; text-align: center; color: #718074; font-size: 13px;">
                Tidak ada paket yang cocok dengan filter pencarian.
            </div>
        @endforelse

        <!-- Kartu Hijau Tua: Pengelolaan Add-on Bawa Motor Pribadi (Figma Frame 249:169) -->
        <div style="background: #1F3D2B; border-radius: 12px; padding: 18px 20px; color: #FFFFFF; display: flex; flex-direction: column; gap: 12px;">
            <div style="font-size: 16px; font-weight: 600; color: #FFFFFF;">
                Add-on · Bawa Motor Pribadi
            </div>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;">
                <div>
                    <div style="font-size: 12px; color: #DFE7DF; margin-bottom: 3px;">Sepatu</div>
                    <div style="font-size: 16px; font-weight: 600; color: #FFFFFF;">Rp100.000</div>
                </div>
                <div>
                    <div style="font-size: 12px; color: #DFE7DF; margin-bottom: 3px;">Guide</div>
                    <div style="font-size: 16px; font-weight: 600; color: #FFFFFF;">Rp200.000</div>
                </div>
                <div>
                    <div style="font-size: 12px; color: #DFE7DF; margin-bottom: 3px;">Paket protector</div>
                    <div style="font-size: 16px; font-weight: 600; color: #FFFFFF;">Rp250.000</div>
                </div>
            </div>
            <div style="font-size: 12px; color: #DFE7DF; line-height: 1.5;">
                Opsional · Semua add-on Rp550.000. Harga dasar Rp200.000; total dengan semua add-on Rp750.000.
            </div>
        </div>

        <div style="font-size: 11px; color: #718074;">
            {{ $packages->count() }} dari {{ $totalCount }} paket · Private Trip hanya melalui konsultasi WhatsApp.
        </div>
    </div>

    <!-- KOLOM KANAN: Form Tambah Paket / Edit Paket (Figma Frame 249:183) -->
    @php
        $isCreate = ($mode === 'create' || !$selectedPackage);
        $pkgData = $isCreate ? null : $selectedPackage;
        $privateTripCat = $categories->first(fn($c) => stripos($c->name, 'Private') !== false);
        $defaultCatId = $isCreate ? ($privateTripCat->id ?? '') : ($pkgData->category_id ?? '');
        $currentCatId = old('category_id', $defaultCatId);
        $isPrivateMode = $isCreate
            ? true
            : (($pkgData->booking_mode ?? '') === 'whatsapp_consultation' || stripos($pkgData->name ?? '', 'Private') !== false);
        $formAction = $isCreate
            ? route('admin.packages.store')
            : route('admin.packages.update', $pkgData->id);
        $existingIncludes = old('includes', $pkgData->includes ?? "Kendaraan & pengemudi\nPemandu perjalanan\nPerlengkapan keselamatan\nAir mineral");
    @endphp

    <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" class="form-panel-card" id="form-panel">
        @csrf
        @if(!$isCreate)
            @method('PUT')
        @endif

        <!-- Kepala Panel (Figma 249:184) -->
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px;">
            <div>
                <h3 id="panelTitleText" style="font-family: 'Montserrat', sans-serif; font-size: 17px; font-weight: 700; color: #1F3D2B; margin-bottom: 3px;">
                    {{ $isCreate ? 'Tambah Private Trip' : 'Edit Paket · ' . $pkgData->name }}
                </h3>
                <p id="panelSubText" style="font-size: 12px; color: #718074;">
                    {{ $isCreate ? 'Lengkapi data Private Trip. * Wajib diisi' : 'Perbarui informasi paket katalog. * Wajib diisi' }}
                </p>
            </div>
            <span style="background: {{ $isCreate ? '#FFF0E4' : '#EAF0E7' }}; color: {{ $isCreate ? '#A54B10' : '#1F3D2B' }}; font-size: 11px; font-weight: 600; padding: 5px 10px; border-radius: 100px; white-space: nowrap;">
                {{ $isCreate ? 'Draf baru' : 'Aktif #' . $pkgData->id }}
            </span>
        </div>

        <!-- Kategori Paket * -->
        <div class="fld-group">
            <label class="fld-label">Kategori paket *</label>
            <select name="category_id" id="categorySelect" class="fld-input" onchange="syncPackageMode()">
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}"
                            data-private="{{ stripos($cat->name, 'Private') !== false ? '1' : '0' }}"
                            {{ (string) $currentCatId === (string) $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <input type="hidden" name="booking_mode" id="bookingModeInput" value="{{ $isPrivateMode ? 'whatsapp_consultation' : 'direct' }}">

        <!-- Mode Pemesanan · Terkunci (Khusus Private Trip sesuai Figma 294:83) -->
        <div class="fld-group" id="privateLockedBox" style="{{ $isPrivateMode ? '' : 'display:none;' }}">
            <label class="fld-label">Mode pemesanan · Terkunci</label>
            <div style="background: #E9EFE5; border: 1px solid #E1E4DA; border-radius: 8px; padding: 12px; font-size: 13px; color: #718074; line-height: 1.5; min-height: 76px;">
                Konsultasi WhatsApp saja. Pelanggan harus chat admin terlebih dahulu; booking langsung dinonaktifkan.
            </div>
        </div>

        <!-- Nama Paket * -->
        <div class="fld-group">
            <label class="fld-label">Nama paket *</label>
            <input type="text" name="name" id="pkgNameInput" class="fld-input" required
                   value="{{ old('name', $pkgData->name ?? '') }}"
                   placeholder="{{ $isPrivateMode ? 'Masukkan nama Private Trip' : 'Contoh: Jelajah Jeep / Trail Paket 1' }}">
        </div>

        <!-- Tujuan Konsultasi (Figma 294:87) -->
        <div class="fld-group" id="consultTargetBox" style="{{ $isPrivateMode ? '' : 'display:none;' }}">
            <label class="fld-label">Tujuan konsultasi</label>
            <input type="text" name="consultation_target" class="fld-input"
                   value="{{ old('consultation_target', $pkgData->consultation_target ?? 'WhatsApp admin utama') }}"
                   placeholder="WhatsApp admin utama">
        </div>

        <!-- Harga: Tampilan Terkunci untuk Private Trip vs Input Tarif untuk Paket Reguler -->
        <div class="fld-group" id="privatePriceBox" style="{{ $isPrivateMode ? '' : 'display:none;' }}">
            <label class="fld-label">Harga · Konsultasi admin</label>
            <div class="fld-locked">
                <span>Tidak ditampilkan sebagai tarif tetap</span>
                <i class="bi bi-chevron-down" style="font-size: 12px;"></i>
            </div>
        </div>

        <div id="regularPriceBox" style="{{ $isPrivateMode ? 'display:none;' : 'display:grid; grid-template-columns: 1.2fr 1fr; gap: 12px;' }}">
            <div class="fld-group">
                <label class="fld-label">Harga paket (Rp) *</label>
                <input type="number" name="price" class="fld-input" min="0" step="5000"
                       value="{{ old('price', $pkgData->price ?? 0) }}"
                       placeholder="700000">
            </div>
            <div class="fld-group">
                <label class="fld-label">Satuan harga</label>
                <input type="text" name="price_unit" class="fld-input"
                       value="{{ old('price_unit', $pkgData->price_unit ?? 'per orang') }}"
                       placeholder="per orang / per jeep">
            </div>
        </div>

        <!-- Deskripsi Layanan * -->
        <div class="fld-group">
            <label class="fld-label">Deskripsi layanan *</label>
            <textarea name="description" rows="3" class="fld-input" style="min-height: 90px;" required
                      placeholder="Jelaskan pengalaman, rute, dan layanan paket.">{{ old('description', $pkgData->description ?? '') }}</textarea>
        </div>

        <!-- Tanggal & Durasi -->
        <div class="fld-group" id="privateDurationBox" style="{{ $isPrivateMode ? '' : 'display:none;' }}">
            <label class="fld-label">Tanggal &amp; durasi · Konsultasi admin</label>
            <div class="fld-locked">
                <span>Ditentukan bersama admin via WhatsApp</span>
                <i class="bi bi-chevron-down" style="font-size: 12px;"></i>
            </div>
        </div>

        <div id="regularDurationBox" style="{{ $isPrivateMode ? 'display:none;' : 'display:grid; grid-template-columns: 1fr 1fr; gap: 12px;' }}">
            <div class="fld-group">
                <label class="fld-label">Durasi perjalanan</label>
                <input type="text" name="duration" class="fld-input"
                       value="{{ old('duration', $pkgData->duration ?? '4–6 jam') }}"
                       placeholder="4–6 jam">
            </div>
            <div class="fld-group">
                <label class="fld-label">Tingkat kesulitan</label>
                <select name="difficulty" class="fld-input">
                    @foreach(['Mudah', 'Sedang', 'Menantang'] as $diff)
                        <option value="{{ $diff }}" {{ old('difficulty', $pkgData->difficulty ?? 'Sedang') === $diff ? 'selected' : '' }}>{{ $diff }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Peserta Maksimal & Kapasitas (Figma 249:209) -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="fld-group">
                <label class="fld-label">Peserta maksimal (opsional)</label>
                <input type="text" name="distance" class="fld-input"
                       value="{{ old('distance', $pkgData->distance ?? 'Sesuai konsultasi') }}"
                       placeholder="Sesuai konsultasi">
            </div>
            <div class="fld-group">
                <label class="fld-label">Kapasitas (opsional)</label>
                <input type="text" name="capacity_label" class="fld-input"
                       value="{{ old('capacity_label', $pkgData->capacity_label ?? 'Sesuai konsultasi') }}"
                       placeholder="Sesuai konsultasi">
            </div>
        </div>

        <!-- Foto Paket (Figma 249:274) -->
        <div class="fld-group">
            <label class="fld-label">Foto paket</label>
            <label for="imageFileInput"
                   style="border: 1px dashed #E1E4DA; border-radius: 8px; min-height: 76px; padding: 12px; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; cursor: pointer; background: #FFFFFF;">
                <span id="uploadFileLabel" style="font-size: 13px; color: #718074;">
                    {{ $pkgData && $pkgData->image ? basename(parse_url($pkgData->image, PHP_URL_PATH)) : 'Pilih foto paket' }}
                </span>
                <span style="font-size: 12px; color: #718074; margin-top: 2px;">JPG atau PNG · Maks. 5 MB</span>
                <input type="file" name="image_file" id="imageFileInput" accept="image/*" style="display: none;"
                       onchange="if(this.files[0]) document.getElementById('uploadFileLabel').textContent = this.files[0].name;">
            </label>
        </div>

        <!-- Fasilitas Termasuk (Figma 249:218 Checkboxes) -->
        <div class="fld-group">
            <label class="fld-label">Fasilitas termasuk</label>
            @php
                $defaultFacs = [
                    'Kendaraan & pengemudi',
                    'Pemandu perjalanan',
                    'Perlengkapan keselamatan',
                    'Air mineral',
                ];
            @endphp
            <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 2px;">
                @foreach($defaultFacs as $fac)
                    <label class="chk-row">
                        <input type="checkbox" class="fac-chk" value="{{ $fac }}"
                               onchange="syncFacilitiesTextarea()"
                               {{ stripos($existingIncludes, $fac) !== false ? 'checked' : '' }}>
                        <span>{{ $fac }}</span>
                    </label>
                @endforeach
            </div>
            <textarea name="includes" id="includesTextarea" rows="2" class="fld-input" style="margin-top: 6px; font-size: 12px;"
                      placeholder="Daftar fasilitas (satu per baris)...">{{ $existingIncludes }}</textarea>
        </div>

        <!-- Informasi Sebelum Perjalanan (Figma 249:240) -->
        <div class="fld-group">
            <label class="fld-label">Informasi sebelum perjalanan</label>
            <textarea name="excludes" rows="3" class="fld-input" style="min-height: 90px;"
                      placeholder="Harga, tanggal, rute, dan kebutuhan rombongan dikonsultasikan melalui WhatsApp admin.">{{ old('excludes', $pkgData->excludes ?? 'Harga, tanggal, rute, dan kebutuhan rombongan dikonsultasikan melalui WhatsApp admin.') }}</textarea>
        </div>

        <!-- Status Aktif Toggle (Figma 249:244) -->
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px;">
            <div>
                <div style="font-size: 12px; font-weight: 600; color: #24382D;">Paket aktif</div>
                <div style="font-size: 11px; color: #718074; margin-top: 2px;">Nonaktif sampai siap ditampilkan</div>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" name="is_active" value="1"
                       {{ old('is_active', $isCreate ? false : $pkgData->is_active) ? 'checked' : '' }}>
                <span class="toggle-slider"></span>
            </label>
        </div>

        <div style="height: 1px; background: #E1E4DA;"></div>

        <!-- Tombol Aksi Batal & Simpan Paket (Figma 249:251) -->
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px;">
            <div>
                @if(!$isCreate && $pkgData)
                    <button type="button"
                            onclick="if(confirm('Hapus paket {{ addslashes($pkgData->name) }}?')) document.getElementById('del-pkg-form').submit();"
                            style="height: 42px; padding: 0 14px; border-radius: 8px; border: 1px solid #AD4940; background: #FFFFFF; color: #AD4940; font-size: 12px; font-weight: 600; cursor: pointer;">
                        Hapus
                    </button>
                @endif
            </div>

            <div style="display: flex; gap: 12px; align-items: center;">
                <a href="{{ route('admin.packages.index') }}"
                   style="height: 42px; padding: 0 16px; border-radius: 8px; border: 1px solid #E1E4DA; background: #FFFFFF; color: #1F3D2B; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center;">
                    Batal
                </a>
                <button type="submit"
                        style="height: 42px; padding: 0 16px; border-radius: 8px; border: none; background: #F26B1D; color: #1F3D2B; font-size: 13px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="bi bi-floppy"></i> Simpan paket
                </button>
            </div>
        </div>
    </form>

    @if(!$isCreate && $pkgData)
        <form id="del-pkg-form" action="{{ route('admin.packages.destroy', $pkgData->id) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endif
</div>

<script>
    function syncPackageMode() {
        const sel = document.getElementById('categorySelect');
        if (!sel) return;
        const opt = sel.options[sel.selectedIndex];
        const isPrivate = opt && opt.getAttribute('data-private') === '1';

        document.getElementById('bookingModeInput').value = isPrivate ? 'whatsapp_consultation' : 'direct';
        document.getElementById('privateLockedBox').style.display = isPrivate ? '' : 'none';
        document.getElementById('consultTargetBox').style.display = isPrivate ? '' : 'none';
        document.getElementById('privatePriceBox').style.display = isPrivate ? '' : 'none';
        document.getElementById('regularPriceBox').style.display = isPrivate ? 'none' : 'grid';
        document.getElementById('privateDurationBox').style.display = isPrivate ? '' : 'none';
        document.getElementById('regularDurationBox').style.display = isPrivate ? 'none' : 'grid';

        const nameInput = document.getElementById('pkgNameInput');
        if (nameInput) {
            nameInput.placeholder = isPrivate ? 'Masukkan nama Private Trip' : 'Masukkan nama paket perjalanan';
        }
    }

    function syncFacilitiesTextarea() {
        const checks = document.querySelectorAll('.fac-chk:checked');
        const values = Array.from(checks).map(c => c.value);
        const textarea = document.getElementById('includesTextarea');
        if (textarea) {
            textarea.value = values.join('\n');
        }
    }
</script>
@endsection
