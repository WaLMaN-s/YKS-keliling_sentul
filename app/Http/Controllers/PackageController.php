<?php

namespace App\Http\Controllers;

use App\Models\YksAddon;
use App\Models\YksCategory;
use App\Models\YksPackage;
use App\Models\YksTerm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
        $addons = Schema::hasTable('yks_addons')
            ? YksAddon::where('is_aktif', true)->get()
            : collect();

        return view('packages.index', compact('packages', 'categories', 'categorySlug', 'addons'));
    }

    public function show($slug)
    {
        $package = YksPackage::with('category')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $addons = Schema::hasTable('yks_addons')
            ? YksAddon::where('is_aktif', true)->get()
            : collect();

        $terms = Schema::hasTable('yks_terms')
            ? YksTerm::all()
            : collect();

        $basecampFacilities = [];
        if (Schema::hasTable('yks_site_contents')) {
            $row = DB::table('yks_site_contents')->where('jenis', 'fasilitas_basecamp')->first();
            if ($row && !empty($row->isi)) {
                $basecampFacilities = array_filter(explode("\n", str_replace("\r", "", $row->isi)));
            }
        }

        return view('packages.show', compact('package', 'addons', 'terms', 'basecampFacilities'));
    }
}
