<?php

namespace App\Http\Controllers;

use App\Models\YksCategory;
use App\Models\YksPackage;
use App\Models\YksTestimonial;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function about()
    {
        return view('about');
    }

    public function gallery(Request $request)
    {
        $ratingFilter = $request->query('penilaian');
        $query = YksTestimonial::query();

        if ($ratingFilter && is_numeric($ratingFilter)) {
            $query->where('rating', (int) $ratingFilter)->orderByDesc('id');
        } elseif ($ratingFilter === 'terendah') {
            $query->orderBy('rating', 'asc')->orderByDesc('id');
        } elseif ($ratingFilter === 'tertinggi') {
            $query->orderBy('rating', 'desc')->orderByDesc('id');
        } else {
            $query->orderByDesc('id');
        }

        $testimonials = $query->get();
        $packages = YksPackage::where('is_active', true)->orderBy('id', 'asc')->get();

        return view('gallery', compact('testimonials', 'packages', 'ratingFilter'));
    }

    /**
     * Halaman Tulis Ulasan (Figma Yuk Keliling Sentul ON GOING #165:303)
     */
    public function createReview()
    {
        $packages = YksPackage::where('is_active', true)->orderBy('id', 'asc')->get();
        return view('reviews.create', compact('packages'));
    }

    /**
     * Simpan Ulasan Pelanggan (Figma Yuk Keliling Sentul ON GOING #165:303)
     */
    public function storeReview(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'package_name' => 'required|string|max:150',
            'trip_date' => 'required|date',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:2000',
            'consent' => 'accepted',
        ], [
            'name.required' => 'Nama penulis wajib diisi.',
            'package_name.required' => 'Pilih paket perjalanan yang kamu ikuti.',
            'trip_date.required' => 'Tanggal perjalanan wajib diisi.',
            'rating.required' => 'Pilih penilaian bintang (1–5 bintang).',
            'review.required' => 'Ceritakan pengalaman perjalananmu.',
            'consent.accepted' => 'Kamu perlu menyetujui publikasi ulasan sebelum mengirim.',
        ]);

        $formattedMonth = Carbon::parse($validated['trip_date'])->translatedFormat('F Y');

        YksTestimonial::create([
            'name' => $validated['name'],
            'city' => $formattedMonth,
            'package_name' => $validated['package_name'],
            'trip_date' => $validated['trip_date'],
            'rating' => (int) $validated['rating'],
            'review' => $validated['review'],
            'is_approved' => true,
        ]);

        return redirect()
            ->route('gallery')
            ->with('success', 'Terima kasih! Ulasan perjalananmu telah berhasil dikirim dan ditampilkan.');
    }

    public function info()
    {
        return view('info');
    }
}
