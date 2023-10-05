<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Employee;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        $employees = Employee::where('user_id',auth()->user()->id)->get();
        return view("index",compact("employees"));
    }

    public function create()
    {
        return view("create");
    }

    public function store(Request $request)
    {
        $this->dataValidation($request, null);

        $employee = new Employee();
        $this->dataInserting($employee, $request);
        $employee->created_at = Carbon::now();
        $employee->save();

        Toastr::success("One Employee is successfully created.", "Success Message", ["closeButton" => true, "progressBar" => true, "positionClass" => "toast-bottom-right"]);
        return redirect()->route("employee.index");
    }

    public function edit(string $id)
    {
        $employee = Employee::find($id);
        filterEmployee(auth()->user()->id, $employee->user_id, null);
        return view("create",compact("employee"));
    }

    public function update(Request $request, string $id)
    {
        $employee = Employee::find($id);
        filterEmployee(auth()->user()->id, $employee->user_id, null);

        $this->dataValidation($request, $id);

        $this->dataInserting($employee, $request);
        $employee->updated_at = Carbon::now();
        $employee->save();

        Toastr::success("One Employee is successfully updated.", "Success Message", ["closeButton" => true, "progressBar" => true, "positionClass" => "toast-bottom-right"]);
        return redirect()->route("employee.index");
    }

    public function destroy(string $id)
    {
        $employee = Employee::find($id);
        filterEmployee(auth()->user()->id, $employee->user_id, null);

        $employee->delete();

        return response()->json([
            "status" => "success",
            "message" => "One Employee data has been deleted."
        ]);
    }

    private function dataValidation($request, $id)
    {
        $request->validate([
            "name" => "required|max:50",
            "email"=> "required|email|unique:employees,email,{$id}",
            "age" => "required|integer|min:16|max:60",
            "phone" => "required",
            "address" => "required",
            "department" => 'required',
            "location" => 'required|in:main_office,yuzana_tower,downtown',
            "position" => "required"
        ],[
            "age.min" => "An applicant is not old enough to be employee.",
            "age.max" => "An applicant's age exceeds our limitation."
        ]);
    }

    private function dataInserting($employee, $request)
    {
        $employee->user_id = auth()->user()->id;
        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->age = $request->age;
        $employee->phone = $request->phone;
        $employee->address = $request->address;
        $employee->department = $request->department;
        $employee->location = $request->location;
        $employee->position = $request->position;
    }
}
