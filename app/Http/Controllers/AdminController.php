<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\Attendance;
use App\Models\PrayerTime; 
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Exports\SantriExport;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Hanya admin yang bisa mengakses
    }

    // 🏠 Menampilkan Dashboard Admin
    public function dashboard()
    {
        $santris = Santri::with('attendances')->get();
        $prayerTimes = PrayerTime::all(); // Ambil data waktu sholat
        return view('admin.dashboard', compact('prayerTimes', 'santris'));
    }

    // 📌 Menampilkan Detail Santri
    public function showSantri(Request $request, $id)
    {
        $santri = Santri::findOrFail($id);

        $filterType = $request->query('filter_type', 'bulan');
        $filterValue = $request->query('filter_value');

        // Gunakan base query
        $filteredQuery = $santri->attendances();

        // Terapkan filter berdasarkan tipe
        if ($filterType === 'hari' && $filterValue) {
            $filteredQuery->whereDate('time', $filterValue);
        } elseif ($filterType === 'minggu' && $filterValue) {
            try {
                $date = Carbon::parse($filterValue);
                $week = $date->weekOfYear;
                $year = $date->year;
                $filteredQuery->whereYear('time', $year)
                            ->where(\DB::raw('WEEK(time, 1)'), $week);
            } catch (\Exception $e) {
                // Invalid week format
            }
        } elseif ($filterType === 'bulan' && $filterValue) {
            $filteredQuery->whereMonth('time', $filterValue);
        }

        // Ambil clone untuk total count sebelum pagination
        $queryForCount = clone $filteredQuery;

        // Paginate hasil
        $attendances = $filteredQuery->orderBy('time', 'desc')
                                    ->paginate(10)
                                    ->appends([
                                        'filter_type' => $filterType,
                                        'filter_value' => $filterValue,
                                    ]);

        // Hitung total kehadiran dari query asli yang belum di-paginate
        $total_terlambat = (clone $queryForCount)->where('status', 'Terlambat')->count();
        $total_tepat_waktu = (clone $queryForCount)->where('status', 'Tepat Waktu')->count();

        return view('admin.santri-detail', compact(
            'santri',
            'attendances',
            'total_terlambat',
            'total_tepat_waktu'
        ));
    }


    // 🕒 Input Absensi Manual
    public function inputAbsensiManual(Request $request, $santri_id)
    {
        $request->validate([
            'status'     => 'required|string|max:255',
            'absen_at'   => 'required|date_format:Y-m-d\TH:i', // Format datetime dari input HTML5
            'prayer_name'=> 'required|string|max:255', // Added prayer_name validation
        ]);

        $absenDate = Carbon::parse($request->absen_at)->toDateString();

        // Check for duplicate attendance
        $existingAttendance = Attendance::where('santri_id', $santri_id)
            ->where('prayer_name', $request->prayer_name)
            ->where('status', $request->status)
            ->whereDate('time', $absenDate)
            ->first();

        if ($existingAttendance) {
            return redirect()->route('admin.santri.detail', $santri_id)
                ->with('error', 'Data absensi untuk sholat, status, dan tanggal yang sama sudah ada.');
        }

        Attendance::create([
            'santri_id'  => $santri_id,
            'prayer_name'=> $request->prayer_name,
            'status'     => $request->status,
            'time'       => Carbon::parse($request->absen_at),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.santri.detail', $santri_id)
                         ->with('success', 'Absensi berhasil ditambahkan.');
    }

    // ✏️ Menampilkan Form Edit Santri
    public function editSantri($id)
    {
        $santri = Santri::findOrFail($id);
        return view('admin.santri-edit', compact('santri'));
    }

    // 💾 Menyimpan Perubahan Data Santri (Termasuk RFID)
    public function updateSantri(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'year' => 'required|integer',
            'rfid_number' => 'required|string|unique:santris,rfid_number,' . $id, // RFID harus unik
        ]);

        $santri = Santri::findOrFail($id);
        $santri->update([
            'name' => $request->name,
            'year' => $request->year,
            'rfid_number' => $request->rfid_number, // Update RFID
        ]);

        return redirect()->route('admin.santri.detail', $santri->id)
                        ->with('success', 'Data santri berhasil diperbarui.');
    }

    // 🌍 Menampilkan Data Santri untuk Publik
    public function index()
    {
        $santris = Santri::with('attendances')->get();
        return view('public.home', compact('santris'));
    }

    public function showInputAbsensiManual($santri_id)
    {
        $santri = Santri::findOrFail($santri_id);
        return view('admin.attendance-manual', compact('santri'));
    }

    public function getScannedRFID()
    {
        // Cek apakah ada RFID yang baru di-scan dalam 5 detik terakhir
        $rfid = Cache::get('recent_rfid');

        if (!$rfid) {
            return response()->json([
                'success' => false,
                'message' => 'Belum ada data RFID masuk.'
            ]);
        }

        return response()->json([
            'success' => true,
            'rfid_number' => $rfid
        ]);
    }
    public function showInputAbsensiManualAll()
    {
        $santriList = Santri::all(); // Ambil data santri dari database
        return view('admin.input-absensi-manual', compact('santriList'));
    }
    public function exportExcel()
    {
        return Excel::download(new SantriExport, 'santri.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new SantriExport, 'santri.csv');
    }
}
