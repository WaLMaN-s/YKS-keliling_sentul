<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    /**
     * Administrasi / Data Reservasi (Figma 2-Column Table + Side Detail).
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('q');
        $selectedCode = $request->query('select');

        // Hitung jumlah per status untuk badge tab
        $counts = [
            'all' => DB::table('yks_bookings')->count(),
            'lunas' => DB::table('yks_bookings')->where('payment_status', 'Lunas')->count(),
            'verifikasi' => DB::table('yks_bookings')->where('payment_status', 'Menunggu Verifikasi')->count(),
            'belum_bayar' => DB::table('yks_bookings')->where('payment_status', 'Menunggu Pembayaran')->count(),
            'dibatalkan' => DB::table('yks_bookings')->where('payment_status', 'Dibatalkan')->count(),
        ];

        $query = DB::table('yks_bookings')
            ->join('yks_packages', 'yks_bookings.package_id', '=', 'yks_packages.id')
            ->select(
                'yks_bookings.*',
                'yks_packages.name as package_name',
                'yks_packages.price as package_price',
                'yks_packages.duration as package_duration',
                'yks_packages.meeting_point as meeting_point'
            );

        if ($status) {
            $query->where('yks_bookings.payment_status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('yks_bookings.booking_code', 'like', "%{$search}%")
                  ->orWhere('yks_bookings.customer_name', 'like', "%{$search}%")
                  ->orWhere('yks_bookings.customer_phone', 'like', "%{$search}%")
                  ->orWhere('yks_packages.name', 'like', "%{$search}%");
            });
        }

        $reservations = $query->orderByDesc('yks_bookings.created_at')->paginate(10)->withQueryString();

        // Tentukan item yang ditampilkan pada panel Detail Reservasi di kanan
        $selectedBooking = null;
        if ($selectedCode) {
            $selectedBooking = DB::table('yks_bookings')
                ->join('yks_packages', 'yks_bookings.package_id', '=', 'yks_packages.id')
                ->select(
                    'yks_bookings.*',
                    'yks_packages.name as package_name',
                    'yks_packages.price as package_price',
                    'yks_packages.duration as package_duration',
                    'yks_packages.meeting_point as meeting_point'
                )
                ->where('yks_bookings.booking_code', $selectedCode)
                ->first();
        }
        if (!$selectedBooking && $reservations->count() > 0) {
            $selectedBooking = $reservations->first();
        }

        return view('admin.reservations.index', compact(
            'reservations',
            'selectedBooking',
            'status',
            'search',
            'counts'
        ));
    }

    /**
     * Detail Reservasi (Halaman Mandiri / Print View)
     */
    public function show($booking_code)
    {
        return redirect()->route('admin.reservations.index', ['select' => $booking_code]);
    }

    /**
     * Ubah Status Reservasi
     */
    public function updateStatus(Request $request, $booking_code)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:Menunggu Pembayaran,Menunggu Verifikasi,Lunas,Dibatalkan',
        ]);

        DB::table('yks_bookings')
            ->where('booking_code', $booking_code)
            ->update([
                'payment_status' => $validated['payment_status'],
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('admin.reservations.index', ['select' => $booking_code])
            ->with('success', "Status reservasi {$booking_code} berhasil diperbarui menjadi \"{$validated['payment_status']}\".");
    }
}
