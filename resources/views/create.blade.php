@extends('layouts.app')

@isset($employee)
    @section('title',"Edit Employee")
@else
    @section('title',"Create Employee")
@endisset

@section('CR')
    <a href="{{ route('employee.index') }}" class="text-decoration-none text-primary">Back</a>
@endsection

@section('content')
    <article>
        <h3 class="text-center display-6">@isset($employee)Update Employee @else Create Employee @endisset</h3>
        <form class="form" action="{{ isset($employee) ? route('employee.update',$employee->id) : route('employee.store') }}" method="POST">
            @isset($employee)
                @method('PUT')
            @endisset
            @csrf
            <div class="form-group mt-3">
                <label class="form-label" for="name">Name</label>
                <input class="form-control" type="text" id="name" name="name" placeholder="Enter Employee Name..." value="{{ isset($employee)? old('name',$employee->name) : old('name') }}">
                @error('name')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group mt-3">
                <label class="form-label" for="email">Email</label>
                <input class="form-control" type="text" id="email" name="email" placeholder="Enter Employee Email..." value="{{ isset($employee)? old('email',$employee->email) : old('email') }}">
                @error('email')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group mt-3">
                <label class="form-label" for="age">Age</label>
                <input class="form-control" type="number" id="age" name="age" placeholder="Enter Employee Age..." value="{{ isset($employee)? old('age',$employee->age) : old('age') }}">
                @error('age')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group mt-3">
                <label class="form-label" for="phone">Phone</label>
                <input class="form-control" type="text" id="phone" name="phone" placeholder="Enter Employee Phone..." value="{{ isset($employee)? old('phone',$employee->phone) : old('phone') }}">
                @error('phone')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group mt-3">
                <label class="form-label" for="address">Address</label>
                <input class="form-control" type="text" id="address" name="address" placeholder="Enter Employee Address..." value="{{ isset($employee)? old('address',$employee->address) : old('address') }}">
                @error('address')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group mt-3">
                <label class="form-label" for="department">Department</label>
                <input class="form-control" id="department" list='dplist' name="department" placeholder="Enter Employee Department..." value="{{ isset($employee)? old('department',$employee->department) : old('department') }}">
                @error('department')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
                <datalist id="dplist">
                    <option value="Admin"></option>
                    <option value="HR-Tech"></option>
                    <option value="Management Team"></option>
                    <option value="IT Department"></option>
                </datalist>
            </div>
            <div class="form-group mt-3">
                <label class="form-label" for="location">Location</label>
                <select name="location" id="location" class="form-select">
                    <option value="" @if(empty($employee)) selected @endif disabled>Choose Office</option>
                    <option value="main_office" @if(isset($employee) && $employee->location == "Main Office") selected @endif>Main Office</option>
                    <option value="yuzana_tower" @if(isset($employee) && $employee->location == "Yuzana Tower") selected @endif>Yuzana Tower</option>
                    <option value="downtown" @if(isset($employee) && $employee->location == "Downtown") selected @endif>Downtown</option>
                </select>
                @error('location')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group mt-3">
                <label class="form-label" for="position">Position</label>
                <input class="form-control" id="position" list='data' name="position" placeholder="Enter Employee Position..." value="{{ isset($employee)? old('position',$employee->position) : old('position') }}">
                @error('position')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
                <datalist id="data">
                    <option value="Junior Developer"></option>
                    <option value="Mid Developer"></option>
                    <option value="Senior Developer"></option>
                    <option value="Team Lead"></option>
                </datalist>
            </div>
            <button class="btn btn-success w-100 mt-3">
                @isset($employee)Update @else Create @endisset
            </button>
        </form>
    </article>
@endsection
