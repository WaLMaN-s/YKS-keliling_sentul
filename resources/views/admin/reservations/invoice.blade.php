<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $booking->booking_code }} — Yuk Keliling Sentul</title>
    <link rel="stylesheet" href="/assets/vendor/fonts/fonts.css">
    <link rel="stylesheet" href="/assets/vendor/bootstrap-icons/bootstrap-icons.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #F4F1EA;
            color: #1A1A1A;
            padding: 28px 16px;
            line-height: 1.5;
        }
        .toolbar {
            max-width: 820px;
            margin: 0 auto 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.15s ease;
        }
        .btn-back { background: #FFFFFF; border-color: #DFE4DC; color: #1F3D2B; }
        .btn-back:hover { background: #EAF0E7; }
        .btn-print { background: #1F3D2B; color: #FFFFFF; }
        .btn-print:hover { background: #162E20; }
        .btn-wa { background: #25D366; color: #FFFFFF; }
        .btn-wa:hover { background: #1EBE5D; }

        .invoice-sheet {
            max-width: 820px;
            margin: 0 auto;
            background: #FFFFFF;
            border: 1px solid #DFE4DC;
            border-radius: 14px;
            padding: 40px 44px;
            box-shadow: 0 8px 28px rgba(31, 61, 43, 0.08);
        }
        .inv-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            padding-bottom: 24px;
            border-bottom: 2px solid #1F3D2B;
            margin-bottom: 24px;
        }
        .brand-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.45rem;
            font-weight: 900;
            color: #1F3D2B;
            letter-spacing: -0.02em;
        }
        .brand-meta {
            font-size: 0.8rem;
            color: #6B756E;
            margin-top: 4px;
        }
        .inv-badge {
            display: inline-block;
            padding: 5px 14px;
            border-radius: 99px;
            font-size: 0.78rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 6px;
        }
        .inv-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 28px;
        }
        .info-box {
            background: #F8F5EF;
            border: 1px solid #E5E7EB;
            border-radius: 10px;
            padding: 16px 18px;
        }
        .info-box h4 {
            font-size: 0.74rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #6B756E;
            margin-bottom: 8px;
        }
        .info-box p {
            font-size: 0.86rem;
            color: #1F3D2B;
            margin-bottom: 4px;
        }
        table.inv-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            font-size: 0.88rem;
        }
        table.inv-table th {
            background: #1F3D2B;
            color: #FFFFFF;
            padding: 11px 14px;
            text-align: left;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        table.inv-table td {
            padding: 12px 14px;
            border-bottom: 1px solid #ECECE6;
        }
        .total-box {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 28px;
        }
        .total-inner {
            width: 320px;
            background: #F8F5EF;
            border: 1px solid #DFE4DC;
            border-radius: 10px;
            padding: 16px 20px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.88rem;
            color: #4B5563;
            margin-bottom: 6px;
        }
        .total-grand {
            display: flex;
            justify-content: space-between;
            font-family: 'Montserrat', sans-serif;
            font-size: 1.15rem;
            font-weight: 900;
            color: #1F3D2B;
            padding-top: 10px;
            margin-top: 8px;
            border-top: 2px dashed #CBD9C8;
        }
        .inv-footer {
            border-top: 1px solid #ECECE6;
            padding-top: 18px;
            display: flex;
            justify-content: space-between;
            gap: 20px;
            font-size: 0.8rem;
            color: #6B756E;
            flex-wrap: wrap;
        }
        @media print {
            body { background: #FFFFFF; padding: 0; }
            .no-print { display: none !important; }
            .invoice-sheet { border: none; box-shadow: none; padding: 12px; max-width: 100%; }
        }
    </style>
</head>
<body>
@php
    $waNumber = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $booking->customer_phone ?? ''));
    $tripFormatted = \Carbon\Carbon::parse($booking->trip_date)->translatedFormat('l, d F Y');
    $subtotalPkg = ($booking->subtotal_package ?? 0) > 0 ? $booking->subtotal_package : ($booking->package_price * $booking->participant_count);
    $subtotalAddon = $booking->subtotal_addons ?? 0;

    $waLines = [
        "*INVOICE RESERVASI RESMI — YUK KELILING SENTUL (YKS)*",
        "No. Invoice: INV-{$booking->booking_code}",
        "Status: *{$booking->payment_status}*",
        "",
        "Halo Kak *{$booking->customer_name}*, berikut rincian invoice reservasi Anda:",
        "• Paket: {$booking->package_name}",
        "• Tanggal Trip: {$tripFormatted} (08.00 WIB)",
        "• Peserta / Unit: {$booking->participant_count} orang/unit",
    ];
    if (!empty($booking->selected_addons)) {
        $waLines[] = "• Layanan Tambahan: {$booking->selected_addons}";
    }
    $waLines[] = "• Total Tagihan: *Rp " . number_format($booking->total_price, 0, ',', '.') . "*";
    $waLines[] = "• Titik Kumpul: " . ($booking->meeting_point ?? 'Pos Jelajah YKS Sentul, Babakan Madang, Bogor');
    $waLines[] = "";
    $waLines[] = "Cek status & unggah bukti bayar di sini:";
    $waLines[] = route('booking.track', ['kode' => $booking->booking_code]);
    $waLines[] = "";
    $waLines[] = "Terima kasih telah memilih Yuk Keliling Sentul!";
    $waMessage = rawurlencode(implode("\n", $waLines));

    $statusBg = '#EDEEE9'; $statusColor = '#6B756E';
    if ($booking->payment_status === 'Lunas') { $statusBg = '#D1FAE5'; $statusColor = '#065F46'; }
    elseif ($booking->payment_status === 'Menunggu Verifikasi') { $statusBg = '#FEEFE6'; $statusColor = '#9A3412'; }
    elseif ($booking->payment_status === 'Dibatalkan') { $statusBg = '#FEE2E2'; $statusColor = '#991B1B'; }
@endphp

<div class="toolbar no-print">
    <a href="{{ route('admin.reservations.index', ['select' => $booking->booking_code]) }}" class="btn btn-back">
        <i class="bi bi-arrow-left"></i> Kembali ke Data Reservasi
    </a>

    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        @if($waNumber)
            <a href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}" target="_blank" class="btn btn-wa">
                <i class="bi bi-whatsapp"></i> Kirim Invoice ke WA Pelanggan
            </a>
        @endif
        <button type="button" onclick="window.print()" class="btn btn-print">
            <i class="bi bi-printer-fill"></i> Cetak / Simpan PDF
        </button>
    </div>
</div>

<div class="invoice-sheet">
    <div class="inv-header">
        <div style="display: flex; align-items: center; gap: 14px;">
            <img src="/assets/images/logo-yks.png" alt="Logo YKS" style="width: 60px; height: 60px; object-fit: contain; border-radius: 50%; flex-shrink: 0;">
            <div>
                <div class="brand-title">YUK KELILING SENTUL (YKS)</div>
                <div class="brand-meta">
                    Wisata Off-Road Jeep 4x4, Motor Trail &amp; Trekking Curug<br>
                    Pos Jelajah YKS, Kawasan Sentul / Hambalang, Babakan Madang, Kab. Bogor<br>
                    WhatsApp Admin: 0812-1084-2032 · Instagram: @yuk_keliling_sentul
                </div>
            </div>
        </div>
        <div style="text-align: right;">
            <div style="font-size: 0.75rem; color: #6B756E; text-transform: uppercase; font-weight: 700;">INVOICE RESERVASI</div>
            <div style="font-family: 'Montserrat', sans-serif; font-size: 1.2rem; font-weight: 900; color: #1F3D2B;">
                INV-{{ $booking->booking_code }}
            </div>
            <div style="font-size: 0.78rem; color: #6B756E; margin-top: 2px;">
                Tgl Terbit: {{ date('d M Y', strtotime($booking->created_at)) }}
            </div>
            <span class="inv-badge" style="background: {{ $statusBg }}; color: {{ $statusColor }};">
                {{ $booking->payment_status }}
            </span>
        </div>
    </div>

    <div class="inv-grid">
        <div class="info-box">
            <h4>Ditagihkan Kepada (Pemesan)</h4>
            <p><strong>{{ $booking->customer_name }}</strong></p>
            <p>WhatsApp: {{ $booking->customer_phone }}</p>
            <p>Email: {{ $booking->customer_email }}</p>
        </div>
        <div class="info-box">
            <h4>Detail Jadwal Perjalanan</h4>
            <p><strong>{{ $tripFormatted }}</strong></p>
            <p>Waktu: 08.00 WIB · Durasi: {{ $booking->package_duration ?? '4 - 6 Jam' }}</p>
            <p>Titik Kumpul: {{ $booking->meeting_point ?? 'Basecamp YKS Sentul' }}</p>
        </div>
    </div>

    <table class="inv-table">
        <thead>
            <tr>
                <th>Deskripsi Layanan</th>
                <th style="text-align: center;">Jumlah</th>
                <th style="text-align: right;">Harga Satuan</th>
                <th style="text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>{{ $booking->package_name }}</strong>
                    @if(!empty($booking->jenis_trip))
                        <div style="font-size: 0.78rem; color: #6B756E;">Jenis Perjalanan: {{ ucfirst($booking->jenis_trip) }} Trip</div>
                    @endif
                </td>
                <td style="text-align: center;">{{ $booking->participant_count }}</td>
                <td style="text-align: right;">Rp {{ number_format($booking->package_price, 0, ',', '.') }}</td>
                <td style="text-align: right; font-weight: 700;">Rp {{ number_format($subtotalPkg, 0, ',', '.') }}</td>
            </tr>

            @foreach($addons as $ad)
                <tr>
                    <td>
                        <strong>Add-On: {{ $ad->nama_addon }}</strong>
                    </td>
                    <td style="text-align: center;">{{ $ad->jumlah }}</td>
                    <td style="text-align: right;">Rp {{ number_format($ad->harga_satuan, 0, ',', '.') }}</td>
                    <td style="text-align: right; font-weight: 700;">Rp {{ number_format($ad->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        <div class="total-inner">
            <div class="total-row">
                <span>Subtotal Paket</span>
                <span>Rp {{ number_format($subtotalPkg, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>Subtotal Add-On</span>
                <span>Rp {{ number_format($subtotalAddon, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>Metode Pembayaran</span>
                <span>{{ $booking->payment_method }}</span>
            </div>
            <div class="total-grand">
                <span>Total Tagihan</span>
                <span style="color: #F26B1D;">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <div class="inv-footer">
        <div>
            <strong>Rekening Pembayaran Resmi (PT YUK KELILING SENTUL):</strong><br>
            @if($bankAccounts->isNotEmpty())
                @foreach($bankAccounts as $bank)
                    {{ $bank->nama_bank }}: <strong>{{ $bank->no_rekening }}</strong> (a.n. {{ $bank->atas_nama }})<br>
                @endforeach
            @else
                BCA: <strong>8450-1234-5678</strong> · Mandiri: <strong>1330-0987-6543-2</strong>
            @endif
        </div>
        <div style="text-align: right;">
            <em>Invoice ini sah diterbitkan secara elektronik oleh sistem Admin YKS.<br>
            Tunjukkan invoice atau kode booking <strong>{{ $booking->booking_code }}</strong> saat tiba di titik kumpul.</em>
        </div>
    </div>
</div>
</body>
</html>
