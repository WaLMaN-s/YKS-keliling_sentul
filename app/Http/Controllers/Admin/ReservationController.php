<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\YksPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    /**
     * Administrasi / Reservasi (Figma ON GOING Frame 178:19479).
     */
    public function index(Request $request)
    {
        $search = $request->query('q');
        $packageFilter = $request->query('package_id');
        $resStatusFilter = $request->query('reservation_status');
        $payStatusFilter = $request->query('payment_status');
        $selectedCode = $request->query('select');

        $packages = YksPackage::orderBy('id', 'asc')->get();

        // Hitung ringkasan untuk badge "2 terkonfirmasi · 2 selesai · 4 menunggu"
        $allBookings = DB::table('yks_bookings')->get();
        $counts = [
            'total' => $allBookings->count(),
            'terkonfirmasi' => $allBookings->where('reservation_status', 'Terkonfirmasi')->count(),
            'selesai' => $allBookings->where('reservation_status', 'Selesai')->count(),
            'menunggu' => $allBookings->whereIn('reservation_status', ['Menunggu verifikasi', 'Menunggu pembayaran'])->count(),
        ];

        $query = DB::table('yks_bookings')
            ->leftJoin('yks_packages', 'yks_bookings.package_id', '=', 'yks_packages.id')
            ->select(
                'yks_bookings.*',
                'yks_packages.name as catalog_package_name',
                'yks_packages.price as catalog_package_price',
                'yks_packages.price_unit as package_price_unit',
                'yks_packages.duration as package_duration',
                'yks_packages.capacity_label as package_capacity_label',
                'yks_packages.meeting_point as catalog_meeting_point'
            );

        if ($packageFilter) {
            $query->where('yks_bookings.package_id', $packageFilter);
        }

        if ($resStatusFilter) {
            $query->where('yks_bookings.reservation_status', $resStatusFilter);
        }

        if ($payStatusFilter) {
            $query->where('yks_bookings.payment_status', $payStatusFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('yks_bookings.booking_code', 'like', "%{$search}%")
                  ->orWhere('yks_bookings.customer_name', 'like', "%{$search}%")
                  ->orWhere('yks_bookings.customer_phone', 'like', "%{$search}%")
                  ->orWhere('yks_bookings.custom_package_name', 'like', "%{$search}%")
                  ->orWhere('yks_packages.name', 'like', "%{$search}%");
            });
        }

        $reservations = $query->orderByDesc('yks_bookings.id')->paginate(12)->withQueryString();

        // Tentukan item yang ditampilkan pada panel Detail di bagian bawah
        $selectedBooking = null;
        if ($selectedCode) {
            $selectedBooking = DB::table('yks_bookings')
                ->leftJoin('yks_packages', 'yks_bookings.package_id', '=', 'yks_packages.id')
                ->select(
                    'yks_bookings.*',
                    'yks_packages.name as catalog_package_name',
                    'yks_packages.price as catalog_package_price',
                    'yks_packages.price_unit as package_price_unit',
                    'yks_packages.duration as package_duration',
                    'yks_packages.capacity_label as package_capacity_label',
                    'yks_packages.meeting_point as catalog_meeting_point'
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
            'packages',
            'packageFilter',
            'resStatusFilter',
            'payStatusFilter',
            'search',
            'counts'
        ));
    }

    /**
     * Form Tambah Reservasi Manual (Figma ON GOING Frame 298:2)
     */
    public function createManual()
    {
        $packages = YksPackage::orderBy('id', 'asc')->get();
        return view('admin.reservations.create', compact('packages'));
    }

    /**
     * Simpan Reservasi Manual Non-Website (Private Trip / WhatsApp / Telepon / Datang langsung)
     */
    public function storeManual(Request $request)
    {
        $validated = $request->validate([
            'booking_channel' => 'required|string|max:50',
            'customer_name' => 'required|string|max:150',
            'customer_phone' => 'required|string|max:40',
            'customer_email' => 'nullable|email|max:150',
            'group_name' => 'nullable|string|max:150',
            'package_id' => 'nullable|exists:yks_packages,id',
            'custom_package_name' => 'required|string|max:180',
            'trip_date' => 'required|date',
            'start_time' => 'required|string|max:40',
            'participant_count' => 'required|integer|min:1|max:500',
            'vehicle_count' => 'nullable|integer|min:1|max:100',
            'meeting_point' => 'required|string|max:200',
            'notes' => 'nullable|string|max:1000',
            'package_agreed_price' => 'required|numeric|min:0',
            'addon_price' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'payment_status' => 'required|string|max:50',
            'reservation_status' => 'required|string|max:50',
            'admin_notes' => 'nullable|string|max:1000',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
        ]);

        // Tentukan package_id (gunakan paket yang cocok atau fallback ke Private Trip / paket pertama)
        $packageId = $validated['package_id'] ?? null;
        if (!$packageId) {
            $matchedPkg = YksPackage::where('name', 'like', '%' . $validated['custom_package_name'] . '%')->first()
                ?? YksPackage::where('booking_mode', 'whatsapp_consultation')->first()
                ?? YksPackage::first();
            $packageId = $matchedPkg ? $matchedPkg->id : 1;
        }

        // Generate kode reservasi YKS-26XXX
        $lastId = (int) DB::table('yks_bookings')->max('id');
        $bookingCode = 'YKS-' . (26080 + $lastId + 1);
        while (DB::table('yks_bookings')->where('booking_code', $bookingCode)->exists()) {
            $bookingCode = 'YKS-' . rand(26100, 29999);
        }

        $agreedPrice = (int) $validated['package_agreed_price'];
        $addonPrice = (int) ($validated['addon_price'] ?? 0);
        $totalPrice = $agreedPrice + $addonPrice;
        $paidAmount = (int) ($validated['paid_amount'] ?? 0);

        $proofPath = null;
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = 'BUKTI_' . $bookingCode . '_' . time() . '.' . $file->getClientOriginalExtension();
            $uploadDir = public_path('uploads/bukti');
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0777, true);
            }
            @chmod($uploadDir, 0777);
            file_put_contents($uploadDir . DIRECTORY_SEPARATOR . $filename, file_get_contents($file->getRealPath()));
            $proofPath = 'uploads/bukti/' . $filename;
        }

        DB::table('yks_bookings')->insert([
            'booking_code' => $bookingCode,
            'package_id' => $packageId,
            'custom_package_name' => $validated['custom_package_name'],
            'booking_source' => 'Non-Website',
            'booking_channel' => $validated['booking_channel'],
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'] ?: strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $validated['customer_name'])) . '@whatsapp.yks',
            'customer_phone' => $validated['customer_phone'],
            'group_name' => $validated['group_name'] ?? null,
            'trip_date' => $validated['trip_date'],
            'start_time' => $validated['start_time'],
            'participant_count' => (int) $validated['participant_count'],
            'vehicle_count' => (int) ($validated['vehicle_count'] ?? 1),
            'meeting_point' => $validated['meeting_point'],
            'package_agreed_price' => $agreedPrice,
            'addon_price' => $addonPrice,
            'addon_notes' => $addonPrice > 0 ? 'Add-on disepakati Rp' . number_format($addonPrice, 0, ',', '.') : 'Tidak dipilih · Rp0',
            'total_price' => $totalPrice,
            'paid_amount' => $paidAmount,
            'payment_method' => 'Transfer Bank (' . $validated['booking_channel'] . ')',
            'payment_status' => $validated['payment_status'],
            'reservation_status' => $validated['reservation_status'],
            'payment_proof' => $proofPath,
            'notes' => $validated['notes'] ?? null,
            'admin_notes' => $validated['admin_notes'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('admin.reservations.index', ['select' => $bookingCode])
            ->with('success', "Reservasi manual {$bookingCode} ({$validated['customer_name']}) berhasil disimpan!");
    }

    /**
     * Detail Reservasi Redirect
     */
    public function show($booking_code)
    {
        return redirect()->route('admin.reservations.index', ['select' => $booking_code]);
    }

    /**
     * Perbarui Status Reservasi & Catatan Perubahan (Figma Frame 178:19778)
     */
    public function updateStatus(Request $request, $booking_code)
    {
        $validated = $request->validate([
            'reservation_status' => 'nullable|string|max:50',
            'payment_status' => 'nullable|string|max:50',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $booking = DB::table('yks_bookings')->where('booking_code', $booking_code)->first();
        if (!$booking) {
            abort(404);
        }

        $newResStatus = $validated['reservation_status'] ?? $booking->reservation_status;
        $newPayStatus = $validated['payment_status'] ?? $booking->payment_status;

        $updateData = [
            'reservation_status' => $newResStatus,
            'payment_status' => $newPayStatus,
            'updated_at' => now(),
        ];

        if ($request->filled('admin_notes')) {
            $updateData['admin_notes'] = $validated['admin_notes'];
        }

        DB::table('yks_bookings')
            ->where('booking_code', $booking_code)
            ->update($updateData);

        return redirect()
            ->route('admin.reservations.index', ['select' => $booking_code])
            ->with('success', "Status reservasi {$booking_code} berhasil diperbarui menjadi \"{$newResStatus}\".");
    }

    /**
     * Unggah / Perbarui Bukti Pembayaran oleh Admin
     */
    public function uploadProof(Request $request, $booking_code)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $file = $request->file('payment_proof');
        $filename = 'BUKTI_' . $booking_code . '_' . time() . '.' . $file->getClientOriginalExtension();
        $uploadDir = public_path('uploads/bukti');
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }
        @chmod($uploadDir, 0777);
        file_put_contents($uploadDir . DIRECTORY_SEPARATOR . $filename, file_get_contents($file->getRealPath()));

        DB::table('yks_bookings')->where('booking_code', $booking_code)->update([
            'payment_proof' => 'uploads/bukti/' . $filename,
            'payment_status' => 'Menunggu Verifikasi',
            'reservation_status' => 'Menunggu verifikasi',
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('admin.reservations.index', ['select' => $booking_code])
            ->with('success', "Bukti pembayaran pesanan {$booking_code} berhasil diperbarui.");
    }
}
