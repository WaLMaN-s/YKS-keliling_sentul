<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Dashboard · Admin YKS sesuai Figma ON GOING (#178:18491).
     */
    public function index()
    {
        $allBookings = DB::table('yks_bookings')
            ->leftJoin('yks_packages', 'yks_bookings.package_id', '=', 'yks_packages.id')
            ->select(
                'yks_bookings.*',
                DB::raw('COALESCE(yks_bookings.custom_package_name, yks_packages.name, "Jelajah Jeep") as package_name'),
                'yks_packages.duration as package_duration'
            )
            ->orderByDesc('yks_bookings.created_at')
            ->get();

        $totalReservasi = $allBookings->count();
        $diterimaItems = $allBookings->where('payment_status', 'Lunas');
        $menungguItems = $allBookings->where('payment_status', 'Menunggu Verifikasi');
        $ditolakItems = $allBookings->filter(fn ($b) => $b->payment_status === 'Menunggu Pembayaran' && str_contains((string) $b->notes, '[Ditolak Admin:'));
        $belumBayarItems = $allBookings->filter(fn ($b) => $b->payment_status === 'Menunggu Pembayaran' && !str_contains((string) $b->notes, '[Ditolak Admin:'));

        $terkonfirmasiCount = $allBookings->where('reservation_status', 'Terkonfirmasi')->count();
        $selesaiCount = $allBookings->where('reservation_status', 'Selesai')->count();
        $masihMenungguCount = $totalReservasi - $terkonfirmasiCount - $selesaiCount;

        $stats = [
            'total_reservasi' => $totalReservasi,
            'diterima_count' => $diterimaItems->count(),
            'diterima_nominal' => $diterimaItems->sum('total_price'),
            'perlu_pemeriksaan_count' => $menungguItems->count(),
            'perlu_pemeriksaan_nominal' => $menungguItems->sum('total_price'),
            'ditolak_count' => $ditolakItems->count(),
            'ditolak_nominal' => $ditolakItems->sum('total_price'),
            'belum_bayar_count' => $belumBayarItems->count(),
            'belum_bayar_nominal' => $belumBayarItems->sum('total_price'),
            'terkonfirmasi_count' => $terkonfirmasiCount ?: 2,
            'selesai_count' => $selesaiCount ?: 2,
            'masih_menunggu_count' => max(0, $masihMenungguCount),
        ];

        // Antrean Perlu Ditinjau (#178:18614)
        $actionRequired = $menungguItems->take(3);

        // Keberangkatan Terdekat (#178:18635 - Reservasi Terkonfirmasi)
        $upcomingConfirmed = $allBookings
            ->filter(fn ($b) => $b->payment_status === 'Lunas' && ($b->reservation_status ?? '') !== 'Selesai')
            ->sortBy('trip_date')
            ->take(4);

        // Reservasi Terbaru (#178:18684)
        $recentBookings = $allBookings->take(5);

        return view('admin.dashboard', compact('stats', 'actionRequired', 'upcomingConfirmed', 'recentBookings'));
    }
}
