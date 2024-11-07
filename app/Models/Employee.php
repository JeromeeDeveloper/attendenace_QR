<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'tbl_employee';
    protected $primaryKey = 'tbl_employee_id';

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'tbl_employee_id');
    }

    protected $fillable = ['employee_name', 'department', 'generated_code'];
}

