<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    /**
     * Administrasi / Data Reservasi (Figma).
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $query = DB::table('yks_bookings')
            ->join('yks_packages', 'yks_bookings.package_id', '=', 'yks_packages.id')
            ->select('yks_bookings.*', 'yks_packages.name as package_name');

        if ($status) {
            $query->where('payment_status', $status);
        }

        $reservations = $query->orderByDesc('yks_bookings.created_at')->paginate(10);

        return view('admin.reservations.index', compact('reservations', 'status'));
    }

    /**
     * Detail Reservasi
     */
    public function show($booking_code)
    {
        $booking = DB::table('yks_bookings')
            ->join('yks_packages', 'yks_bookings.package_id', '=', 'yks_packages.id')
            ->select('yks_bookings.*', 'yks_packages.name as package_name')
            ->where('booking_code', $booking_code)
            ->first();

        if (!$booking) {
            abort(404, 'Reservasi tidak ditemukan');
        }

        return view('admin.reservations.show', compact('booking'));
    }
}
