<?php

namespace App\Http\Controllers;

use App\Models\YksCategory;
use App\Models\YksPackage;
use App\Models\YksTestimonial;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = YksCategory::all();
        $packages = YksPackage::with('category')->where('is_active', true)->get();
        $testimonials = YksTestimonial::latest()->take(3)->get();

        return view('home', compact('categories', 'packages', 'testimonials'));
    }

    public function about()
    {
        return view('about');
    }

    public function gallery()
    {
        $testimonials = YksTestimonial::latest()->get();
        $packages = YksPackage::where('is_active', true)->get();

        return view('gallery', compact('testimonials', 'packages'));
    }

    /**
     * UI-06: Halaman Tulis Ulasan Pelanggan (SDD Tabel 4)
     */
    public function createReview()
    {
        $packages = YksPackage::where('is_active', true)->get();
        return view('reviews.create', compact('packages'));
    }

    public function storeReview(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'city' => 'nullable|string|max:80',
            'package_name' => 'required|string|max:150',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:2000',
            'agree' => 'accepted',
        ], [
            'name.required' => 'Nama penulis wajib diisi.',
            'package_name.required' => 'Silakan pilih paket perjalanan yang Anda ikuti.',
            'rating.required' => 'Silakan pilih penilaian 1 hingga 5 bintang.',
            'review.required' => 'Ceritakan pengalaman perjalanan Anda.',
            'agree.accepted' => 'Mohon centang persetujuan tampilan ulasan.',
        ]);

        YksTestimonial::create([
            'name' => $validated['name'],
            'city' => $validated['city'] ?: 'Jabodetabek',
            'package_name' => $validated['package_name'],
            'rating' => (int) $validated['rating'],
            'review' => $validated['review'],
            'is_tampil' => true,
        ]);

        return redirect()->route('gallery')
            ->with('success', 'Terima kasih! Ulasan perjalanan Anda berhasil dikirim dan ditampilkan.');
    }

    public function info()
    {
        return view('info');
    }
}

