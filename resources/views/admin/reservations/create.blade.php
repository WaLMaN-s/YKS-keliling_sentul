@extends('layouts.admin', ['title' => 'Tambah Reservasi Manual', 'breadcrumb' => 'Reservasi / Tambah manual'])

@section('content')
<style>
    .manual-layout {
        display: grid;
        grid-template-columns: 728px 1fr;
        gap: 24px;
        align-items: start;
    }
    @media (max-width: 1200px) {
        .manual-layout {
            grid-template-columns: 1fr;
        }
    }
    .manual-card {
        background: #FFFFFF;
        border: 1px solid #E1E4DA;
        border-radius: 12px;
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .m-label {
        font-size: 12px;
        font-weight: 600;
        color: #24382D;
        margin-bottom: 7px;
        display: block;
    }
    .m-input {
        width: 100%;
        min-height: 44px;
        padding: 10px 12px;
        border: 1px solid #E1E4DA;
        border-radius: 7px;
        font-family: inherit;
        font-size: 13px;
        color: #24382D;
        background: #FFFFFF;
    }
    .m-input:focus {
        outline: none;
        border-color: #1F3D2B;
    }
    .m-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    @media (max-width: 640px) {
        .m-grid-2 {
            grid-template-columns: 1fr;
        }
    }
    .sum-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        font-size: 12px;
        line-height: 1.5;
    }
    .sum-row span:first-child {
        color: #718074;
    }
    .sum-row span:last-child {
        color: #24382D;
        font-weight: 600;
        text-align: right;
    }
</style>

<!-- Identitas Halaman (Figma Frame 298:64) -->
<div style="display: flex; justify-content: space-between; align-items: center; gap: 20px; margin-bottom: 24px; flex-wrap: wrap;">
    <h1 style="font-size: 28px; font-weight: 800; color: #1F3D2B; letter-spacing: -0.6px;">
        Tambah reservasi manual
    </h1>

    <a href="{{ route('admin.reservations.index') }}"
       style="height: 42px; padding: 0 16px; border-radius: 8px; border: 1px solid #E1E4DA; background: #FFFFFF; color: #1F3D2B; font-size: 15px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center;">
        Kembali ke reservasi
    </a>
</div>

<!-- Banner Informasi Input Admin (Figma Frame 298:70) -->
<div style="background: #EAF0E7; border-radius: 8px; padding: 16px 18px; color: #1F3D2B; margin-bottom: 24px;">
    <div style="font-size: 11px; font-weight: 700; letter-spacing: 0.02em; margin-bottom: 4px;">
        INPUT ADMIN · NON-WEBSITE
    </div>
    <div style="font-size: 13px; line-height: 1.5;">
        Private Trip dicatat setelah konsultasi WhatsApp. Untuk paket reguler yang dipesan melalui WhatsApp, telepon, atau datang langsung, gunakan formulir yang sama.
    </div>
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

<form action="{{ route('admin.reservations.store_manual') }}" method="POST" enctype="multipart/form-data" class="manual-layout">
    @csrf

    <!-- KOLOM KIRI: 5 Kartu Formulir Reservasi Manual (Figma Frame 298:72) -->
    <div style="display: flex; flex-direction: column; gap: 20px;">
        <!-- 01 Asal & jenis pemesanan -->
        <div class="manual-card">
            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: #1F3D2B; margin-bottom: 4px;">01 &nbsp;Asal &amp; jenis pemesanan</h3>
                <p style="font-size: 12px; color: #718074;">Pisahkan asal pemesanan dari jenis paket yang dipilih pelanggan.</p>
            </div>

            <div class="m-grid-2">
                <div>
                    <label class="m-label">Sumber pemesanan</label>
                    <input type="text" value="Non-Website" readonly
                           class="m-input" style="background: #F8F5EF; color: #718074;">
                </div>
                <div>
                    <label class="m-label">Kanal pemesanan *</label>
                    <select name="booking_channel" id="channelInput" class="m-input" required onchange="updateManualSummary()">
                        <option value="WhatsApp" {{ old('booking_channel') === 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                        <option value="Telepon" {{ old('booking_channel') === 'Telepon' ? 'selected' : '' }}>Telepon</option>
                        <option value="Datang langsung" {{ old('booking_channel') === 'Datang langsung' ? 'selected' : '' }}>Datang langsung</option>
                    </select>
                </div>
            </div>

            <p style="font-size: 11px; color: #718074;">Kanal: WhatsApp, telepon, datang langsung.</p>
        </div>

        <!-- 02 Data pemesan -->
        <div class="manual-card">
            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: #1F3D2B; margin-bottom: 4px;">02 &nbsp;Data pemesan</h3>
                <p style="font-size: 12px; color: #718074;">Gunakan kontak aktif untuk konfirmasi perjalanan dan pembayaran.</p>
            </div>

            <div class="m-grid-2">
                <div>
                    <label class="m-label">Nama pemesan *</label>
                    <input type="text" name="customer_name" id="custNameInput" class="m-input" required
                           value="{{ old('customer_name') }}" placeholder="Masukkan nama lengkap"
                           oninput="updateManualSummary()">
                </div>
                <div>
                    <label class="m-label">Nomor WhatsApp *</label>
                    <input type="text" name="customer_phone" class="m-input" required
                           value="{{ old('customer_phone') }}" placeholder="Masukkan nomor WhatsApp">
                </div>
            </div>

            <div class="m-grid-2">
                <div>
                    <label class="m-label">Email (opsional)</label>
                    <input type="email" name="customer_email" class="m-input"
                           value="{{ old('customer_email') }}" placeholder="Masukkan alamat email">
                </div>
                <div>
                    <label class="m-label">Nama rombongan (opsional)</label>
                    <input type="text" name="group_name" class="m-input"
                           value="{{ old('group_name') }}" placeholder="Nama komunitas / perusahaan">
                </div>
            </div>
        </div>

        <!-- 03 Rencana perjalanan -->
        <div class="manual-card">
            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: #1F3D2B; margin-bottom: 4px;">03 &nbsp;Rencana perjalanan</h3>
                <p style="font-size: 12px; color: #718074;">Isi jadwal dan kebutuhan yang telah dikonfirmasi bersama pelanggan.</p>
            </div>

            <div>
                <label class="m-label">Nama paket / Private Trip *</label>
                <input type="text" name="custom_package_name" id="pkgNameManual" list="yksPackageSuggestions" class="m-input" required
                       value="{{ old('custom_package_name') }}" placeholder="Tulis nama Private Trip (atau pilih paket reguler)"
                       oninput="updateManualSummary()">
                <datalist id="yksPackageSuggestions">
                    <option value="Private Trip · Komunitas / Keluarga">
                    @foreach($packages as $p)
                        <option value="{{ $p->name }}">
                    @endforeach
                </datalist>
            </div>

            <div class="m-grid-2">
                <div>
                    <label class="m-label">Tanggal perjalanan *</label>
                    <input type="date" name="trip_date" id="tripDateManual" class="m-input" required
                           value="{{ old('trip_date', date('Y-m-d', strtotime('+3 days'))) }}"
                           onchange="updateManualSummary()">
                </div>
                <div>
                    <label class="m-label">Jam mulai (WIB) *</label>
                    <input type="text" name="start_time" id="startTimeManual" class="m-input" required
                           value="{{ old('start_time', '08.00–12.00') }}" placeholder="Masukkan jam mulai (misal 08.00–12.00)"
                           oninput="updateManualSummary()">
                </div>
            </div>

            <div>
                <label class="m-label">Jumlah peserta *</label>
                <input type="number" name="participant_count" id="partCountManual" class="m-input" required min="1"
                       value="{{ old('participant_count', 4) }}" placeholder="Masukkan jumlah peserta"
                       oninput="updateManualSummary()">
            </div>

            <div class="m-grid-2">
                <div>
                    <label class="m-label">Jumlah unit kendaraan</label>
                    <input type="number" name="vehicle_count" class="m-input" min="1"
                           value="{{ old('vehicle_count', 1) }}" placeholder="Masukkan jumlah unit">
                </div>
                <div>
                    <label class="m-label">Titik kumpul *</label>
                    <input type="text" name="meeting_point" class="m-input" required
                           value="{{ old('meeting_point', 'Pos Jelajah YKS, Sentul') }}" placeholder="Masukkan lokasi pertemuan">
                </div>
            </div>

            <div>
                <label class="m-label">Kebutuhan khusus (opsional)</label>
                <input type="text" name="notes" class="m-input"
                       value="{{ old('notes') }}" placeholder="Rute, fasilitas, add-on, atau kebutuhan rombongan">
            </div>

            <p style="font-size: 11px; color: #718074;">Pastikan ketersediaan pemandu dan kendaraan sebelum menetapkan jadwal.</p>
        </div>

        <!-- 04 Harga & pembayaran -->
        <div class="manual-card">
            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: #1F3D2B; margin-bottom: 4px;">04 &nbsp;Harga &amp; pembayaran</h3>
                <p style="font-size: 12px; color: #718074;">Catat nominal yang disepakati; status pembayaran dikelola terpisah.</p>
            </div>

            <div class="m-grid-2">
                <div>
                    <label class="m-label">Harga paket disepakati (Rp) *</label>
                    <input type="number" name="package_agreed_price" id="agreedPriceManual" class="m-input" required min="0" step="5000"
                           value="{{ old('package_agreed_price') }}" placeholder="Masukkan harga paket"
                           oninput="updateManualSummary()">
                </div>
                <div>
                    <label class="m-label">Biaya tambahan / add-on (Rp)</label>
                    <input type="number" name="addon_price" id="addonPriceManual" class="m-input" min="0" step="5000"
                           value="{{ old('addon_price', 0) }}" placeholder="0"
                           oninput="updateManualSummary()">
                </div>
            </div>

            <div>
                <label class="m-label">Nominal sudah dibayar (Rp)</label>
                <input type="number" name="paid_amount" id="paidAmountManual" class="m-input" min="0" step="5000"
                       value="{{ old('paid_amount', 0) }}" placeholder="0"
                       oninput="updateManualSummary()">
            </div>

            <div>
                <label class="m-label">Status pembayaran *</label>
                <select name="payment_status" class="m-input" required>
                    <option value="Menunggu Pembayaran" {{ old('payment_status') === 'Menunggu Pembayaran' ? 'selected' : '' }}>Belum bayar</option>
                    <option value="Menunggu Verifikasi" {{ old('payment_status') === 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu pemeriksaan</option>
                    <option value="Lunas" {{ old('payment_status') === 'Lunas' ? 'selected' : '' }}>Diterima (Lunas)</option>
                </select>
            </div>

            <label for="manualProofInput"
                   style="border: 1px dashed #E1E4DA; border-radius: 7px; padding: 16px; cursor: pointer; display: flex; flex-direction: column; gap: 4px;">
                <span id="manualProofLabel" style="font-size: 12px; font-weight: 600; color: #1F3D2B;">
                    Unggah bukti pembayaran (opsional)
                </span>
                <span style="font-size: 11px; color: #718074;">
                    Pilih berkas · JPG, PNG, atau PDF · Maks. 5 MB
                </span>
                <input type="file" name="payment_proof" id="manualProofInput" accept="image/*,.pdf" style="display: none;"
                       onchange="if(this.files[0]) document.getElementById('manualProofLabel').textContent = 'Berkas dipilih: ' + this.files[0].name;">
            </label>
        </div>

        <!-- 05 Status & catatan admin -->
        <div class="manual-card">
            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: #1F3D2B; margin-bottom: 4px;">05 &nbsp;Status &amp; catatan admin</h3>
                <p style="font-size: 12px; color: #718074;">Selesaikan pencatatan sebelum menyimpan reservasi.</p>
            </div>

            <div>
                <label class="m-label">Status reservasi *</label>
                <select name="reservation_status" class="m-input" required>
                    <option value="Menunggu pembayaran" {{ old('reservation_status') === 'Menunggu pembayaran' ? 'selected' : '' }}>Menunggu pembayaran</option>
                    <option value="Menunggu verifikasi" {{ old('reservation_status') === 'Menunggu verifikasi' ? 'selected' : '' }}>Menunggu verifikasi</option>
                    <option value="Terkonfirmasi" {{ old('reservation_status') === 'Terkonfirmasi' ? 'selected' : '' }}>Terkonfirmasi</option>
                    <option value="Selesai" {{ old('reservation_status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <p style="font-size: 11px; color: #718074;">
                Gunakan Terkonfirmasi hanya jika pembayaran sudah diterima dan jadwal telah disepakati.
            </p>

            <div>
                <label class="m-label">Catatan internal (opsional)</label>
                <input type="text" name="admin_notes" class="m-input"
                       value="{{ old('admin_notes') }}" placeholder="Ringkasan konsultasi, permintaan, atau informasi operasional">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <a href="{{ route('admin.reservations.index') }}"
                   style="padding: 12px 18px; border-radius: 8px; border: 1px solid #E1E4DA; background: #FFFFFF; color: #1F3D2B; font-size: 13px; font-weight: 600; text-decoration: none;">
                    Batal
                </a>
                <button type="submit"
                        style="padding: 12px 18px; border-radius: 8px; border: none; background: #F26B1D; color: #FFFFFF; font-size: 13px; font-weight: 600; cursor: pointer;">
                    Simpan reservasi
                </button>
            </div>
        </div>
    </div>

    <!-- KOLOM KANAN: Ringkasan Reservasi, Panduan Private Trip, Sebelum Menyimpan (Figma Frame 298:73) -->
    <div style="display: flex; flex-direction: column; gap: 20px;">
        <!-- Kartu 1: Ringkasan Reservasi -->
        <div class="manual-card" style="gap: 16px;">
            <div style="font-size: 16px; font-weight: 700; color: #1F3D2B;">Ringkasan reservasi</div>

            <div>
                <span style="display: inline-block; background: #FFF0E4; color: #A1420A; font-size: 11px; font-weight: 600; padding: 5px 10px; border-radius: 100px;">
                    Belum disimpan
                </span>
            </div>

            <div class="sum-row">
                <span>Sumber</span>
                <span id="sumSourceText">Non-Website</span>
            </div>
            <div class="sum-row">
                <span>Jenis paket</span>
                <span id="sumPkgText">Belum dipilih</span>
            </div>
            <div class="sum-row">
                <span>Pemesan</span>
                <span id="sumCustText">Belum diisi</span>
            </div>
            <div class="sum-row">
                <span>Tanggal &amp; jam</span>
                <span id="sumDateText">Belum ditentukan</span>
            </div>
            <div class="sum-row">
                <span>Peserta</span>
                <span id="sumPartText">4 peserta</span>
            </div>

            <div style="background: #F8F5EF; border-radius: 8px; padding: 18px 16px;">
                <div style="font-size: 10px; font-weight: 700; color: #718074; margin-bottom: 6px;">TOTAL RESERVASI</div>
                <div id="sumTotalText" style="font-size: 26px; font-weight: 700; color: #1F3D2B; margin-bottom: 4px;">Rp—</div>
                <div style="font-size: 11px; color: #718074;">Harga paket + biaya tambahan − potongan</div>
            </div>

            <div style="font-size: 12px; color: #718074; line-height: 1.6;">
                <div id="sumPaidText">Sudah dibayar: Rp0</div>
                <div id="sumRemainText">Sisa pembayaran: menunggu total harga</div>
            </div>

            <div style="font-size: 11px; color: #718074;">
                Nomor reservasi dibuat setelah data disimpan.
            </div>
        </div>

        <!-- Kartu 2: Panduan Private Trip -->
        <div class="manual-card" style="gap: 14px;">
            <div style="font-size: 16px; font-weight: 700; color: #1F3D2B;">Private Trip</div>
            <div style="font-size: 13px; color: #24382D; line-height: 1.6; display: flex; flex-direction: column; gap: 8px;">
                <div>1. Konsultasi WhatsApp terlebih dahulu.</div>
                <div>2. Sepakati harga, tanggal, rute, dan kebutuhan rombongan.</div>
                <div>3. Admin mencatat reservasi melalui halaman ini.</div>
            </div>
            <div style="font-size: 12px; font-weight: 600; color: #1F3D2B;">
                Tidak ada booking langsung dari katalog.
            </div>
        </div>

        <!-- Kartu 3: Sebelum menyimpan -->
        <div class="manual-card" style="gap: 14px;">
            <div style="font-size: 15px; font-weight: 700; color: #1F3D2B;">Sebelum menyimpan</div>
            <div style="font-size: 12px; color: #24382D; line-height: 1.6; display: flex; flex-direction: column; gap: 6px;">
                <div>• Kontak pemesan sudah sesuai.</div>
                <div>• Jadwal dan kapasitas tersedia.</div>
                <div>• Harga dan kebutuhan disepakati.</div>
                <div>• Pembayaran dicatat sesuai bukti.</div>
            </div>
        </div>
    </div>
</form>

<script>
    function formatRupiah(num) {
        return 'Rp' + Number(num || 0).toLocaleString('id-ID');
    }

    function updateManualSummary() {
        const channel = document.getElementById('channelInput')?.value || 'WhatsApp';
        const cust = document.getElementById('custNameInput')?.value.trim();
        const pkg = document.getElementById('pkgNameManual')?.value.trim();
        const tDate = document.getElementById('tripDateManual')?.value;
        const sTime = document.getElementById('startTimeManual')?.value.trim();
        const part = document.getElementById('partCountManual')?.value;
        const agreed = parseInt(document.getElementById('agreedPriceManual')?.value || 0, 10);
        const addon = parseInt(document.getElementById('addonPriceManual')?.value || 0, 10);
        const paid = parseInt(document.getElementById('paidAmountManual')?.value || 0, 10);

        document.getElementById('sumSourceText').textContent = 'Non-Website (' + channel + ')';
        document.getElementById('sumPkgText').textContent = pkg || 'Belum dipilih';
        document.getElementById('sumCustText').textContent = cust || 'Belum diisi';
        document.getElementById('sumDateText').textContent = tDate ? (tDate + (sTime ? ' · ' + sTime : '')) : 'Belum ditentukan';
        document.getElementById('sumPartText').textContent = part ? (part + ' peserta') : 'Belum diisi';

        const total = agreed + addon;
        document.getElementById('sumTotalText').textContent = total > 0 ? formatRupiah(total) : 'Rp—';
        document.getElementById('sumPaidText').textContent = 'Sudah dibayar: ' + formatRupiah(paid);

        if (total > 0) {
            const remain = Math.max(0, total - paid);
            document.getElementById('sumRemainText').textContent = 'Sisa pembayaran: ' + formatRupiah(remain);
        } else {
            document.getElementById('sumRemainText').textContent = 'Sisa pembayaran: menunggu total harga';
        }
    }

    updateManualSummary();
</script>
@endsection
