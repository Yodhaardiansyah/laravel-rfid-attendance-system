<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Santri extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'rfid_number', 'year'];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Ambil absensi terakhir berdasarkan waktu absensi terbaru
    public function latestAttendance(): HasOne
    {
        return $this->hasOne(Attendance::class)->latestOfMany('time');
    }
}

