@extends('layouts.admin', ['title' => 'Jam Operasional & Pos'])

@section('content')
<div style="margin-bottom: 24px;">
    <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 6px;">Jam Operasional & Pengaturan Pos</h1>
    <p style="color: #6B756E; font-size: 0.9rem;">Informasi pos jelajah, jam aktif admin, dan rekening resmi yang ditampilkan ke pemesan.</p>
</div>

<div style="background: #FFFFFF; border: 1px solid var(--admin-border); border-radius: 12px; padding: 24px; max-width: 720px;">
    <div style="display: flex; flex-direction: column; gap: 20px; font-size: 0.9rem;">
        <div>
            <label style="font-weight: 600; color: #1F3D2B; display: block; margin-bottom: 6px;">Lokasi Pos Utama</label>
            <input type="text" value="{{ $settings['pos_location'] }}" readonly style="width: 100%; padding: 10px 14px; border: 1px solid #DFE4DC; border-radius: 8px; background: #F8F5EF;">
        </div>

        <div>
            <label style="font-weight: 600; color: #1F3D2B; display: block; margin-bottom: 6px;">Jam Operasional Admin</label>
            <input type="text" value="{{ $settings['operating_hours'] }}" readonly style="width: 100%; padding: 10px 14px; border: 1px solid #DFE4DC; border-radius: 8px; background: #F8F5EF;">
        </div>

        <div>
            <label style="font-weight: 600; color: #1F3D2B; display: block; margin-bottom: 6px;">Kanal Resmi Instagram</label>
            <input type="text" value="{{ $settings['official_instagram'] }}" readonly style="width: 100%; padding: 10px 14px; border: 1px solid #DFE4DC; border-radius: 8px; background: #F8F5EF;">
        </div>

        <div>
            <label style="font-weight: 600; color: #1F3D2B; display: block; margin-bottom: 6px;">Rekening Resmi Pembayaran</label>
            <input type="text" value="{{ $settings['official_bank'] }}" readonly style="width: 100%; padding: 10px 14px; border: 1px solid #DFE4DC; border-radius: 8px; background: #F8F5EF;">
        </div>
    </div>
</div>
@endsection
