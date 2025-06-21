<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrayerTime extends Model
{
    use HasFactory;

    protected $table = 'prayer_times';
    public $timestamps = false; // Pastikan tidak menggunakan timestamps jika tidak ada di tabel

    protected $fillable = ['name', 'time'];
}
