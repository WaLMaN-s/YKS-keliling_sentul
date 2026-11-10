<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerificationController extends Controller
{
    /**
     * Administrasi / Verifikasi Pembayaran (Figma 2-Column Master-Detail).
     */
    public function index(Request $request)
    {
        $search = $request->query('q');
        $selectedCode = $request->query('select');

        $query = DB::table('yks_bookings')
            ->join('yks_packages', 'yks_bookings.package_id', '=', 'yks_packages.id')
            ->select(
                'yks_bookings.*',
                'yks_packages.name as package_name',
                'yks_packages.price as package_price',
                'yks_packages.duration as package_duration'
            )
            ->where('payment_status', 'Menunggu Verifikasi')
            ->orderBy('yks_bookings.updated_at', 'asc');

        $allPending = $query->get();

        $pendingPayments = $allPending;
        if ($search) {
            $pendingPayments = $allPending->filter(function ($item) use ($search) {
                return stripos($item->booking_code, $search) !== false
                    || stripos($item->customer_name, $search) !== false
                    || stripos($item->package_name, $search) !== false;
            });
        }

        $selectedItem = null;
        if ($selectedCode) {
            $selectedItem = $allPending->firstWhere('booking_code', $selectedCode);
        }
        if (!$selectedItem && $pendingPayments->isNotEmpty()) {
            $selectedItem = $pendingPayments->first();
        }

        return view('admin.verifications.index', compact(
            'pendingPayments',
            'allPending',
            'selectedItem',
            'search'
        ));
    }

    /**
     * Konfirmasi / Terima Pembayaran (Lunas)
     */
    public function approve($booking_code)
    {
        DB::table('yks_bookings')->where('booking_code', $booking_code)->update([
            'payment_status' => 'Lunas',
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('admin.verifications.index')
            ->with('success', "Pembayaran untuk pesanan {$booking_code} berhasil disetujui (Status: Lunas).");
    }

    /**
     * Tolak Bukti Pembayaran beserta Alasan Penolakan
     */
    public function reject(Request $request, $booking_code)
    {
        $reason = trim((string) $request->input('rejection_reason', 'Bukti transfer tidak sesuai dengan mutasi rekening.'));
        $booking = DB::table('yks_bookings')->where('booking_code', $booking_code)->first();

        $updatedNotes = $booking && $booking->notes
            ? $booking->notes . "\n[Ditolak Admin: {$reason}]"
            : "[Ditolak Admin: {$reason}]";

        DB::table('yks_bookings')->where('booking_code', $booking_code)->update([
            'payment_status' => 'Menunggu Pembayaran',
            'notes' => $updatedNotes,
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('admin.verifications.index')
            ->with('warning', "Bukti pembayaran {$booking_code} ditolak ({$reason}). Status dikembalikan ke Menunggu Pembayaran.");
    }
}
