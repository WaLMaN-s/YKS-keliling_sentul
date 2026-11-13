<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Administrasi / Data Pelanggan (Figma 3 KPI + Daftar Pelanggan + Panel Profil Pelanggan).
     */
    public function index(Request $request)
    {
        $search = $request->query('q');
        $filter = $request->query('filter'); // 'all' or 'repeat'
        $selectedEmail = $request->query('select');

        $allCustomers = DB::table('yks_bookings')
            ->select(
                DB::raw('MAX(customer_name) as customer_name'),
                'customer_phone',
                'customer_email',
                DB::raw('count(*) as total_trip'),
                DB::raw('sum(total_price) as total_spent'),
                DB::raw('min(created_at) as first_joined')
            )
            ->groupBy('customer_email', 'customer_phone')
            ->orderByDesc('total_spent')
            ->get();

        // Hitung KPI
        $totalRegistered = $allCustomers->count();
        $repeatCustomers = $allCustomers->where('total_trip', '>', 1)->count();
        $newThisMonth = $allCustomers->count();

        // Filter & Pencarian
        $filtered = $allCustomers;

        if ($search) {
            $filtered = $filtered->filter(function ($c) use ($search) {
                return stripos($c->customer_name ?? '', $search) !== false
                    || stripos($c->customer_email ?? '', $search) !== false
                    || stripos($c->customer_phone ?? '', $search) !== false;
            });
        }

        if ($filter === 'repeat') {
            $filtered = $filtered->filter(fn ($c) => (int) $c->total_trip > 1);
        }

        $perPage = 10;
        $page = Paginator::resolveCurrentPage() ?: 1;
        $total = $filtered->count();
        $items = $filtered->slice(($page - 1) * $perPage, $perPage)->values();

        $customers = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => Paginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        // Pilih pelanggan untuk Panel Profil Kanan
        $selectedCustomer = null;
        if ($selectedEmail) {
            $selectedCustomer = $allCustomers->firstWhere('customer_email', $selectedEmail);
        }
        if (!$selectedCustomer && $customers->count() > 0) {
            $selectedCustomer = $customers->first();
        }

        $customerHistory = collect();
        if ($selectedCustomer && !empty($selectedCustomer->customer_email)) {
            $customerHistory = DB::table('yks_bookings')
                ->join('yks_packages', 'yks_bookings.package_id', '=', 'yks_packages.id')
                ->select('yks_bookings.*', 'yks_packages.name as package_name')
                ->where('customer_email', $selectedCustomer->customer_email)
                ->orderByDesc('yks_bookings.trip_date')
                ->get();
        }

        return view('admin.customers.index', compact(
            'customers',
            'selectedCustomer',
            'customerHistory',
            'search',
            'filter',
            'totalRegistered',
            'repeatCustomers',
            'newThisMonth'
        ));
    }

    /**
     * Unduh Data Pelanggan (CSV)
     */
    public function exportCsv()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="data_pelanggan_yks_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID Pelanggan', 'Nama Pelanggan', 'Email', 'Kontak WhatsApp', 'Total Pesanan', 'Total Transaksi']);

            $rows = DB::table('yks_bookings')
                ->select(
                    DB::raw('MAX(customer_name) as customer_name'),
                    'customer_phone',
                    'customer_email',
                    DB::raw('count(*) as total_trip'),
                    DB::raw('sum(total_price) as total_spent')
                )
                ->groupBy('customer_email', 'customer_phone')
                ->orderByDesc('total_spent')
                ->get();

            foreach ($rows as $idx => $row) {
                fputcsv($file, [
                    'PLG-' . str_pad((string) ($idx + 1), 3, '0', STR_PAD_LEFT),
                    $row->customer_name,
                    $row->customer_email,
                    $row->customer_phone,
                    $row->total_trip . ' pesanan',
                    $row->total_spent,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
