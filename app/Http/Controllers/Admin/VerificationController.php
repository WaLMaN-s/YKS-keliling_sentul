<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\YksPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerificationController extends Controller
{
    /**
     * Administrasi / Pembayaran (Figma ON GOING Frame 178:19820).
     */
    public function index(Request $request)
    {
        $search = $request->query('q');
        $statusTab = $request->query('tab', 'all'); // all, menunggu, diterima, ditolak
        $packageFilter = $request->query('package_id');

        $packages = YksPackage::orderBy('id', 'asc')->get();

        // Ambil semua transaksi yang memiliki aktivitas bukti / status pembayaran
        $allPayments = DB::table('yks_bookings')
            ->leftJoin('yks_packages', 'yks_bookings.package_id', '=', 'yks_packages.id')
            ->select(
                'yks_bookings.*',
                'yks_packages.name as catalog_package_name',
                'yks_packages.price as catalog_package_price',
                'yks_packages.duration as package_duration'
            )
            ->whereIn('yks_bookings.payment_status', ['Menunggu Verifikasi', 'Lunas', 'Ditolak'])
            ->get();

        $pendingList = $allPayments->where('payment_status', 'Menunggu Verifikasi');
        $acceptedList = $allPayments->where('payment_status', 'Lunas');
        $rejectedList = $allPayments->where('payment_status', 'Ditolak');

        $summary = [
            'all_count' => $allPayments->count(),
            'pending_count' => $pendingList->count(),
            'pending_sum' => $pendingList->sum('total_price'),
            'accepted_count' => $acceptedList->count(),
            'accepted_sum' => $acceptedList->sum('total_price'),
            'rejected_count' => $rejectedList->count(),
            'rejected_sum' => $rejectedList->sum('total_price'),
        ];

        $query = DB::table('yks_bookings')
            ->leftJoin('yks_packages', 'yks_bookings.package_id', '=', 'yks_packages.id')
            ->select(
                'yks_bookings.*',
                'yks_packages.name as catalog_package_name',
                'yks_packages.price as catalog_package_price',
                'yks_packages.duration as package_duration'
            )
            ->whereIn('yks_bookings.payment_status', ['Menunggu Verifikasi', 'Lunas', 'Ditolak']);

        if ($statusTab === 'menunggu') {
            $query->where('yks_bookings.payment_status', 'Menunggu Verifikasi');
        } elseif ($statusTab === 'diterima') {
            $query->where('yks_bookings.payment_status', 'Lunas');
        } elseif ($statusTab === 'ditolak') {
            $query->where('yks_bookings.payment_status', 'Ditolak');
        }

        if ($packageFilter) {
            $query->where('yks_bookings.package_id', $packageFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('yks_bookings.booking_code', 'like', "%{$search}%")
                  ->orWhere('yks_bookings.customer_name', 'like', "%{$search}%")
                  ->orWhere('yks_bookings.custom_package_name', 'like', "%{$search}%")
                  ->orWhere('yks_packages.name', 'like', "%{$search}%");
            });
        }

        $payments = $query
            ->orderByRaw("CASE WHEN yks_bookings.payment_status = 'Menunggu Verifikasi' THEN 0 WHEN yks_bookings.payment_status = 'Ditolak' THEN 1 ELSE 2 END")
            ->orderByDesc('yks_bookings.id')
            ->get();

        return view('admin.verifications.index', compact(
            'payments',
            'summary',
            'packages',
            'statusTab',
            'packageFilter',
            'search'
        ));
    }

    /**
     * Halaman Pemeriksaan Bukti Transfer (Figma ON GOING Frame 178:20073)
     */
    public function show($booking_code)
    {
        $booking = DB::table('yks_bookings')
            ->leftJoin('yks_packages', 'yks_bookings.package_id', '=', 'yks_packages.id')
            ->select(
                'yks_bookings.*',
                'yks_packages.name as catalog_package_name',
                'yks_packages.price as catalog_package_price',
                'yks_packages.duration as package_duration',
                'yks_packages.capacity_label as package_capacity_label'
            )
            ->where('yks_bookings.booking_code', $booking_code)
            ->first();

        if (!$booking) {
            abort(404);
        }

        return view('admin.verifications.show', compact('booking'));
    }

    /**
     * Konfirmasi / Terima Pembayaran (Pembayaran -> Diterima/Lunas, Reservasi -> Terkonfirmasi)
     */
    public function approve($booking_code)
    {
        $booking = DB::table('yks_bookings')->where('booking_code', $booking_code)->first();
        if (!$booking) {
            abort(404);
        }

        if ($booking->payment_status === 'Lunas') {
            return redirect()
                ->route('admin.verifications.show', $booking_code)
                ->with('info', "Pembayaran {$booking_code} sudah berstatus Diterima dan tidak dapat diubah kembali.");
        }

        if ($booking->payment_status === 'Ditolak') {
            return redirect()
                ->route('admin.verifications.show', $booking_code)
                ->with('warning', "Bukti transfer {$booking_code} sudah ditolak. Pelanggan harus mengunggah bukti baru terlebih dahulu.");
        }

        DB::table('yks_bookings')->where('booking_code', $booking_code)->update([
            'payment_status' => 'Lunas',
            'reservation_status' => 'Terkonfirmasi',
            'paid_amount' => $booking->total_price,
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('admin.verifications.index')
            ->with('success', "Pembayaran {$booking_code} berhasil dikonfirmasi (Diterima) dan status reservasi menjadi Terkonfirmasi.");
    }

    /**
     * Tolak Bukti Transfer beserta Alasan Penolakan (Figma Frame 178:20073)
     */
    public function reject(Request $request, $booking_code)
    {
        $booking = DB::table('yks_bookings')->where('booking_code', $booking_code)->first();
        if (!$booking) {
            abort(404);
        }

        // Cegah inkonsistensi: pembayaran yang sudah Diterima (Lunas) tidak boleh ditolak kembali
        if ($booking->payment_status === 'Lunas') {
            return redirect()
                ->route('admin.verifications.show', $booking_code)
                ->with('warning', "Transaksi {$booking_code} sudah dikonfirmasi (Diterima) sehingga bukti transfer tidak dapat ditolak.");
        }

        if ($booking->payment_status === 'Ditolak') {
            return redirect()
                ->route('admin.verifications.show', $booking_code)
                ->with('info', "Bukti transfer {$booking_code} sudah berstatus Ditolak.");
        }

        $request->validate([
            'rejection_reason' => 'required|string|min:3|max:500',
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi saat menolak bukti transfer.',
        ]);

        $reason = trim((string) $request->input('rejection_reason'));

        // Bersihkan tag penolakan lama jika ada, lalu tambahkan alasan penolakan terbaru
        $cleanNotes = $booking->notes ? trim(preg_replace('/\[Ditolak Admin:\s*.*?\]/s', '', $booking->notes)) : '';
        $updatedNotes = $cleanNotes !== ''
            ? $cleanNotes . "\n[Ditolak Admin: {$reason}]"
            : "[Ditolak Admin: {$reason}]";

        DB::table('yks_bookings')->where('booking_code', $booking_code)->update([
            'payment_status' => 'Ditolak',
            'reservation_status' => 'Menunggu pembayaran',
            'notes' => $updatedNotes,
            'admin_notes' => "Bukti transfer ditolak: {$reason}",
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('admin.verifications.index')
            ->with('warning', "Bukti transfer {$booking_code} ditolak ({$reason}). Alasan penolakan telah dikirimkan ke halaman lacak pesanan pelanggan.");
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
            ->route('admin.verifications.show', $booking_code)
            ->with('success', "Berkas bukti pembayaran {$booking_code} berhasil diperbarui.");
    }
}
