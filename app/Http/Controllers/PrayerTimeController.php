<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PrayerTime;
use Illuminate\Support\Facades\Log;

class PrayerTimeController extends Controller
{
    /**
     * Konstruktor untuk memastikan hanya admin yang bisa mengakses.
     */
    public function __construct()
    {
        $this->middleware('auth'); // Pastikan user login sebelum mengakses
    }

    /**
     * Menampilkan halaman edit waktu sholat.
     */
    public function edit()
    {
        $prayerTimes = PrayerTime::orderBy('id')->get(); // Urutkan berdasarkan ID
        return view('admin.prayer-edit', compact('prayerTimes'));
    }

    /**
     * Memproses pembaruan waktu sholat.
     */
    public function update(Request $request)
    {
        Log::info('Request masuk ke update:', $request->all());

        $request->validate([
            'times' => 'required|array',
            'times.*' => 'required|date_format:H:i',
        ]);

        foreach ($request->times as $name => $time) {
            Log::info("Updating prayer time: $name to $time");

            PrayerTime::updateOrInsert(
                ['name' => $name],
                ['time' => $time . ':00', 'updated_at' => now()]
            );
        }

        return redirect()->route('admin.prayer.edit')
        ->with('success', 'Waktu sholat berhasil diperbarui!');
    }
}
