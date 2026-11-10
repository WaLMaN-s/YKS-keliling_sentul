@extends('layouts.admin', ['title' => 'Data Pelanggan'])

@section('content')
<div style="margin-bottom: 24px;">
    <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 6px;">Data Pelanggan</h1>
    <p style="color: #6B756E; font-size: 0.9rem;">Daftar pemesan dan wisatawan yang pernah melakukan perjalanan bersama YKS.</p>
</div>

<div style="background: #FFFFFF; border: 1px solid var(--admin-border); border-radius: 12px; padding: 24px;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
            <thead>
                <tr style="border-bottom: 2px solid #DFE4DC; text-align: left; color: #6B756E;">
                    <th style="padding: 12px 10px;">Nama Pelanggan</th>
                    <th style="padding: 12px 10px;">Kontak WhatsApp</th>
                    <th style="padding: 12px 10px;">Email</th>
                    <th style="padding: 12px 10px;">Total Trip</th>
                    <th style="padding: 12px 10px;">Total Transaksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $c)
                    <tr style="border-bottom: 1px solid #ECECE6;">
                        <td style="padding: 12px 10px; font-weight: 600;">{{ $c->customer_name }}</td>
                        <td style="padding: 12px 10px;">{{ $c->customer_phone }}</td>
                        <td style="padding: 12px 10px; color: #6B756E;">{{ $c->customer_email }}</td>
                        <td style="padding: 12px 10px;">{{ $c->total_trip }} kali</td>
                        <td style="padding: 12px 10px; font-weight: 600; color: #1F3D2B;">Rp{{ number_format($c->total_spent, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 32px; text-align: center; color: #6B756E;">Belum ada data pelanggan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $customers->links() }}
    </div>
</div>
@endsection
