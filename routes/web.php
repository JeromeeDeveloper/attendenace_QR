<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EmployeeController;

// Attendance Routes
Route::get('/', [AttendanceController::class, 'index'])->name('attendance.index'); // Home page with attendance
Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store'); // Store attendance record
Route::delete('/attendance/{id}', [AttendanceController::class, 'destroy'])->name('attendance.destroy'); // Delete attendance record

// Employee Routes
Route::resource('employees', EmployeeController::class); // This will create all the necessary employee routes
