<?php

namespace App\Http\Controllers;

use App\Models\YksCategory;
use App\Models\YksPackage;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $categorySlug = $request->query('kategori');
        $categories = YksCategory::all();

        $query = YksPackage::with('category')->where('is_active', true);

        if ($categorySlug && $categorySlug !== 'all') {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        $packages = $query->get();

        return view('packages.index', compact('packages', 'categories', 'categorySlug'));
    }

    public function show($slug)
    {
        $package = YksPackage::with('category')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('packages.show', compact('package'));
    }
}
