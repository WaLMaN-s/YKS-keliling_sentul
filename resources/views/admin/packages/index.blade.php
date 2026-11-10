@extends('layouts.admin', ['title' => 'Kelola Paket'])

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 6px;">Kelola Paket Petualangan</h1>
        <p style="color: #6B756E; font-size: 0.9rem;">Atur pengalaman, harga, fasilitas, dan ketersediaan paket wisata Sentul.</p>
    </div>
</div>

<div style="background: #FFFFFF; border: 1px solid var(--admin-border); border-radius: 12px; padding: 24px;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
            <thead>
                <tr style="border-bottom: 2px solid #DFE4DC; text-align: left; color: #6B756E;">
                    <th style="padding: 12px 10px;">ID</th>
                    <th style="padding: 12px 10px;">Nama Paket</th>
                    <th style="padding: 12px 10px;">Harga Dasar</th>
                    <th style="padding: 12px 10px;">Durasi</th>
                    <th style="padding: 12px 10px;">Tingkat Kesulitan</th>
                    <th style="padding: 12px 10px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($packages as $p)
                    <tr style="border-bottom: 1px solid #ECECE6;">
                        <td style="padding: 12px 10px;">#{{ $p->id }}</td>
                        <td style="padding: 12px 10px; font-weight: 600;">{{ $p->name }}</td>
                        <td style="padding: 12px 10px; font-weight: 600; color: #1F3D2B;">Rp{{ number_format($p->price, 0, ',', '.') }}</td>
                        <td style="padding: 12px 10px;">{{ $p->duration }}</td>
                        <td style="padding: 12px 10px;">{{ $p->difficulty }}</td>
                        <td style="padding: 12px 10px;">
                            <span style="display: inline-block; padding: 4px 10px; border-radius: 99px; font-size: 0.75rem; font-weight: 600; background: {{ $p->is_active ? '#EAF0E7; color: #1F3D2B;' : '#EDEEE9; color: #6B756E;' }}">
                                {{ $p->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
