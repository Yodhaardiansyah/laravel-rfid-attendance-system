<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['santri_id', 'status', 'time', 'created_at', 'updated_at', 'prayer_name'];

    protected $dates = ['time', 'created_at', 'updated_at'];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}
