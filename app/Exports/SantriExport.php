<?php

namespace App\Exports;

use App\Models\Santri;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SantriExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Santri::with('attendances')->get()->map(function ($santri) {
            $latestAttendance = $santri->attendances->sortByDesc('time')->first();
            
            return [
                'ID' => $santri->id,
                'Nama' => $santri->name,
                'Tahun Ajaran' => $santri->year,
                'Status Absen Terakhir' => $latestAttendance ? $latestAttendance->status : 'Belum Absen',
                'Sholat Terakhir' => $latestAttendance ? ucfirst($latestAttendance->prayer_name) : 'Tidak ditemukan',
                'Waktu Absen' => $latestAttendance ? $latestAttendance->time : '-',
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Nama', 'Tahun Ajaran', 'Status Absen Terakhir', 'Sholat Terakhir', 'Waktu Absen'];
    }
}
