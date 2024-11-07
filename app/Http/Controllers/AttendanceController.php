<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance; // Make sure to import your Attendance model
use App\Models\Employee;   // Make sure to import your Employee model

class AttendanceController extends Controller
{
    // Show the home page with attendance records
    public function index(Request $request)
    {
        // Start a query for the Attendance model with the related Employee model
        $query = Attendance::with('employee');

        // Date filter: Filter attendances where either time_in or time_out matches the specified date
        if ($request->has('date')) {
            $query->whereDate('time_in', $request->date)
                ->orWhereDate('time_out', $request->date);
        }

        // Search filter: Filter attendances by employee name
        // if ($request->has('search')) {
        //     $search = $request->get('search');
        //     $query->whereHas('employee', function ($q) use ($search) {
        //         $q->where('employee_name', 'like', '%' . $search . '%');
        //     });
        // }

        // Paginate the results with 10 entries per page
        $attendances = $query->paginate(9);

        // Return the view with the attendance data
        return view('attendance.index', compact('attendances'));
    }

    
    

    // Store a new attendance record
    public function store(Request $request)
    {
        $request->validate([
            'qr_code' => 'required',
        ]);
    
        // Fetch employee ID from QR code
        $employee = Employee::where('generated_code', $request->qr_code)->first();
    
        // Debugging: Check the retrieved employee
        if (!$employee) {
            return redirect('/')->withErrors(['qr_code' => 'No employee found for the given QR code.']);
        } else {
            \Log::info('Employee found:', ['id' => $employee->tbl_employee_id]); // Log the ID for debugging
        }
    
        // Check if the employee already has a time-in record without a time-out
        $attendance = Attendance::where('tbl_employee_id', $employee->tbl_employee_id)
            ->whereNull('time_out')
            ->first();
    
        if ($attendance) {
            // Update the existing record with the time-out
            $attendance->update(['time_out' => now()]);
    
            return redirect('/')->with('success', 'Attendance recorded successfully! Time out registered.');
        } else {
            // Create a new attendance record for time-in
            Attendance::create([
                'tbl_employee_id' => $employee->tbl_employee_id, // Ensure this is the correct field
                'time_in' => now(),
            ]);
    
            return redirect('/')->with('success', 'Attendance recorded successfully! Time in registered.');
        }
    }
    


    // Delete an attendance record
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();
    
        // Return a JSON response for AJAX requests
        return response()->json(['success' => 'Attendance deleted successfully!']);
    }
    
    
}
