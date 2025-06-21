<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendanceExport implements FromCollection, WithHeadings
{
    protected $santri_id;

    public function __construct($santri_id)
    {
        $this->santri_id = $santri_id;
    }

    public function collection()
    {
        return Attendance::where('santri_id', $this->santri_id)
            ->orderByDesc('time')
            ->get()
            ->map(function ($attendance) {
                return [
                    'Waktu' => $attendance->time,
                    'Nama Sholat' => ucfirst($attendance->prayer_name),
                    'Status' => $attendance->status,
                ];
            });
    }

    public function headings(): array
    {
        return ['Waktu', 'Nama Sholat', 'Status'];
    }
}
