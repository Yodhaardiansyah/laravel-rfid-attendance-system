<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('prayer_times', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Nama sholat: subuh, dzuhur, ashar, maghrib, isya
            $table->time('time'); // Jam batas waktu sholat
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prayer_times');
    }
};
