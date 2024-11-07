<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_tbl_attendance_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblAttendanceTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_attendance', function (Blueprint $table) {
            $table->increments('tbl_attendance_id');
            $table->unsignedInteger('tbl_employee_id');
            $table->timestamp('time_in')->useCurrent()->nullable(false);
            $table->timestamp('time_out')->nullable();
            $table->timestamps(); // This will add created_at and updated_at columns
            $table->foreign('tbl_employee_id')->references('tbl_employee_id')->on('tbl_employee')->onDelete('cascade');
        });
    }
    

    public function down()
    {
        Schema::dropIfExists('tbl_attendance');
    }
}
