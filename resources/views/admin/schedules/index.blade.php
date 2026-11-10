@extends('layouts.admin', ['title' => 'Jadwal & Slot'])

@section('content')
<div style="margin-bottom: 24px;">
    <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 6px;">Jadwal & Manajemen Slot Kuota</h1>
    <p style="color: #6B756E; font-size: 0.9rem;">Atur kapasitas slot harian jip offroad dan pantau ketersediaan jadwal jelajah.</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="stat-card">
        <span style="font-size: 0.75rem; text-transform: uppercase; color: #6B756E; font-weight: 600;">Kapasitas Harian</span>
        <h3 style="font-size: 1.8rem; font-weight: 800; color: #1F3D2B; margin-top: 6px;">{{ $dailyQuota }} Slot</h3>
        <p style="font-size: 0.75rem; color: #6B756E; margin-top: 4px;">Maksimal rombongan jip per hari</p>
    </div>

    <div class="stat-card">
        <span style="font-size: 0.75rem; text-transform: uppercase; color: #6B756E; font-weight: 600;">Slot Terisi Hari Ini</span>
        <h3 style="font-size: 1.8rem; font-weight: 800; color: #304F3B; margin-top: 6px;">{{ $bookedSlots }} Slot</h3>
        <p style="font-size: 0.75rem; color: #6B756E; margin-top: 4px;">Jadwal telah terkunci</p>
    </div>

    <div class="stat-card" style="border-top: 3px solid #F26B1D;">
        <span style="font-size: 0.75rem; text-transform: uppercase; color: #F26B1D; font-weight: 600;">Sisa Kuota Tersedia</span>
        <h3 style="font-size: 1.8rem; font-weight: 800; color: #F26B1D; margin-top: 6px;">{{ $availableSlots }} Slot</h3>
        <p style="font-size: 0.75rem; color: #6B756E; margin-top: 4px;">Tersedia untuk reservasi baru</p>
    </div>
</div>

<div style="background: #FFFFFF; border: 1px solid var(--admin-border); border-radius: 12px; padding: 24px;">
    <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 16px;">Slot Jam Operasional Trip</h3>
    <div style="display: flex; gap: 16px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 200px; padding: 16px; background: #F8F5EF; border-radius: 8px;">
            <strong style="color: #1F3D2B; display: block; font-size: 1rem;">Batch Pagi (08.00–13.00 WIB)</strong>
            <span style="font-size: 0.8rem; color: #6B756E;">Durasi 4–6 Jam · Pengarahan pukul 07.30 WIB</span>
        </div>
        <div style="flex: 1; min-width: 200px; padding: 16px; background: #F8F5EF; border-radius: 8px;">
            <strong style="color: #1F3D2B; display: block; font-size: 1rem;">Batch Siang (11.00–16.00 WIB)</strong>
            <span style="font-size: 0.8rem; color: #6B756E;">Durasi 4–5 Jam · Pengarahan pukul 10.30 WIB</span>
        </div>
        <div style="flex: 1; min-width: 200px; padding: 16px; background: #F8F5EF; border-radius: 8px;">
            <strong style="color: #1F3D2B; display: block; font-size: 1rem;">Batch Sore / Senja (14.00–17.30 WIB)</strong>
            <span style="font-size: 0.8rem; color: #6B756E;">Sunset Trekking & Offroad Scenic View</span>
        </div>
    </div>
</div>
@endsection
