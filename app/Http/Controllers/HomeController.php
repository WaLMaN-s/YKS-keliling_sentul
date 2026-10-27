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
}
