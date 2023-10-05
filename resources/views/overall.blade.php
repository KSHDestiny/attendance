@extends('layouts.app')

@section('title',"Overall Attendance")

@section('content')
<link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">

    <article class="d-flex justify-content-between align-items-center">
        <h3>@isset($name) {{ $name }}'s @else Employee @endisset Attendance Overall</h3>
        <form action="{{ route('overall.data') }}" method="POST">
            @csrf
            <section class="form-group d-flex">
                <input class="form-control" id="name" list='nameList' name="name" placeholder="Enter Employee Name..." required>
                <datalist id="nameList">
                    @foreach ($attendees as $attendee)
                        <option value="{{ $attendee->employee->name }}"></option>
                    @endforeach
                </datalist>
                <button class="btn btn-success">Find</button>
            </section>
        </form>
    </article>
    <article class="row mt-5">
        @if(isset($linejs) || isset($barjs))
            @isset($linejs)
                <section class="col-12 col-md-6">
                    {!! $linejs->render() !!}
                </section>
            @endisset
            @isset($barjs)
                <section class="col-12 col-md-6">
                    {!! $barjs->render() !!}
                </section>
            @endisset
        @else
            <h1 class="text-center text-danger mt-5">This field is to be shown employee's attendance data <br>when finding with employee's name</h1>
        @endif
    </article>
@endsection
