@extends('layouts.admin', ['title' => 'Jam Operasional'])

@section('content')
<style>
    .ops-grid {
        display: grid;
        grid-template-columns: 1.25fr 1fr;
        gap: 24px;
        align-items: start;
    }
    @media (max-width: 1100px) {
        .ops-grid {
            grid-template-columns: 1fr;
        }
    }
    .time-input {
        padding: 7px 10px;
        border: 1px solid #DFE4DC;
        border-radius: 6px;
        font-size: 0.83rem;
        color: #1F3D2B;
        width: 90px;
        text-align: center;
        background: #FFF;
    }
    .time-input:disabled {
        background: #F0F2EE;
        color: #8E988E;
    }
</style>

<!-- Header Bar (Figma: Administrasi / Jam Operasional) -->
<div style="margin-bottom: 24px;">
    <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 4px;">Jam Operasional</h1>
    <p style="color: #6B756E; font-size: 0.9rem;">Tentukan waktu layanan, hari libur, dan pengecualian tim.</p>
</div>

<div class="ops-grid">
    <!-- KOLOM KIRI: Jadwal Mingguan (Figma) -->
    <div class="stat-card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; flex-wrap: wrap; gap: 10px;">
            <div>
                <h3 style="font-size: 1.05rem; font-weight: 700;">Jadwal mingguan</h3>
                <span style="font-size: 0.78rem; color: #6B756E;">Zona waktu Asia/Jakarta · WIB (UTC+7)</span>
            </div>

            <button type="button" onclick="copyMondayToAll()" class="btn-outline-yks" style="font-size: 0.78rem; padding: 6px 12px;">
                <i class="bi bi-files"></i> Salin Senin ke semua hari
            </button>
        </div>

        <p style="font-size: 0.78rem; color: #6B756E; margin-bottom: 16px; background: #F8F5EF; padding: 8px 12px; border-radius: 6px;">
            Jam ini berlaku untuk layanan admin dan verifikasi. Jadwal keberangkatan dikelola terpisah di <a href="{{ route('admin.schedules.index') }}" style="color: #1F3D2B; font-weight: 600;">Jadwal & Slot</a>.
        </p>

        <form action="{{ route('admin.settings.save_weekly') }}" method="POST">
            @csrf
            <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; margin-bottom: 16px;">
                <thead>
                    <tr style="border-bottom: 2px solid #DFE4DC; text-align: left; color: #6B756E;">
                        <th style="padding: 10px 8px;">Hari</th>
                        <th style="padding: 10px 8px;">Jam buka (WIB)</th>
                        <th style="padding: 10px 8px;">Jam tutup (WIB)</th>
                        <th style="padding: 10px 8px;">Hari libur</th>
                        <th style="padding: 10px 8px; text-align: right;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($settings['weekly'] as $idx => $row)
                        <tr style="border-bottom: 1px solid #ECECE6;">
                            <td style="padding: 11px 8px; font-weight: 700; color: #1F3D2B;">
                                {{ $row['day'] }}
                            </td>
                            <td style="padding: 11px 8px;">
                                <input type="text" name="days[{{ $idx }}][open]" id="open_{{ $idx }}"
                                       value="{{ $row['open'] }}" class="time-input" {{ $row['is_holiday'] ? 'readonly' : '' }}>
                            </td>
                            <td style="padding: 11px 8px;">
                                <input type="text" name="days[{{ $idx }}][close]" id="close_{{ $idx }}"
                                       value="{{ $row['close'] }}" class="time-input" {{ $row['is_holiday'] ? 'readonly' : '' }}>
                            </td>
                            <td style="padding: 11px 8px;">
                                <label style="display: inline-flex; align-items: center; gap: 6px; cursor: pointer; font-size: 0.8rem; color: #6B756E;">
                                    <input type="checkbox" name="days[{{ $idx }}][is_holiday]" id="hol_{{ $idx }}" value="1"
                                           {{ $row['is_holiday'] ? 'checked' : '' }}
                                           onchange="toggleDayRow({{ $idx }})">
                                    <span>Libur</span>
                                </label>
                            </td>
                            <td style="padding: 11px 8px; text-align: right;">
                                <span id="badge_{{ $idx }}" style="display: inline-block; padding: 3px 10px; border-radius: 99px; font-size: 0.74rem; font-weight: 600; {{ $row['is_holiday'] ? 'background: #EDEEE9; color: #6B756E;' : 'background: #EAF0E7; color: #1F3D2B;' }}">
                                    {{ $row['is_holiday'] ? 'Libur' : 'Buka' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Informasi Lokasi Pos & Rekening Resmi -->
            <div style="border-top: 1px solid #ECECE6; padding-top: 14px; margin-bottom: 16px; display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div>
                    <label style="font-size: 0.78rem; font-weight: 600; color: #1F3D2B; display: block; margin-bottom: 4px;">Alamat Pos Jelajah</label>
                    <input type="text" name="pos_location" value="{{ $settings['pos_location'] }}"
                           style="width: 100%; padding: 8px 10px; border: 1px solid #DFE4DC; border-radius: 8px; font-size: 0.82rem;">
                </div>
                <div>
                    <label style="font-size: 0.78rem; font-weight: 600; color: #1F3D2B; display: block; margin-bottom: 4px;">Rekening Resmi Pembayaran</label>
                    <input type="text" name="official_bank" value="{{ $settings['official_bank'] }}"
                           style="width: 100%; padding: 8px 10px; border: 1px solid #DFE4DC; border-radius: 8px; font-size: 0.82rem;">
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 12px; border-top: 1px solid #ECECE6; flex-wrap: wrap; gap: 10px;">
                <span style="font-size: 0.76rem; color: #6B756E;">Aktifkan Hari libur untuk menutup layanan pada hari tersebut.</span>
                <div style="display: flex; gap: 8px;">
                    <a href="{{ route('admin.settings.index') }}" class="btn-outline-yks">Batal</a>
                    <button type="submit" class="btn-primary-yks">
                        <i class="bi bi-check2-circle"></i> Simpan perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- KOLOM KANAN: Layanan Hari Ini & Pengecualian Tanggal (Figma) -->
    <div style="display: flex; flex-direction: column; gap: 24px;">
        <!-- Card 1: Layanan Hari Ini -->
        <div class="stat-card" style="background: #EAF0E7; border-color: #C1D1BE;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.06em; color: #1F3D2B; font-weight: 700;">
                        Layanan hari ini
                    </span>
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: #1F3D2B; margin-top: 2px;">
                        {{ date('l, d F Y') }} · 08.00–17.00 WIB
                    </h3>
                </div>
                <span style="background: #1F3D2B; color: #FFFFFF; padding: 5px 12px; border-radius: 99px; font-size: 0.76rem; font-weight: 700;">
                    ● Admin Tersedia
                </span>
            </div>
            <p style="font-size: 0.8rem; color: #304F3B; margin-top: 8px;">
                Pos: {{ $settings['pos_location'] }}
            </p>
        </div>

        <!-- Card 2: Pengecualian Tanggal (Figma) -->
        <div class="stat-card">
            <h3 style="font-size: 1.05rem; font-weight: 700; margin-bottom: 4px;">Pengecualian tanggal</h3>
            <p style="font-size: 0.78rem; color: #6B756E; margin-bottom: 14px;">Pengecualian lebih diutamakan daripada jadwal mingguan.</p>

            <table style="width: 100%; border-collapse: collapse; font-size: 0.83rem; margin-bottom: 18px;">
                <thead>
                    <tr style="border-bottom: 2px solid #DFE4DC; text-align: left; color: #6B756E;">
                        <th style="padding: 8px;">Tanggal</th>
                        <th style="padding: 8px;">Layanan</th>
                        <th style="padding: 8px;">Keterangan</th>
                        <th style="padding: 8px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($settings['exceptions'] as $ex)
                        <tr style="border-bottom: 1px solid #ECECE6;">
                            <td style="padding: 10px 8px; font-weight: 700; color: #1F3D2B;">
                                {{ date('d M Y', strtotime($ex['date'])) }}
                            </td>
                            <td style="padding: 10px 8px;">
                                <span style="background: {{ $ex['service'] === 'Libur' ? '#FEEFE6; color: #F26B1D;' : '#F8F5EF; color: #1F3D2B;' }} padding: 2px 8px; border-radius: 99px; font-size: 0.74rem; font-weight: 600;">
                                    {{ $ex['service'] }}
                                </span>
                            </td>
                            <td style="padding: 10px 8px; color: #6B756E;">{{ $ex['note'] }}</td>
                            <td style="padding: 10px 8px; text-align: right;">
                                <form action="{{ route('admin.settings.remove_exception', $ex['id']) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="border: none; background: none; color: #D32F2F; font-size: 0.78rem; font-weight: 600; cursor: pointer;">
                                        Hapus ×
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 16px; text-align: center; color: #6B756E;">Belum ada pengecualian tanggal.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Form Tambah Pengecualian (Figma) -->
            <div style="border-top: 1px solid #ECECE6; padding-top: 14px;">
                <h4 style="font-size: 0.9rem; font-weight: 700; color: #1F3D2B; margin-bottom: 10px;">Tambah pengecualian</h4>
                <form action="{{ route('admin.settings.add_exception') }}" method="POST" style="display: flex; flex-direction: column; gap: 10px;">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <div>
                            <label style="font-size: 0.76rem; font-weight: 600; display: block; margin-bottom: 4px;">Tanggal</label>
                            <input type="date" name="date" value="2026-11-25" required
                                   style="width: 100%; padding: 8px 10px; border: 1px solid #DFE4DC; border-radius: 8px; font-size: 0.82rem;">
                        </div>
                        <div>
                            <label style="font-size: 0.76rem; font-weight: 600; display: block; margin-bottom: 4px;">Jenis pengecualian</label>
                            <select name="service" style="width: 100%; padding: 8px 10px; border: 1px solid #DFE4DC; border-radius: 8px; font-size: 0.82rem; background: #FFF;">
                                <option value="Libur">Libur penuh</option>
                                <option value="09.00–15.00 WIB">Jam khusus (09.00–15.00 WIB)</option>
                                <option value="08.00–12.00 WIB">Setengah hari (08.00–12.00 WIB)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label style="font-size: 0.76rem; font-weight: 600; display: block; margin-bottom: 4px;">Keterangan</label>
                        <input type="text" name="note" placeholder="Contoh: Libur internal tim / Rapat evaluasi" required
                               style="width: 100%; padding: 8px 10px; border: 1px solid #DFE4DC; border-radius: 8px; font-size: 0.82rem;">
                    </div>

                    <button type="submit" class="btn-primary-yks" style="justify-content: center; padding: 9px;">
                        + Tambah pengecualian
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function copyMondayToAll() {
        const monOpen = document.getElementById('open_0').value;
        const monClose = document.getElementById('close_0').value;
        for (let i = 1; i < 7; i++) {
            document.getElementById('open_' + i).value = monOpen;
            document.getElementById('close_' + i).value = monClose;
        }
    }

    function toggleDayRow(idx) {
        const cb = document.getElementById('hol_' + idx);
        const badge = document.getElementById('badge_' + idx);
        const openInp = document.getElementById('open_' + idx);
        const closeInp = document.getElementById('close_' + idx);

        if (cb.checked) {
            badge.textContent = 'Libur';
            badge.style.background = '#EDEEE9';
            badge.style.color = '#6B756E';
            openInp.readOnly = true;
            closeInp.readOnly = true;
        } else {
            badge.textContent = 'Buka';
            badge.style.background = '#EAF0E7';
            badge.style.color = '#1F3D2B';
            openInp.readOnly = false;
            closeInp.readOnly = false;
        }
    }
</script>
@endsection
