@extends('layouts.app')

@section('title',"Attendance List")

@section('CR')
    <article class="d-flex">
        <form action="{{ route('attendance.create') }}" method="POST">
            @csrf
            <button class="btn btn-warning mx-1">Create All</button>
        </form>
        <form action="{{ route('attendance.delete') }}" method="POST">
            @csrf
            <button class="btn btn-danger mx-1">Delete All</button>
        </form>
    </article>
@endsection

@section('content')
<link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">

    <article class="d-flex justify-content-between align-items-center">
        <section class="d-flex">
            <div class="form-group">
                <select class="form-select">
                    <option value="" class="selected disabled">Department</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->department }}">{{ $department->department }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select class="form-select ms-2">
                    <option value="" class="selected disabled">Position</option>
                    @foreach ($positions as $position)
                        <option value="{{ $position->position }}">{{ $position->position }}</option>
                    @endforeach
                </select>
            </div>
        </section>
        <section class="form-group d-flex">
            <input type="text" class="form-control" placeholder="Enter Employee Name...">
            <button class="btn btn-success ms-2">Search</button>
        </section>
    </article>
    @if(count($attendances) > 0)
        <article class="d-flex">
            <table class="table table-light mt-2">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Location</th>
                        <th>Position</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attendances as $attendance)
                    <tr>
                        <td>{{ $attendance->employee->name }}</td>
                        <td>{{ $attendance->employee->department }}</td>
                        <td>{{ $attendance->employee->location }}</td>
                        <td>{{ $attendance->employee->position }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <table class="table table-hover mt-2 ms-2">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Start</th>
                        <th>Break</th>
                        <th>Finish</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attendances as $attendance)
                    <tr>
                        <td>{{ $attendance->status }}</td>
                        <td>{{ $attendance->start }}</td>
                        <td>{{ $attendance->break }}</td>
                        <td>{{ $attendance->finish }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </article>
        @else
            <h1 class="text-center display-3 mt-5 text-danger">There is no data!</h1>
        @endif
@endsection
