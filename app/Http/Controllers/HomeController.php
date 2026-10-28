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
        $testimonials = YksTestimonial::all();
        $packages = YksPackage::where('is_active', true)->get();

        return view('gallery', compact('testimonials', 'packages'));
    }

    public function info()
    {
        return view('info');
    }
}

