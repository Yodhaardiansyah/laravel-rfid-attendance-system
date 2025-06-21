<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Santri;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SantriExport;

class SantriController extends Controller
{
    public function index()
    {
        $santris = Santri::latest()->get();
        return view('admin.santri.index', compact('santris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'rfid_number' => 'required|string|unique:santris,rfid_number',
            'year' => 'required|integer|min:2000|max:' . date('Y')
        ]);

        Santri::create([
            'name' => $request->name,
            'rfid_number' => $request->rfid_number,
            'year' => $request->year
        ]);

        return response()->json(['message' => 'Santri berhasil ditambahkan']);
    }

    public function destroy($id)
    {
        $santri = Santri::findOrFail($id);
        $santri->delete();

        return response()->json(['message' => 'Santri berhasil dihapus']);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'year' => 'required|integer',
        ]);

        $santri = Santri::findOrFail($id);
        $santri->update([
            'name' => $request->name,
            'year' => $request->year,
        ]);

        return response()->json(['success' => true]);
    }

    
    public function export($format)
    {
        $fileName = 'daftar_santri.' . $format;
        
        if ($format === 'csv') {
            return Excel::download(new SantriExport, $fileName, \Maatwebsite\Excel\Excel::CSV);
        } elseif ($format === 'xlsx') {
            return Excel::download(new SantriExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        return redirect()->back()->with('error', 'Format tidak didukung.');
    }

    
}