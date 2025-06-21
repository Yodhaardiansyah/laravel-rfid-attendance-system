<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSantriTable extends Migration
{
    public function up()
    {
        Schema::create('santris', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('rfid_number')->unique();
            $table->string('year');  // Tahun ajaran
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('santris');
    }
}
