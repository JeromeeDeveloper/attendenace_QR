<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProfileController;

// Attendance Routes
Route::get('/', [AttendanceController::class, 'index'])->name('attendance.index'); // Public homepage displaying attendance

// Unauthenticated attendance store route (no login required)
Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store'); // Store attendance record

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard - Employee Management
    Route::get('/dashboard', [EmployeeController::class, 'index'])->name('dashboard'); // Dashboard for authenticated users

    // Protected Attendance Routes
    Route::delete('/attendance/{id}', [AttendanceController::class, 'destroy'])->name('attendance.destroy'); // Delete attendance record

    // Employee Management Routes
    Route::resource('employees', EmployeeController::class); // All employee-related routes for authenticated users

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Add the route inside the middleware group for authenticated users
 // Employee Attendance Route
 Route::get('/employee/attendance', [AttendanceController::class, 'employeeAttendance'])->name('employee.attendance'); // Employee-specific attendance view


// Authentication routes provided by Laravel Breeze
require __DIR__.'/auth.php';

