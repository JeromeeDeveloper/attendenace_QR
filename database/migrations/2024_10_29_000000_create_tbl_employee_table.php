<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_tbl_employee_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblEmployeeTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_employee', function (Blueprint $table) {
            $table->increments('tbl_employee_id');
            $table->string('employee_name');
            $table->string('department');
            $table->string('generated_code');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_employee');
    }
}

