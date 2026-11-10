<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
                'customer_name',
                'customer_phone',
                'customer_email',
                DB::raw('count(*) as total_trip'),
                DB::raw('sum(total_price) as total_spent'),
                DB::raw('min(created_at) as first_joined')
            )
            ->groupBy('customer_name', 'customer_phone', 'customer_email')
            ->orderByDesc('total_spent')
            ->get();

        // Hitung KPI
        $totalRegistered = $allCustomers->count();
        $repeatCustomers = $allCustomers->where('total_trip', '>', 1)->count();
        $newThisMonth = $allCustomers->count();

        // Filter & Pencarian
        $query = DB::table('yks_bookings')
            ->select(
                'customer_name',
                'customer_phone',
                'customer_email',
                DB::raw('count(*) as total_trip'),
                DB::raw('sum(total_price) as total_spent'),
                DB::raw('min(created_at) as first_joined')
            )
            ->groupBy('customer_name', 'customer_phone', 'customer_email');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        if ($filter === 'repeat') {
            $query->havingRaw('count(*) > 1');
        }

        $customers = $query->orderByDesc('total_spent')->paginate(10)->withQueryString();

        // Pilih pelanggan untuk Panel Profil Kanan
        $selectedCustomer = null;
        if ($selectedEmail) {
            $selectedCustomer = $allCustomers->firstWhere('customer_email', $selectedEmail);
        }
        if (!$selectedCustomer && $customers->count() > 0) {
            $selectedCustomer = $customers->first();
        }

        $customerHistory = collect();
        if ($selectedCustomer) {
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
                    'customer_name',
                    'customer_phone',
                    'customer_email',
                    DB::raw('count(*) as total_trip'),
                    DB::raw('sum(total_price) as total_spent')
                )
                ->groupBy('customer_name', 'customer_phone', 'customer_email')
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
