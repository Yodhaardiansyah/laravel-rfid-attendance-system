<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Santri;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\PrayerTime;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;

class AttendanceController extends Controller
{
    // Input Absensi Manual (Admin)
    public function inputAbsensiManual(Request $request, $id)
    {
        // Validasi request
        $request->validate([
            'status' => 'required|string|max:255',
            'time' => 'nullable|date_format:Y-m-d H:i:s', // Format fleksibel
        ]);

        // Cari santri berdasarkan ID
        $santri = Santri::findOrFail($id);

        // Gunakan waktu input jika ada, jika tidak gunakan waktu sekarang
        $formattedTime = $request->has('time') 
            ? Carbon::parse($request->time)->format('Y-m-d H:i:s') 
            : now()->format('Y-m-d H:i:s');

        // Simpan data absensi
        Attendance::create([
            'santri_id' => $santri->id,
            'status' => $request->status,
            'time' => $formattedTime,
        ]);

        return redirect()->route('admin.santri.detail', $id)
            ->with('success', 'Absensi berhasil ditambahkan.');
    }

    // Input Absensi dari ESP32/RFID
    public function store(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'time' => 'required|date',
            'prayer_name' => 'required|string',
            'status' => 'required|string'
        ]);

        Attendance::create([
            'santri_id' => $request->santri_id,
            'time' => $request->time,
            'prayer_name' => $request->prayer_name,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Absensi berhasil ditambahkan.');
    }   

    /**
     * Merekam absensi berdasarkan RFID.
     */
    public function recordAttendance(Request $request)
    {
        $request->validate([
            'rfid_number' => 'required|string',
            'time' => 'nullable|date_format:Y-m-d H:i:s'
        ]);
    
        $santri = Santri::where('rfid_number', $request->rfid_number)->first();
        if (!$santri) {
            return response()->json(['message' => 'Santri tidak ditemukan'], 404);
        }
    
        // Ambil semua waktu sholat dan konversi ke Carbon
        $prayerTimes = PrayerTime::orderBy('time')->get()->map(function ($prayer) {
            $prayer->carbon_time = Carbon::parse($prayer->time); // Pastikan data jadi Carbon
            return $prayer;
        });
    
        $absenTime = $request->has('time') 
        ? Carbon::parse($request->time) 
        : now();

        Log::info("Absensi masuk: " . $absenTime->toDateTimeString());
    
        $status = 'Belum Waktunya';
        $selectedPrayer = null;
    
        // Ambil waktu subuh sebagai batas keterlambatan Isya
        $subuh = $prayerTimes->where('name', 'subuh')->first();
        $isya = $prayerTimes->where('name', 'isya')->first();
    
        if ($subuh && $isya) {
            $subuhStart = Carbon::parse($absenTime->toDateString() . ' ' . $subuh->carbon_time->toTimeString());
            $isyaStart = Carbon::parse($absenTime->toDateString() . ' ' . $isya->carbon_time->toTimeString());
            $terlambatIsyaEnd = $subuhStart->copy()->subHour(); // 1 jam sebelum subuh
    
            // **Cek keterlambatan Isya**
            if ($absenTime->between(Carbon::parse($absenTime->toDateString() . ' 00:00:00'), $terlambatIsyaEnd)) {
                $selectedPrayer = 'isya';
                $status = 'Terlambat';
            }
        }
    
        // **Cek waktu absensi untuk sholat lainnya**
        foreach ($prayerTimes as $index => $prayer) {
            $prayerStart = Carbon::parse($absenTime->toDateString() . ' ' . $prayer->carbon_time->toTimeString());
            $prayerEnd = $prayerStart->copy()->addMinutes(10);
            $prayerEarly = $prayerStart->copy()->subMinutes(10);
    
            $nextPrayerStart = isset($prayerTimes[$index + 1]) 
                ? Carbon::parse($absenTime->toDateString() . ' ' . $prayerTimes[$index + 1]->carbon_time->toTimeString()) 
                : null;
    
            $prayerLateLimit = $nextPrayerStart ? $nextPrayerStart->copy()->subHour() : Carbon::parse($absenTime->toDateString() . ' 23:59:59');
    
            Log::info("Cek waktu sholat {$prayer->name}: Start {$prayerStart}, End {$prayerEnd}, Early {$prayerEarly}, LateLimit {$prayerLateLimit}");
    
            // **Maghrib ke Isya**
            if ($prayer->name == 'maghrib' && $absenTime->greaterThan(Carbon::parse($absenTime->toDateString() . ' 18:30:00'))) {
                continue;
            }
    
            if ($absenTime->between($prayerStart->subHour(), $prayerLateLimit)) {
                $selectedPrayer = $prayer->name;
    
                if ($absenTime->between($prayerEarly, $prayerEnd)) {
                    $status = 'Tepat Waktu';
                } elseif ($absenTime->greaterThan($prayerEnd)) {
                    $status = 'Terlambat';
                } else {
                    $status = 'Belum Waktunya';
                }
    
                break;
            }
        }
    
        if (!$selectedPrayer) {
            return response()->json(['message' => 'Waktu absensi tidak valid untuk sholat mana pun'], 422);
        }

        // Cek apakah sudah ada absensi dengan status yang sama untuk sholat yang sama hari ini
        $existingAttendance = Attendance::where('santri_id', $santri->id)
            ->where('prayer_name', $selectedPrayer)
            ->whereDate('time', $absenTime->toDateString())
            ->latest()
            ->first();

        if ($existingAttendance && $existingAttendance->status === $status) {
            return response()->json([
                'message' => 'Absensi sudah tercatat dengan status yang sama',
                'prayer' => ucfirst($selectedPrayer),
                'status' => $status
            ], 200);
        }

        // Simpan jika belum ada atau statusnya berbeda
        Attendance::create([
            'santri_id' => $santri->id,
            'time' => $absenTime->toDateTimeString(),
            'prayer_name' => $selectedPrayer,
            'status' => $status
        ]);
    
        return response()->json([
            'message' => 'Absensi berhasil dicatat',
            'prayer' => ucfirst($selectedPrayer),
            'status' => $status
        ]);
    }
    
    public function listSantri()
    {
        $santriList = Santri::with('latestAttendance')->get();
        return view('admin.santri.index', compact('santriList'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'time' => 'required|date_format:Y-m-d\TH:i',
            'prayer_name' => 'required|string',
            'status' => 'required|string',
        ]);

        $attendance = Attendance::findOrFail($id);
        $attendance->time = $request->time;
        $attendance->prayer_name = $request->prayer_name;
        $attendance->status = $request->status;
        $attendance->save();

        return redirect()->back()->with('success', 'Riwayat absensi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();
    
        return redirect()->back()->with('success', 'Riwayat absensi berhasil dihapus.');
    }
    
    public function manualAttendanceForm()
    {
        $santriList = Santri::all();
        return redirect()->back()->with('success', 'Absensi Manual Berhasil.');
    }
    public function storeManualAttendance(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'status' => 'required|string|max:255',
            'prayer_name' => 'required|string|max:255', // Tambahkan validasi ini
            'time' => 'nullable|date_format:Y-m-d\TH:i',
        ]);

        $formattedTime = $request->has('time')
            ? Carbon::parse($request->time)->format('Y-m-d H:i:s')
            : now()->format('Y-m-d H:i:s');

        Attendance::create([
            'santri_id' => $request->santri_id,
            'status' => $request->status,
            'prayer_name' => $request->prayer_name, // Tambahkan ini
            'time' => $formattedTime,
        ]);

        return redirect()->route('admin.attendance.form')
            ->with('success', 'Absensi berhasil ditambahkan.');
    }

    public function publicAttendance()
    {
        return view('public');
    }

    public function getLatestAttendance()
    {
        $latestAttendance = Attendance::latest()->with('santri')->first();

        if (!$latestAttendance) {
            return response()->json(['message' => 'Belum ada data absensi'], 404);
        }

        return response()->json([
            'santri' => $latestAttendance->santri->name,
            'prayer' => ucfirst($latestAttendance->prayer_name),
            'status' => $latestAttendance->status,
            'time' => $latestAttendance->time
        ]);
    }

    public function export($santri_id, $format)
    {
        $santri = Santri::findOrFail($santri_id);
        $fileName = 'riwayat_absensi_' . $santri->name . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new AttendanceExport($santri_id), $fileName, \Maatwebsite\Excel\Excel::CSV);
        } elseif ($format === 'xlsx') {
            return Excel::download(new AttendanceExport($santri_id), $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        return redirect()->back()->with('error', 'Format tidak didukung.');
    }  
}
