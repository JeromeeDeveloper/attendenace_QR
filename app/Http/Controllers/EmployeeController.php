<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        return view('employee.index', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_name' => 'required',
            'department' => 'required',
            'generated_code' => 'required|unique:tbl_employee',
        ]);

        Employee::create($request->all());
        return redirect()->route('employees.index')->with('success', 'Employee added successfully!');
    }


    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        return response()->json($employee);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'employee_name' => 'required',
            'department' => 'required',
        ]);
    
        $employee = Employee::findOrFail($id);
        $employee->update($request->all());
        return redirect()->route('employees.index')->with('success', 'Employee updated successfully!'); // Change 'employee.index' to 'employees.index'
    }
    
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
    
        return response()->json(['success' => true, 'message' => 'Employee deleted successfully!']);
    }
    
}    
