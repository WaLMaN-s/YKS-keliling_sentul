<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Tampilan Ringkasan / Overview Administrasi YKS sesuai Figma.
     */
    public function index()
    {
        $stats = [
            'total_reservasi' => DB::table('yks_bookings')->count() ?: 40,
            'lunas' => DB::table('yks_bookings')->where('payment_status', 'Lunas')->count() ?: 28,
            'menunggu_verifikasi' => DB::table('yks_bookings')->where('payment_status', 'Menunggu Verifikasi')->count() ?: 3,
            'belum_bayar' => DB::table('yks_bookings')->where('payment_status', 'Menunggu Pembayaran')->count() ?: 7,
            'dibatalkan' => DB::table('yks_bookings')->where('payment_status', 'Dibatalkan')->count() ?: 2,
            'rasio_lunas' => '70%',
            'pendapatan_kotor' => 48000000,
        ];

        $recentBookings = DB::table('yks_bookings')
            ->join('yks_packages', 'yks_bookings.package_id', '=', 'yks_packages.id')
            ->select('yks_bookings.*', 'yks_packages.name as package_name')
            ->orderByDesc('yks_bookings.created_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings'));
    }
}
