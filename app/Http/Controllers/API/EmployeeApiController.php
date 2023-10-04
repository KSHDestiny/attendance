<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class EmployeeApiController extends Controller
{
    public function index()
    {
        return Employee::where('user_id',auth()->user()->id)->get();
    }

    public function store(Request $request)
    {
        $employee = new Employee();

        $data = $this->data($request);

        $validator = $this->dataValidation($data, null);
        if ($validator->fails()) {
            return response("Invalid data.",400);
        }
        $validator->validated();

        $this->dataInserting($employee, $request);
        $employee->created_at = Carbon::now();
        $employee->save();

        return response("Successfully created.",200);
    }

    public function updateData(Request $request, string $id)
    {
        $employee = Employee::find($id);

        // ! Empty Data
        if(empty($employee)){
            return response("No data for id:{$id}", 400);
        }

        $status = filterEmployee(auth()->user()->id, $employee->user_id, "api");
        if($status == "403"){
            return response('Unauthorized User', 403);
        }

        // ! Validation
        $data = $this->data($request);
        $validator = $this->dataValidation($data, $id);
        if ($validator->fails()) {
            return response("Invalid data.",400);
        }
        $validator->validated();

        // ! data Inserting
        $this->dataInserting($employee, $request);
        $employee->updated_at = Carbon::now();
        $employee->save();

        return response("Successfully updated.",200);
    }

    public function update(Request $request, string $id)
    {
        $employee = Employee::find($id);
        $status = filterEmployee(auth()->user()->id, $employee->user_id, "api");
        if($status == "403"){
            return response('Unauthorized User', 403);
        }

        // ! Empty Data
        if(empty($employee)){
            return response("No data for id:{$id}", 400);
        }

        // ! Validation
        $data = $this->data($request);
        $validator = $this->dataValidation($data, $id);
        if ($validator->fails()) {
            return response("Invalid data.",400);
        }
        $validator->validated();

        // ! data Inserting
        $this->dataInserting($employee, $request);
        $employee->updated_at = Carbon::now();
        $employee->save();

        return response("Successfully updated.",200);
    }

    public function destroy(string $id)
    {
        $employee = Employee::find($id);
        if(empty($employee)){
            return response("No data for id:{$id}", 400);
        }

        $status = filterEmployee(auth()->user()->id, $employee->user_id, "api");
        if($status == "403"){
            return response('Unauthorized User', 403);
        }

        $employee->delete();
        return response('Successfully deleted.', 200);
    }

    private function data($request){
        return [
            'user_id' => auth()->user()->id,
            'name' => $request->name,
            'email' => $request->email,
            'age' => $request->age,
            'phone' => $request->phone,
            'address' => $request->address,
            'position' => $request->position,
        ];
    }

    private function dataValidation($data, $id){
        return Validator::make($data,[
            "user_id" => "required|exists:users,id",
            "name" => "required|max:50",
            "email"=> "required|email|unique:employees,email,{$id}",
            "age" => "required|integer|between:18,30",
            "phone" => "required",
            "address" => "required",
            "position" => "required"
        ]);

    }

    private function dataInserting($employee, $request){
        $employee->user_id = auth()->user()->id;
        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->age = $request->age;
        $employee->phone = $request->phone;
        $employee->address = $request->address;
        $employee->position = $request->position;
    }
}
