<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Administrasi / Data Pelanggan (Figma).
     */
    public function index()
    {
        $customers = DB::table('yks_bookings')
            ->select('customer_name', 'customer_phone', 'customer_email', DB::raw('count(*) as total_trip'), DB::raw('sum(total_price) as total_spent'))
            ->groupBy('customer_name', 'customer_phone', 'customer_email')
            ->orderByDesc('total_spent')
            ->paginate(15);

        return view('admin.customers.index', compact('customers'));
    }
}
