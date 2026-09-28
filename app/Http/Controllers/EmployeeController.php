<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::query();

        if ($search = $request->get('search')) {
            $query->search($search);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($department = $request->get('department')) {
            $query->where('department', $department);
        }

        $employees  = $query->orderBy('employee_name')->paginate(15)->withQueryString();
        $departments = Employee::distinct()->pluck('department')->filter()->sort()->values();

        return view('employees.index', compact('employees', 'departments'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateEmployee($request);
        Employee::create($data);

        return redirect()->route('employees.index')
            ->with('success', 'Employee added successfully.');
    }

    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $this->validateEmployee($request, $employee->id);
        $employee->update($data);

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->update(['status' => 'inactive']);

        return redirect()->route('employees.index')
            ->with('success', 'Employee deactivated successfully.');
    }

    private function validateEmployee(Request $request, ?int $excludeId = null): array
    {
        return $request->validate([
            'employee_code'  => 'required|string|max:50|unique:employees,employee_code' . ($excludeId ? ",{$excludeId}" : ''),
            'employee_name'  => 'required|string|max:255',
            'email'          => 'required|email|max:255|unique:employees,email' . ($excludeId ? ",{$excludeId}" : ''),
            'department'     => 'nullable|string|max:100',
            'designation'    => 'nullable|string|max:100',
            'date_of_birth'  => 'nullable|date|before:today',
            'date_of_joining' => 'nullable|date',
            'manager_name'   => 'nullable|string|max:255',
            'manager_email'  => 'nullable|email|max:255',
            'status'         => 'required|in:active,inactive',
        ]);
    }
}
