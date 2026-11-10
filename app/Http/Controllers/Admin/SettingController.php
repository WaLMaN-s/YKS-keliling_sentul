<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Administrasi / Jam Operasional & Pengaturan Pos (Figma).
     */
    public function index()
    {
        $settings = [
            'pos_location' => 'Pos Jelajah YKS, Kawasan Sentul, Babakan Madang, Bogor',
            'operating_hours' => 'Senin–Minggu, 08.00–17.00 WIB',
            'official_instagram' => '@yks.sentul',
            'official_bank' => 'BCA - 0000 0000 00 (a.n. YKS Keliling Sentul)',
            'slots_per_day' => 12,
        ];

        return view('admin.settings.index', compact('settings'));
    }
}
