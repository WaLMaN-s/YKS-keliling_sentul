<?php

namespace App\Http\Controllers;

use App\Models\YksBooking;
use App\Models\YksPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function create(Request $request)
    {
        $packageId = $request->query('paket_id');
        $package = $packageId 
            ? YksPackage::where('id', $packageId)->where('is_active', true)->first() 
            : YksPackage::where('is_active', true)->first();

        if (!$package) {
            return redirect()->route('packages.index')->with('info', 'Silakan pilih paket wisata terlebih dahulu.');
        }

        $allPackages = YksPackage::where('is_active', true)->get();
        $user = Auth::user();

        return view('bookings.create', compact('package', 'allPackages', 'user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:yks_packages,id',
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:25',
            'customer_email' => 'required|email|max:120',
            'trip_date' => 'required|date|after_or_equal:today',
            'participant_count' => 'required|integer|min:1|max:100',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string|max:500',
        ]);

        $package = YksPackage::findOrFail($validated['package_id']);
        $totalPrice = $package->price * $validated['participant_count'];
        $bookingCode = 'YKS-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        $booking = YksBooking::create([
            'booking_code' => $bookingCode,
            'user_id' => Auth::id(),
            'package_id' => $package->id,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'],
            'trip_date' => $validated['trip_date'],
            'participant_count' => $validated['participant_count'],
            'total_price' => $totalPrice,
            'payment_method' => $validated['payment_method'],
            'notes' => $validated['notes'],
            'payment_status' => 'Menunggu Pembayaran',
        ]);

        return redirect()->route('payment.show', $booking->booking_code)
            ->with('success', 'Pemesanan trip berhasil dibuat! Silakan selesaikan pembayaran.');
    }

    public function payment($booking_code)
    {
        $booking = YksBooking::with('package')->where('booking_code', $booking_code)->firstOrFail();
        return view('bookings.payment', compact('booking'));
    }

    public function uploadProof(Request $request, $booking_code)
    {
        $booking = YksBooking::where('booking_code', $booking_code)->firstOrFail();

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $file = $request->file('payment_proof');
        $filename = 'BUKTI_' . $booking->booking_code . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/bukti'), $filename);

        $booking->update([
            'payment_proof' => $filename,
            'payment_status' => 'Menunggu Verifikasi',
        ]);

        return redirect()->route('booking.track', ['kode' => $booking->booking_code])
            ->with('success', 'Bukti transfer berhasil diunggah! Admin sedang memverifikasi pesanan Anda.');
    }

    public function track(Request $request)
    {
        $kode = $request->query('kode');
        $booking = null;

        if ($kode) {
            $booking = YksBooking::with('package')
                ->where('booking_code', $kode)
                ->orWhere('customer_phone', $kode)
                ->latest()
                ->first();
        }

        return view('bookings.track', compact('booking', 'kode'));
    }
}
