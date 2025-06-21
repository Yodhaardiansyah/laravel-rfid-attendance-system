<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceTable extends Migration
{
// 2025_03_22_000002_create_attendance_table.php

public function up()
{
    Schema::create('attendances', function (Blueprint $table) {
        $table->id();
        $table->foreignId('santri_id')->constrained()->onDelete('cascade');
        $table->timestamp('time');
        $table->text('status');  // Mengubah status menjadi text
        $table->timestamps();
    });
}

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
