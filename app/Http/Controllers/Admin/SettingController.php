<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    private function getStoragePath(): string
    {
        return storage_path('app/yks_operational_settings.json');
    }

    private function loadSettings(): array
    {
        $path = $this->getStoragePath();
        if (file_exists($path)) {
            $data = json_decode(file_get_contents($path), true);
            if (is_array($data)) {
                return $data;
            }
        }

        // Default sesuai Figma (Senin-Jumat 08.00-17.00, Sabtu 08.00-16.00, Minggu Libur)
        return [
            'pos_location' => 'Pos Jelajah YKS, Kawasan Sentul, Babakan Madang, Bogor',
            'official_instagram' => '@yks.sentul',
            'official_bank' => 'BCA - 0000 0000 00 (a.n. YKS Keliling Sentul)',
            'weekly' => [
                ['day' => 'Senin', 'open' => '08.00', 'close' => '17.00', 'is_holiday' => false],
                ['day' => 'Selasa', 'open' => '08.00', 'close' => '17.00', 'is_holiday' => false],
                ['day' => 'Rabu', 'open' => '08.00', 'close' => '17.00', 'is_holiday' => false],
                ['day' => 'Kamis', 'open' => '08.00', 'close' => '17.00', 'is_holiday' => false],
                ['day' => 'Jumat', 'open' => '08.00', 'close' => '17.00', 'is_holiday' => false],
                ['day' => 'Sabtu', 'open' => '08.00', 'close' => '16.00', 'is_holiday' => false],
                ['day' => 'Minggu', 'open' => '08.00', 'close' => '16.00', 'is_holiday' => true],
            ],
            'exceptions' => [
                [
                    'id' => 1,
                    'date' => '2026-11-25',
                    'service' => 'Libur',
                    'note' => 'Libur internal tim',
                ],
                [
                    'id' => 2,
                    'date' => '2026-11-26',
                    'service' => '09.00–15.00 WIB',
                    'note' => 'Rapat evaluasi',
                ],
            ],
        ];
    }

    private function saveSettings(array $settings): void
    {
        $dir = dirname($this->getStoragePath());
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        file_put_contents($this->getStoragePath(), json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Administrasi / Jam Operasional (Figma Jadwal Mingguan & Pengecualian Tanggal).
     */
    public function index()
    {
        $settings = $this->loadSettings();
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Simpan Jadwal Mingguan & Info Pos
     */
    public function saveWeekly(Request $request)
    {
        $settings = $this->loadSettings();

        $daysInput = $request->input('days', []);
        $weekly = [];
        foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $idx => $dayName) {
            $row = $daysInput[$idx] ?? [];
            $weekly[] = [
                'day' => $dayName,
                'open' => $row['open'] ?? '08.00',
                'close' => $row['close'] ?? '17.00',
                'is_holiday' => !empty($row['is_holiday']),
            ];
        }

        $settings['weekly'] = $weekly;
        if ($request->filled('pos_location')) {
            $settings['pos_location'] = $request->input('pos_location');
        }
        if ($request->filled('official_bank')) {
            $settings['official_bank'] = $request->input('official_bank');
        }

        $this->saveSettings($settings);

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Jadwal operasional mingguan berhasil disimpan!');
    }

    /**
     * Tambah Pengecualian Tanggal
     */
    public function addException(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'service' => 'required|string|max:60',
            'note' => 'required|string|max:190',
        ]);

        $settings = $this->loadSettings();
        $exceptions = $settings['exceptions'] ?? [];

        $maxId = collect($exceptions)->max('id') ?: 0;
        $exceptions[] = [
            'id' => $maxId + 1,
            'date' => $validated['date'],
            'service' => $validated['service'],
            'note' => $validated['note'],
        ];

        $settings['exceptions'] = $exceptions;
        $this->saveSettings($settings);

        return redirect()
            ->route('admin.settings.index')
            ->with('success', "Pengecualian tanggal {$validated['date']} ({$validated['note']}) berhasil ditambahkan.");
    }

    /**
     * Hapus Pengecualian Tanggal
     */
    public function removeException($id)
    {
        $settings = $this->loadSettings();
        $settings['exceptions'] = array_values(array_filter(
            $settings['exceptions'] ?? [],
            fn ($item) => (int) $item['id'] !== (int) $id
        ));
        $this->saveSettings($settings);

        return redirect()
            ->route('admin.settings.index')
            ->with('warning', 'Pengecualian tanggal telah dihapus.');
    }
}
