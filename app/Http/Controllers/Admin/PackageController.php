<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\YksCategory;
use App\Models\YksPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    /**
     * Administrasi / Kelola Paket (Figma).
     * Menampilkan katalog paket, ringkasan publikasi, serta panel tambah/ubah paket.
     */
    public function index(Request $request)
    {
        $search = $request->query('q');
        $editId = $request->query('edit');
        $mode = $request->query('mode', 'edit'); // 'edit' or 'create'

        $allPackages = YksPackage::with('category')->orderBy('id', 'asc')->get();

        $packages = $allPackages;
        if ($search) {
            $packages = $allPackages->filter(function ($pkg) use ($search) {
                return stripos($pkg->name, $search) !== false
                    || stripos($pkg->description, $search) !== false;
            });
        }

        $categories = YksCategory::orderBy('id', 'asc')->get();

        // Statistik Katalog & Ringkasan Publikasi sesuai Figma
        $totalCount = $allPackages->count();
        $activeCount = $allPackages->where('is_active', true)->count();
        $inactiveCount = $totalCount - $activeCount;
        $photoReadyCount = $allPackages->filter(fn ($p) => !empty($p->image))->count();
        $priceReadyCount = $allPackages->filter(fn ($p) => $p->price > 0)->count();
        $lastUpdated = $allPackages->max('updated_at');

        // Tentukan paket yang sedang diedit di panel kanan
        $selectedPackage = null;
        if ($mode !== 'create') {
            if ($editId) {
                $selectedPackage = $allPackages->firstWhere('id', (int) $editId);
            }
            if (!$selectedPackage && $allPackages->isNotEmpty()) {
                $selectedPackage = $allPackages->first();
            }
        }

        return view('admin.packages.index', compact(
            'packages',
            'allPackages',
            'categories',
            'selectedPackage',
            'mode',
            'search',
            'totalCount',
            'activeCount',
            'inactiveCount',
            'photoReadyCount',
            'priceReadyCount',
            'lastUpdated'
        ));
    }

    /**
     * Tambah Paket Baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'nullable|exists:yks_categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|string|max:60',
            'distance' => 'nullable|string|max:60',
            'difficulty' => 'required|in:Mudah,Sedang,Menantang',
            'meeting_point' => 'nullable|string|max:190',
            'includes' => 'nullable|string',
            'excludes' => 'nullable|string',
            'itinerary' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'image_url' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        // Generate unique slug
        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        while (YksPackage::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        // Handle Foto Paket (Upload File atau URL)
        $imagePath = $validated['image_url'] ?? 'https://images.unsplash.com/photo-1544644181-1484b3fdfc62?auto=format&fit=crop&w=800&q=80';
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $uploadDir = public_path('uploads/packages');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }
            $file->move($uploadDir, $filename);
            $imagePath = '/uploads/packages/' . $filename;
        }

        $package = YksPackage::create([
            'category_id' => $validated['category_id'] ?: null,
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'],
            'price' => (int) $validated['price'],
            'duration' => $validated['duration'],
            'distance' => $validated['distance'] ?: '4.5 Km PP',
            'difficulty' => $validated['difficulty'],
            'meeting_point' => $validated['meeting_point'] ?: 'Pos Jelajah YKS, Sentul, Babakan Madang, Bogor',
            'includes' => $validated['includes'] ?: "Pemandu Lokal\nAir Mineral\nP3K Standar\nDokumentasi",
            'excludes' => $validated['excludes'] ?: "Transportasi ke Meeting Point\nPengeluaran Pribadi",
            'itinerary' => $validated['itinerary'] ?: "07.30 - Berkumpul di Meeting Point & Pengarahan\n08.00 - Mulai perjalanan jelajah Sentul\n12.00 - Kembali ke titik kumpul & selesai",
            'image' => $imagePath,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.packages.index', ['edit' => $package->id])
            ->with('success', "Paket baru \"{$package->name}\" berhasil ditambahkan ke katalog!");
    }

    /**
     * Perbarui Data Paket (Edit Paket, Upload Foto Baru, Ubah Status Aktif/Nonaktif)
     */
    public function update(Request $request, $id)
    {
        $package = YksPackage::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'nullable|exists:yks_categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|string|max:60',
            'distance' => 'nullable|string|max:60',
            'difficulty' => 'required|in:Mudah,Sedang,Menantang',
            'meeting_point' => 'nullable|string|max:190',
            'includes' => 'nullable|string',
            'excludes' => 'nullable|string',
            'itinerary' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'image_url' => 'nullable|string|max:500',
        ]);

        // Update slug jika nama berubah
        $slug = $package->slug;
        if ($package->name !== $validated['name']) {
            $baseSlug = Str::slug($validated['name']);
            $slug = $baseSlug;
            $counter = 1;
            while (YksPackage::where('slug', $slug)->where('id', '!=', $package->id)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }
        }

        // Handle Foto Paket
        $imagePath = $package->image;
        if ($request->filled('image_url') && $request->input('image_url') !== $package->image) {
            $imagePath = $validated['image_url'];
        }
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $uploadDir = public_path('uploads/packages');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }
            $file->move($uploadDir, $filename);
            $imagePath = '/uploads/packages/' . $filename;
        }

        $package->update([
            'category_id' => $validated['category_id'] ?: null,
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'],
            'price' => (int) $validated['price'],
            'duration' => $validated['duration'],
            'distance' => $validated['distance'] ?: $package->distance,
            'difficulty' => $validated['difficulty'],
            'meeting_point' => $validated['meeting_point'] ?: $package->meeting_point,
            'includes' => $validated['includes'] ?? $package->includes,
            'excludes' => $validated['excludes'] ?? $package->excludes,
            'itinerary' => $validated['itinerary'] ?? $package->itinerary,
            'image' => $imagePath,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : false,
        ]);

        return redirect()
            ->route('admin.packages.index', ['edit' => $package->id])
            ->with('success', "Perubahan pada paket \"{$package->name}\" berhasil disimpan!");
    }

    /**
     * Toggle Cepat Status Aktif / Nonaktif Paket
     */
    public function toggleStatus($id)
    {
        $package = YksPackage::findOrFail($id);
        $package->is_active = !$package->is_active;
        $package->save();

        $statusLabel = $package->is_active ? 'Aktif (Tayang di Katalog)' : 'Nonaktif (Disembunyikan dari Katalog)';

        return redirect()
            ->back()
            ->with('success', "Status paket \"{$package->name}\" diubah menjadi: {$statusLabel}.");
    }

    /**
     * Hapus Paket dari Katalog
     */
    public function destroy($id)
    {
        $package = YksPackage::findOrFail($id);
        $name = $package->name;
        $package->delete();

        return redirect()
            ->route('admin.packages.index')
            ->with('warning', "Paket \"{$name}\" telah dihapus dari katalog.");
    }
}
