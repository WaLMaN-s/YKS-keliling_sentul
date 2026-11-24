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
     * Administrasi / Paket & Layanan (Figma ON GOING Frame 249:2).
     */
    public function index(Request $request)
    {
        $search = $request->query('q');
        $statusFilter = $request->query('status');
        $editId = $request->query('edit');
        $mode = $request->query('mode', $editId ? 'edit' : 'create');

        $allPackages = YksPackage::with('category')->orderBy('id', 'asc')->get();

        $packages = $allPackages;
        if ($search) {
            $packages = $packages->filter(function ($pkg) use ($search) {
                return stripos($pkg->name, $search) !== false
                    || stripos($pkg->description, $search) !== false;
            });
        }
        if ($statusFilter === 'aktif') {
            $packages = $packages->where('is_active', true);
        } elseif ($statusFilter === 'nonaktif') {
            $packages = $packages->where('is_active', false);
        }

        $categories = YksCategory::orderBy('id', 'asc')->get();

        $totalCount = $allPackages->count();
        $activeCount = $allPackages->where('is_active', true)->count();
        $inactiveCount = $totalCount - $activeCount;
        $photoReadyCount = $allPackages->filter(fn ($p) => !empty($p->image))->count();
        $priceReadyCount = $allPackages->filter(fn ($p) => $p->price > 0 || $p->booking_mode === 'whatsapp_consultation')->count();
        $lastUpdated = $allPackages->max('updated_at');

        $selectedPackage = null;
        if ($mode === 'edit' && $editId) {
            $selectedPackage = $allPackages->firstWhere('id', (int) $editId);
        }

        return view('admin.packages.index', compact(
            'packages',
            'allPackages',
            'categories',
            'selectedPackage',
            'mode',
            'search',
            'statusFilter',
            'totalCount',
            'activeCount',
            'inactiveCount',
            'photoReadyCount',
            'priceReadyCount',
            'lastUpdated'
        ));
    }

    /**
     * Tambah Paket Baru / Private Trip
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'nullable|exists:yks_categories,id',
            'booking_mode' => 'nullable|in:direct,whatsapp_consultation',
            'consultation_target' => 'nullable|string|max:150',
            'description' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'price_unit' => 'nullable|string|max:60',
            'duration' => 'nullable|string|max:60',
            'capacity_label' => 'nullable|string|max:120',
            'distance' => 'nullable|string|max:60',
            'difficulty' => 'nullable|in:Mudah,Sedang,Menantang',
            'meeting_point' => 'nullable|string|max:190',
            'includes' => 'nullable|string',
            'excludes' => 'nullable|string',
            'itinerary' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'image_url' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        $category = !empty($validated['category_id']) ? YksCategory::find($validated['category_id']) : null;
        $isPrivateTrip = ($validated['booking_mode'] ?? '') === 'whatsapp_consultation'
            || ($category && stripos($category->name, 'Private') !== false)
            || stripos($validated['name'], 'Private Trip') !== false;

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        while (YksPackage::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $imagePath = $validated['image_url'] ?? 'https://images.unsplash.com/photo-1544644181-1484b3fdfc62?auto=format&fit=crop&w=800&q=80';
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $uploadDir = public_path('uploads/packages');
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0777, true);
            }
            @chmod($uploadDir, 0777);
            file_put_contents($uploadDir . DIRECTORY_SEPARATOR . $filename, file_get_contents($file->getRealPath()));
            $imagePath = '/uploads/packages/' . $filename;
        }

        $package = YksPackage::create([
            'category_id' => $validated['category_id'] ?: null,
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'],
            'price' => $isPrivateTrip ? (int) ($validated['price'] ?? 0) : (int) ($validated['price'] ?? 700000),
            'price_unit' => $validated['price_unit'] ?: ($isPrivateTrip ? 'konsultasi admin' : 'per orang'),
            'booking_mode' => $isPrivateTrip ? 'whatsapp_consultation' : ($validated['booking_mode'] ?? 'direct'),
            'consultation_target' => $validated['consultation_target'] ?: 'WhatsApp admin utama',
            'duration' => $validated['duration'] ?: ($isPrivateTrip ? 'Sesuai konsultasi' : '4–6 jam'),
            'capacity_label' => $validated['capacity_label'] ?: ($isPrivateTrip ? 'Harga & tanggal via WhatsApp · Tanpa booking langsung' : 'per orang · 4–6 jam · min. 2 orang'),
            'distance' => $validated['distance'] ?: '4.5 Km PP',
            'difficulty' => $validated['difficulty'] ?: 'Sedang',
            'meeting_point' => $validated['meeting_point'] ?: 'Pos Jelajah YKS, Sentul, Babakan Madang, Bogor',
            'includes' => $validated['includes'] ?: "Kendaraan & pengemudi\nPemandu perjalanan\nPerlengkapan keselamatan\nAir mineral",
            'excludes' => $validated['excludes'] ?: "Harga, tanggal, rute, dan kebutuhan rombongan dikonsultasikan melalui WhatsApp admin.",
            'itinerary' => $validated['itinerary'] ?: "07.30 - Berkumpul di Meeting Point & Pengarahan\n08.00 - Mulai perjalanan jelajah Sentul\n12.00 - Kembali ke titik kumpul & selesai",
            'image' => $imagePath,
            'is_active' => $request->boolean('is_active', false),
        ]);

        return redirect()
            ->route('admin.packages.index', ['edit' => $package->id, 'mode' => 'edit'])
            ->with('success', "Paket \"{$package->name}\" berhasil disimpan ke katalog!");
    }

    /**
     * Perbarui Data Paket
     */
    public function update(Request $request, $id)
    {
        $package = YksPackage::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'nullable|exists:yks_categories,id',
            'booking_mode' => 'nullable|in:direct,whatsapp_consultation',
            'consultation_target' => 'nullable|string|max:150',
            'description' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'price_unit' => 'nullable|string|max:60',
            'duration' => 'nullable|string|max:60',
            'capacity_label' => 'nullable|string|max:120',
            'distance' => 'nullable|string|max:60',
            'difficulty' => 'nullable|in:Mudah,Sedang,Menantang',
            'meeting_point' => 'nullable|string|max:190',
            'includes' => 'nullable|string',
            'excludes' => 'nullable|string',
            'itinerary' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'image_url' => 'nullable|string|max:500',
        ]);

        $category = !empty($validated['category_id']) ? YksCategory::find($validated['category_id']) : null;
        $isPrivateTrip = ($validated['booking_mode'] ?? $package->booking_mode) === 'whatsapp_consultation'
            || ($category && stripos($category->name, 'Private') !== false);

        $slug = $package->slug;
        if ($package->name !== $validated['name']) {
            $baseSlug = Str::slug($validated['name']);
            $slug = $baseSlug;
            $counter = 1;
            while (YksPackage::where('slug', $slug)->where('id', '!=', $package->id)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }
        }

        $imagePath = $package->image;
        if ($request->filled('image_url') && $request->input('image_url') !== $package->image) {
            $imagePath = $validated['image_url'];
        }
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $uploadDir = public_path('uploads/packages');
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0777, true);
            }
            @chmod($uploadDir, 0777);
            file_put_contents($uploadDir . DIRECTORY_SEPARATOR . $filename, file_get_contents($file->getRealPath()));
            $imagePath = '/uploads/packages/' . $filename;
        }

        $package->update([
            'category_id' => $validated['category_id'] ?: null,
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'],
            'price' => $isPrivateTrip ? (int) ($validated['price'] ?? 0) : (int) ($validated['price'] ?? $package->price),
            'price_unit' => $validated['price_unit'] ?: $package->price_unit,
            'booking_mode' => $isPrivateTrip ? 'whatsapp_consultation' : ($validated['booking_mode'] ?? 'direct'),
            'consultation_target' => $validated['consultation_target'] ?: $package->consultation_target,
            'duration' => $validated['duration'] ?: $package->duration,
            'capacity_label' => $validated['capacity_label'] ?: $package->capacity_label,
            'distance' => $validated['distance'] ?: $package->distance,
            'difficulty' => $validated['difficulty'] ?: $package->difficulty,
            'meeting_point' => $validated['meeting_point'] ?: $package->meeting_point,
            'includes' => $validated['includes'] ?? $package->includes,
            'excludes' => $validated['excludes'] ?? $package->excludes,
            'itinerary' => $validated['itinerary'] ?? $package->itinerary,
            'image' => $imagePath,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : false,
        ]);

        return redirect()
            ->route('admin.packages.index', ['edit' => $package->id, 'mode' => 'edit'])
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
