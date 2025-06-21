<?php

// app/Http/Controllers/SearchController.php

namespace App\Http\Controllers;

use App\Models\Santri; // Import model Santri
use Illuminate\Http\Request;

class SearchController extends Controller
{
    // Fungsi pencarian untuk santri di admin
    public function search(Request $request)
    {
        $query = $request->input('query'); // Ambil kata kunci pencarian
        $santris = Santri::where('name', 'like', "%{$query}%")
            ->orWhere('rfid_number', 'like', "%{$query}%")
            ->get();

        // Kembalikan hasil pencarian ke tampilan admin
        return view('admin.dashboard', compact('santris'));
    }
}
