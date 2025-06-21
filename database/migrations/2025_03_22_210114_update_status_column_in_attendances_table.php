<?php

// 2025_03_22_000003_update_status_column_in_attendances_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusColumnInAttendancesTable extends Migration
{
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->text('status')->change();
        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('status', 10)->change();  // Mengembalikan ke string
        });
    }
}
