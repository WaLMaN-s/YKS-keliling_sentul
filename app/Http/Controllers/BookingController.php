<?php

namespace App\Http\Controllers;

use App\Models\YksBooking;
use App\Models\YksPackage;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function create(Request $request)
    {
        $packageId = $request->query('paket_id');
        $package = $packageId
            ? YksPackage::with('category')->where('id', $packageId)->where('is_active', true)->first()
            : YksPackage::with('category')->where('is_active', true)->where('booking_mode', '!=', 'whatsapp_only')->first();

        if (!$package) {
            $package = YksPackage::with('category')->where('is_active', true)->first();
        }

        if (!$package) {
            return redirect()->route('packages.index')->with('info', 'Silakan pilih paket wisata terlebih dahulu.');
        }

        // Private Trip tidak memiliki booking langsung di website (harus konsultasi WhatsApp)
        if ($package->is_private_trip) {
            return redirect()->route('packages.show', $package->slug)
                ->with('info', 'Paket Private Trip dipesan melalui konsultasi WhatsApp bersama Admin YKS.');
        }

        $allPackages = YksPackage::with('category')
            ->where('is_active', true)
            ->where('booking_mode', '!=', 'whatsapp_only')
            ->orderBy('id', 'asc')
            ->get();

        return view('bookings.create', compact('package', 'allPackages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:yks_packages,id',
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:25',
            'customer_email' => 'required|email|max:120',
            'trip_date' => 'required|date|after_or_equal:today',
            'start_time' => 'nullable|string|max:30',
            'participant_count' => 'required|integer|min:1|max:100',
            'payment_method' => 'required|string',
            'addons' => 'nullable|array',
            'notes' => 'nullable|string|max:500',
        ]);

        $package = YksPackage::findOrFail($validated['package_id']);

        // Hitung Add-on khusus Bawa Motor Pribadi (Sepatu Rp100k, Guide Rp200k, Paket protector Rp250k)
        $addonCatalog = [
            'sepatu' => ['label' => 'Sepatu', 'price' => 100000],
            'guide' => ['label' => 'Guide', 'price' => 200000],
            'protector' => ['label' => 'Paket protector', 'price' => 250000],
        ];
        $addonPricePerPerson = 0;
        $selectedAddonLabels = [];
        if ($package->is_motor_pribadi && !empty($validated['addons'])) {
            foreach ($validated['addons'] as $addonKey) {
                if (isset($addonCatalog[$addonKey])) {
                    $addonPricePerPerson += $addonCatalog[$addonKey]['price'];
                    $selectedAddonLabels[] = $addonCatalog[$addonKey]['label'] . ' (Rp' . number_format($addonCatalog[$addonKey]['price'], 0, ',', '.') . ')';
                }
            }
        }

        $count = (int) $validated['participant_count'];
        $packageBaseTotal = $package->price * $count;
        $addonTotal = $addonPricePerPerson * $count;
        $totalPrice = $packageBaseTotal + $addonTotal;

        $nextNumber = YksBooking::count() + 26082;
        $bookingCode = 'YKS-' . $nextNumber;
        while (YksBooking::where('booking_code', $bookingCode)->exists()) {
            $nextNumber++;
            $bookingCode = 'YKS-' . $nextNumber;
        }

        $booking = YksBooking::create([
            'booking_code' => $bookingCode,
            'user_id' => null,
            'package_id' => $package->id,
            'booking_source' => 'Website',
            'booking_channel' => 'Website',
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'],
            'trip_date' => $validated['trip_date'],
            'start_time' => $validated['start_time'] ?: '08.00–12.00',
            'participant_count' => $count,
            'vehicle_count' => str_contains(strtolower($package->name), 'jeep') ? $count : $count,
            'meeting_point' => $package->meeting_point,
            'package_agreed_price' => $packageBaseTotal,
            'addon_price' => $addonTotal,
            'addon_notes' => !empty($selectedAddonLabels) ? implode(', ', $selectedAddonLabels) : 'Tidak dipilih · Rp0',
            'total_price' => $totalPrice,
            'paid_amount' => 0,
            'payment_method' => $validated['payment_method'],
            'notes' => $validated['notes'],
            'payment_status' => 'Menunggu Pembayaran',
            'reservation_status' => 'Menunggu pembayaran',
        ]);

        return redirect()->route('payment.show', $booking->booking_code)
            ->with('success', "Reservasi berhasil dibuat dengan Nomor Referensi {$booking->booking_code}! Silakan simpan kode ini untuk melacak status pesanan.");
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
        $uploadDir = public_path('uploads/bukti');
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }
        @chmod($uploadDir, 0777);
        file_put_contents($uploadDir . DIRECTORY_SEPARATOR . $filename, file_get_contents($file->getRealPath()));

        // Hapus catatan penolakan sebelumnya setelah pelanggan mengunggah bukti baru
        $cleanedNotes = $booking->notes
            ? trim(preg_replace('/\[Ditolak Admin:\s*.*?\]/s', '', $booking->notes))
            : null;

        $booking->update([
            'payment_proof' => 'uploads/bukti/' . $filename,
            'payment_status' => 'Menunggu Verifikasi',
            'reservation_status' => 'Menunggu verifikasi',
            'paid_amount' => $booking->total_price,
            'notes' => $cleanedNotes ?: null,
            'admin_notes' => 'Pelanggan mengunggah bukti transfer baru.',
        ]);

        return redirect()->route('booking.track', ['kode' => $booking->booking_code])
            ->with('success', 'Bukti transfer berhasil diunggah! Admin sedang memeriksa bukti pembayaran Anda.');
    }

    /**
     * Lacak Status Booking Tanpa Login (Berdasarkan Kode Booking, Nomor WhatsApp, atau Email)
     */
    public function track(Request $request)
    {
        $kode = trim((string) $request->query('kode', ''));
        $selectedCode = $request->query('pilih');
        $booking = null;
        $matchedBookings = collect();

        if ($kode !== '') {
            $matchedBookings = YksBooking::with('package')
                ->where('booking_code', $kode)
                ->orWhere('customer_phone', $kode)
                ->orWhere('customer_email', $kode)
                ->latest()
                ->get();

            if ($selectedCode) {
                $booking = $matchedBookings->firstWhere('booking_code', $selectedCode);
            }
            if (!$booking) {
                $booking = $matchedBookings->first();
            }
        }

        if (session()->has('recent_bookings')) {
            session()->forget('recent_bookings');
        }

        return view('bookings.track', compact('booking', 'matchedBookings', 'kode'));
    }

    /**
     * Pelanggan Mengubah Jadwal Trip (Reschedule Mandiri Maksimal H-2)
     */
    public function reschedule(Request $request, $booking_code)
    {
        $booking = YksBooking::where('booking_code', $booking_code)->firstOrFail();

        if (!$booking->can_reschedule) {
            return redirect()->route('booking.track', ['kode' => $booking->booking_code])
                ->with('error', 'Perubahan jadwal mandiri hanya dapat dilakukan minimal H-2 sebelum hari kegiatan. Untuk perubahan H-1 atau hari kegiatan, silakan hubungi Admin via WhatsApp.');
        }

        $minNewDate = now()->addDays(2)->format('Y-m-d');

        $validated = $request->validate([
            'new_trip_date' => 'required|date|after_or_equal:' . $minNewDate,
            'reschedule_reason' => 'nullable|string|max:255',
        ], [
            'new_trip_date.required' => 'Silakan pilih tanggal jadwal trip yang baru.',
            'new_trip_date.after_or_equal' => 'Tanggal baru minimal H+2 dari hari ini.',
        ]);

        $newDateStr = substr($validated['new_trip_date'], 0, 10);
        $currentDateStr = substr((string) $booking->trip_date, 0, 10);

        if ($newDateStr === $currentDateStr) {
            return redirect()->route('booking.track', ['kode' => $booking->booking_code])
                ->with('info', 'Tanggal yang Anda pilih sama dengan tanggal reservasi saat ini.');
        }

        $schedulePath = storage_path('app/yks_schedules.json');
        if (file_exists($schedulePath)) {
            $scheduleConfig = json_decode(file_get_contents($schedulePath), true);
            $blockedDates = $scheduleConfig['blocked_dates'] ?? [];
            if (isset($blockedDates[$newDateStr])) {
                $blockReason = $blockedDates[$newDateStr];
                return redirect()->route('booking.track', ['kode' => $booking->booking_code])
                    ->with('error', "Tanggal {$newDateStr} sedang tidak tersedia untuk trip ({$blockReason}). Silakan pilih tanggal lain.");
            }
        }

        $oldDateFormatted = \Carbon\Carbon::parse($booking->trip_date)->translatedFormat('d M Y');
        $newDateFormatted = \Carbon\Carbon::parse($validated['new_trip_date'])->translatedFormat('d M Y');
        $reasonText = !empty($validated['reschedule_reason']) ? ' (Alasan: ' . trim($validated['reschedule_reason']) . ')' : '';
        $timestamp = now()->format('d/m/Y H:i');

        $logEntry = "\n[Ganti Jadwal: Diubah dari {$oldDateFormatted} ke {$newDateFormatted} oleh Pelanggan pada {$timestamp}{$reasonText}]";

        $booking->trip_date = $validated['new_trip_date'];
        $booking->notes = ($booking->notes ?? '') . $logEntry;
        $booking->save();

        return redirect()->route('booking.track', ['kode' => $booking->booking_code])
            ->with('success', "Jadwal kegiatan berhasil diubah ke tanggal {$newDateFormatted}. Reservasi Anda telah diperbarui!");
    }
}
