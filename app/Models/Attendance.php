<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'tbl_attendance';
    protected $primaryKey = 'tbl_attendance_id';
    public $timestamps = false; // Disable timestamps

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'tbl_employee_id');
    }

    protected $fillable = [
        'tbl_employee_id',
        'time_in',
        'time_out',
    ];
}


