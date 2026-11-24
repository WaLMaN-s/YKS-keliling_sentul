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
        $search = trim((string) $request->query('cari', ''));
        $categories = YksCategory::orderBy('id', 'asc')->get();

        $query = YksPackage::with('category')->where('is_active', true)->orderBy('id', 'asc');

        if ($categorySlug && $categorySlug !== 'all') {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('includes', 'like', "%{$search}%");
            });
        }

        $packages = $query->get();

        return view('packages.index', compact('packages', 'categories', 'categorySlug', 'search'));
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
