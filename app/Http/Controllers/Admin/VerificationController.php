<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerificationController extends Controller
{
    /**
     * Administrasi / Verifikasi Pembayaran (Figma).
     */
    public function index()
    {
        $pendingPayments = DB::table('yks_bookings')
            ->join('yks_packages', 'yks_bookings.package_id', '=', 'yks_packages.id')
            ->select('yks_bookings.*', 'yks_packages.name as package_name')
            ->where('payment_status', 'Menunggu Verifikasi')
            ->orderBy('yks_bookings.updated_at', 'asc')
            ->get();

        return view('admin.verifications.index', compact('pendingPayments'));
    }

    /**
     * Konfirmasi / Terima Pembayaran
     */
    public function approve($booking_code)
    {
        DB::table('yks_bookings')->where('booking_code', $booking_code)->update([
            'payment_status' => 'Lunas',
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', "Pembayaran untuk {$booking_code} berhasil disetujui (Lunas).");
    }

    /**
     * Tolak Bukti Pembayaran
     */
    public function reject(Request $request, $booking_code)
    {
        DB::table('yks_bookings')->where('booking_code', $booking_code)->update([
            'payment_status' => 'Menunggu Pembayaran',
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('warning', "Pembayaran untuk {$booking_code} ditolak. Menunggu pengiriman ulang bukti transfer.");
    }
}
