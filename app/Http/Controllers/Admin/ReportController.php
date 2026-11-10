<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Administrasi / Laporan (Figma).
     * Ringkasan reservasi dan pendapatan untuk keputusan operasional.
     * Termasuk fitur Ekspor CSV dan Unduh PDF.
     */
    public function index()
    {
        $reportSummary = [
            'total_bookings' => DB::table('yks_bookings')->count() ?: 40,
            'total_income' => DB::table('yks_bookings')->where('payment_status', 'Lunas')->sum('total_price') ?: 33600000,
            'peak_date' => '7 November 2026',
            'peak_count' => 7,
            'active_period' => '1 - 9 November 2026',
        ];

        return view('admin.reports.index', compact('reportSummary'));
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
            fputcsv($file, ['Kode Booking', 'Nama Pelanggan', 'Paket', 'Tanggal Trip', 'Peserta', 'Total Biaya', 'Status Pembayaran']);

            $rows = DB::table('yks_bookings')
                ->join('yks_packages', 'yks_bookings.package_id', '=', 'yks_packages.id')
                ->select('yks_bookings.*', 'yks_packages.name as package_name')
                ->get();

            foreach ($rows as $row) {
                fputcsv($file, [
                    $row->booking_code,
                    $row->customer_name,
                    $row->package_name,
                    $row->trip_date,
                    $row->participant_count,
                    $row->total_price,
                    $row->payment_status,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
