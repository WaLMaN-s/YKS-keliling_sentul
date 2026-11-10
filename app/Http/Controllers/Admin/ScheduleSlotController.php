<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleSlotController extends Controller
{
    /**
     * Administrasi / Jadwal & Slot (Figma).
     */
    public function index()
    {
        // 12 slot harian · 10 terisi · 2 tersedia (Figma specification)
        $dailyQuota = 12;
        $bookedSlots = 10;
        $availableSlots = 2;

        return view('admin.schedules.index', compact('dailyQuota', 'bookedSlots', 'availableSlots'));
    }
}
