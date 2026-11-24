<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Administrasi / Laporan (Figma ON GOING Frame 178:20541).
     */
    public function index(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $packageId = $request->query('package_id');
        $statusFilter = $request->query('status');

        $packages = DB::table('yks_packages')->orderBy('id', 'asc')->get();

        $query = DB::table('yks_bookings')
            ->leftJoin('yks_packages', 'yks_bookings.package_id', '=', 'yks_packages.id')
            ->select(
                'yks_bookings.*',
                'yks_packages.name as catalog_package_name',
                'yks_packages.price as catalog_package_price'
            );

        if ($startDate) {
            $query->whereDate('yks_bookings.created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('yks_bookings.created_at', '<=', $endDate);
        }
        if ($packageId) {
            $query->where('yks_bookings.package_id', $packageId);
        }
        if ($statusFilter) {
            $query->where('yks_bookings.reservation_status', $statusFilter);
        }

        $bookings = $query->get();

        $totalBookings = $bookings->count();
        $totalReservationValue = $bookings->sum('total_price');

        $acceptedList = $bookings->where('payment_status', 'Lunas');
        $pendingInspectList = $bookings->where('payment_status', 'Menunggu Verifikasi');
        $rejectedList = $bookings->where('payment_status', 'Ditolak');
        $unpaidList = $bookings->where('payment_status', 'Menunggu Pembayaran');

        $statusCounts = [
            'menunggu_pembayaran' => $bookings->where('reservation_status', 'Menunggu pembayaran')->count(),
            'menunggu_verifikasi' => $bookings->where('reservation_status', 'Menunggu verifikasi')->count(),
            'terkonfirmasi' => $bookings->where('reservation_status', 'Terkonfirmasi')->count(),
            'selesai' => $bookings->where('reservation_status', 'Selesai')->count(),
            'unpaid_count' => $unpaidList->count(),
            'unpaid_sum' => $unpaidList->sum('total_price'),
        ];

        // Rekap per paket sesuai tabel Figma ON GOING Frame 178:20718
        $packageRecap = [];
        $grouped = $bookings->groupBy(fn ($b) => $b->custom_package_name ?: ($b->catalog_package_name ?? 'Jelajah Jeep'));
        foreach ($grouped as $pkgName => $rows) {
            $packageRecap[] = [
                'name' => $pkgName,
                'count' => $rows->count(),
                'total_value' => $rows->sum('total_price'),
                'accepted_value' => $rows->where('payment_status', 'Lunas')->sum('total_price'),
                'pending_value' => $rows->where('payment_status', 'Menunggu Verifikasi')->sum('total_price'),
                'rejected_value' => $rows->where('payment_status', 'Ditolak')->sum('total_price'),
                'unpaid_value' => $rows->where('payment_status', 'Menunggu Pembayaran')->sum('total_price'),
            ];
        }

        $reportSummary = [
            'total_bookings' => $totalBookings,
            'total_value' => $totalReservationValue,
            'accepted_count' => $acceptedList->count(),
            'accepted_sum' => $acceptedList->sum('total_price'),
            'pending_count' => $pendingInspectList->count(),
            'pending_sum' => $pendingInspectList->sum('total_price'),
            'rejected_count' => $rejectedList->count(),
            'rejected_sum' => $rejectedList->sum('total_price'),
            'active_period' => ($startDate && $endDate) ? "{$startDate} s/d {$endDate}" : '1–9 November 2026',
        ];

        return view('admin.reports.index', compact(
            'reportSummary',
            'statusCounts',
            'packages',
            'packageRecap',
            'startDate',
            'endDate',
            'packageId',
            'statusFilter'
        ));
    }

    /**
     * Ekspor CSV
     */
    public function exportCsv()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="laporan_yks_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Kode Booking', 'Nama Pelanggan', 'Paket', 'Tanggal Trip', 'Peserta', 'Total Biaya', 'Status Pembayaran', 'Status Reservasi']);

            $rows = DB::table('yks_bookings')
                ->leftJoin('yks_packages', 'yks_bookings.package_id', '=', 'yks_packages.id')
                ->select('yks_bookings.*', 'yks_packages.name as package_name')
                ->get();

            foreach ($rows as $row) {
                fputcsv($file, [
                    $row->booking_code,
                    $row->customer_name,
                    $row->custom_package_name ?: $row->package_name,
                    $row->trip_date,
                    $row->participant_count,
                    $row->total_price,
                    $row->payment_status,
                    $row->reservation_status,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
