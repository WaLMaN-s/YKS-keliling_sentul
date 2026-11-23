<?php

namespace App\Http\Controllers;

use App\Models\YksAddon;
use App\Models\YksBooking;
use App\Models\YksPackage;
use App\Models\YksPayment;
use App\Models\YksUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
        $addons = Schema::hasTable('yks_addons')
            ? YksAddon::where('is_aktif', true)->get()
            : collect();
        $units = Schema::hasTable('yks_units')
            ? YksUnit::where('status', 'aktif')->get()
            : collect();

        return view('bookings.create', compact('package', 'allPackages', 'addons', 'units'));
    }

    /**
     * Proses Pembuatan Reservasi Sesuai SDD Bab 3 & Bab 6:
     * - Pemeriksaan ketersediaan jadwal & tanggal blokir
     * - Perhitungan Total = Subtotal Paket + Subtotal Add-on
     * - Pembuatan Order ID format YKS-YYYYMMDD-NNNN dengan transaksi lockForUpdate()
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:yks_packages,id',
            'jenis_trip' => 'nullable|in:open,private',
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:25',
            'customer_email' => 'required|email|max:120',
            'trip_date' => 'required|date|after_or_equal:today',
            'participant_count' => 'required|integer|min:1|max:100',
            'motor_pribadi' => 'nullable|boolean',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string|max:500',
            'addons' => 'nullable|array',
            'addons.*' => 'nullable|integer|min:0|max:50',
            'unit_ids' => 'nullable|array',
            'unit_ids.*' => 'nullable|exists:yks_units,id',
        ]);

        $tripDateStr = substr($validated['trip_date'], 0, 10);

        // 1. Pemeriksaan tanggal diblokir & kuota harian (Bab 6 SDD)
        $schedulePath = storage_path('app/yks_schedules.json');
        $dailyCapacity = 12;
        if (file_exists($schedulePath)) {
            $scheduleConfig = json_decode(file_get_contents($schedulePath), true);
            $blockedDates = $scheduleConfig['blocked_dates'] ?? [];
            $dailyCapacity = (int) ($scheduleConfig['daily_capacity'] ?? 12);
            if (isset($blockedDates[$tripDateStr])) {
                return back()
                    ->withInput()
                    ->with('error', "Mohon maaf, tanggal {$tripDateStr} sedang ditutup ({$blockedDates[$tripDateStr]}). Silakan pilih tanggal lain.");
            }
        }

        $package = YksPackage::findOrFail($validated['package_id']);

        // 2. Transaksi database dengan penguncian baris (SELECT ... FOR UPDATE) untuk mencegah overbooking (Bab 6 SDD)
        return DB::transaction(function () use ($request, $validated, $package, $tripDateStr, $dailyCapacity) {
            $existingBookingsOnDate = YksBooking::whereDate('trip_date', $tripDateStr)
                ->where('payment_status', '!=', 'Dibatalkan')
                ->lockForUpdate()
                ->count();

            if ($existingBookingsOnDate >= $dailyCapacity) {
                return back()
                    ->withInput()
                    ->with('error', "Kuota jadwal pada tanggal {$tripDateStr} telah penuh ({$dailyCapacity} slot). Silakan pilih tanggal keberangkatan lain.");
            }

            // 3. Perhitungan Matematis SDD Bab 6:
            // Total = Subtotal Paket + Subtotal Add-on
            $subtotalPackage = (int) $package->price * (int) $validated['participant_count'];
            $subtotalAddons = 0;
            $addonSummaryList = [];
            $addonPivotRows = [];

            if (!empty($validated['addons']) && Schema::hasTable('yks_addons')) {
                $addonIds = array_keys(array_filter($validated['addons'], fn ($qty) => (int) $qty > 0));
                if (!empty($addonIds)) {
                    $dbAddons = YksAddon::whereIn('id', $addonIds)->where('is_aktif', true)->get();
                    foreach ($dbAddons as $addon) {
                        $qty = (int) ($validated['addons'][$addon->id] ?? 0);
                        if ($qty > 0) {
                            $itemSub = $qty * (int) $addon->harga;
                            $subtotalAddons += $itemSub;
                            $addonSummaryList[] = "{$addon->nama_addon} ({$qty}x)";
                            $addonPivotRows[] = [
                                'addon_id' => $addon->id,
                                'jumlah' => $qty,
                                'harga_satuan' => (int) $addon->harga,
                                'subtotal' => $itemSub,
                            ];
                        }
                    }
                }
            }

            $totalPrice = $subtotalPackage + $subtotalAddons;

            // 4. Pembuatan Order ID Sesuai Bab 6 SDD: YKS-YYYYMMDD-NNNN (Nomor urut harian 4 digit)
            $datePrefix = 'YKS-' . date('Ymd') . '-';
            $todaySeq = YksBooking::where('booking_code', 'like', $datePrefix . '%')
                ->lockForUpdate()
                ->count() + 1;
            $bookingCode = $datePrefix . str_pad((string) $todaySeq, 4, '0', STR_PAD_LEFT);
            while (YksBooking::where('booking_code', $bookingCode)->exists()) {
                $todaySeq++;
                $bookingCode = $datePrefix . str_pad((string) $todaySeq, 4, '0', STR_PAD_LEFT);
            }

            // 5. Cek Pilihan Unit Kendaraan
            $selectedUnitCodes = [];
            $unitIdsToAttach = [];
            if (!empty($validated['unit_ids']) && Schema::hasTable('yks_units')) {
                $units = YksUnit::whereIn('id', $validated['unit_ids'])->where('status', 'aktif')->get();
                foreach ($units as $u) {
                    $selectedUnitCodes[] = $u->kode_unit;
                    $unitIdsToAttach[] = $u->id;
                }
            }

            $bookingData = [
                'booking_code' => $bookingCode,
                'user_id' => null,
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
            ];

            if (Schema::hasColumn('yks_bookings', 'jenis_trip')) {
                $bookingData['jenis_trip'] = $validated['jenis_trip'] ?? 'private';
                $bookingData['motor_pribadi'] = $request->boolean('motor_pribadi') || str_contains(strtolower($package->name), 'pribadi');
                $bookingData['subtotal_package'] = $subtotalPackage;
                $bookingData['subtotal_addons'] = $subtotalAddons;
                $bookingData['selected_addons'] = !empty($addonSummaryList) ? implode(', ', $addonSummaryList) : null;
                $bookingData['selected_units'] = !empty($selectedUnitCodes) ? implode(', ', $selectedUnitCodes) : null;
            }

            $booking = YksBooking::create($bookingData);

            // Simpan ke tabel pivot reservasi_addon (yks_booking_addons)
            if (!empty($addonPivotRows) && Schema::hasTable('yks_booking_addons')) {
                foreach ($addonPivotRows as $row) {
                    DB::table('yks_booking_addons')->insert([
                        'booking_id' => $booking->id,
                        'addon_id' => $row['addon_id'],
                        'jumlah' => $row['jumlah'],
                        'harga_satuan' => $row['harga_satuan'],
                        'subtotal' => $row['subtotal'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Simpan ke tabel pivot reservasi_unit (yks_booking_units)
            if (!empty($unitIdsToAttach) && Schema::hasTable('yks_booking_units')) {
                foreach ($unitIdsToAttach as $uid) {
                    DB::table('yks_booking_units')->insert([
                        'booking_id' => $booking->id,
                        'unit_id' => $uid,
                        'trip_date' => $validated['trip_date'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            return redirect()->route('payment.show', $booking->booking_code)
                ->with('success', "Reservasi berhasil dibuat dengan Order ID {$booking->booking_code}! Silakan simpan kode ini untuk melacak status pesanan.");
        });
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
            mkdir($uploadDir, 0775, true);
        }
        $file->move($uploadDir, $filename);

        $proofPath = 'uploads/bukti/' . $filename;

        $booking->update([
            'payment_proof' => $proofPath,
            'payment_status' => 'Menunggu Verifikasi',
        ]);

        // Catat riwayat ke tabel pembayaran (1:N sesuai SDD Bab 4.1)
        if (Schema::hasTable('yks_payments')) {
            YksPayment::create([
                'booking_id' => $booking->id,
                'nominal' => $booking->total_price,
                'bukti_path' => $proofPath,
                'status' => 'menunggu_verifikasi',
                'tgl_upload' => now(),
            ]);
        }

        return redirect()->route('booking.track', ['kode' => $booking->booking_code])
            ->with('success', 'Bukti transfer berhasil diunggah! Admin sedang memverifikasi pesanan Anda.');
    }

    /**
     * Lacak Status Booking Tanpa Login (Berdasarkan Order ID / Kode Booking, Nomor WhatsApp, atau Email)
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
