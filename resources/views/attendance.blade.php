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
                <select id="department" class="form-select">
                    <option value="" class="selected disabled">Department</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->department }}">{{ $department->department }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select id="position" class="form-select ms-2">
                    <option value="" class="selected disabled">Position</option>
                    @foreach ($positions as $position)
                        <option value="{{ $position->position }}">{{ $position->position }}</option>
                    @endforeach
                </select>
            </div>
        </section>
        <section class="form-group d-flex">
            <input type="text" id="name" class="form-control" placeholder="Enter Employee Name...">
            <button id="searchNameBtn" class="btn btn-success ms-2">Search</button>
            <button onclick="location.reload();" class="btn btn-danger ms-2">Clear</button>
        </section>
    </article>
    <article id="tableField">
        @if(count($attendances) > 0)
        <section class="d-flex">
            <table class="table table-light mt-2">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th class="d-none d-md-table-cell">Department</th>
                        <th class="d-none d-md-table-cell">Location</th>
                        <th class="d-none d-md-table-cell">Position</th>
                    </tr>
                </thead>
                <tbody id="attendanceData">
                    @foreach ($attendances as $attendance)
                    <tr>
                        <td>{{ $attendance->employee->name }}</td>
                        <td class="d-none d-md-table-cell">{{ $attendance->employee->department }}</td>
                        <td class="d-none d-md-table-cell">{{ $attendance->employee->location }}</td>
                        <td class="d-none d-md-table-cell">{{ $attendance->employee->position }}</td>
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
                <tbody id="attendanceStatus">
                    @foreach ($attendances as $attendance)
                    <tr data-bs-toggle="modal" data-bs-target="#staticBackdrop{{ $attendance->id }}">
                        <td class="@if($attendance->status == "On time") text-success @elseif ($attendance->status == "Late") text-warning @else text-danger @endif">{{ $attendance->status }}</td>
                        @if ($attendance->status == "Absent")
                            <td class="text-danger">A</td>
                            <td class="text-danger">A</td>
                            <td class="text-danger">A</td>
                        @else
                            <td class="@if($attendance->status == "Late") text-warning @else text-success @endif">{{ $attendance->start }}</td>
                            <td class="@if($attendance->status == "Late") text-warning @else text-success @endif">{{ $attendance->break }}</td>
                            <td class="@if($attendance->status == "Late") text-warning @else text-success @endif">{{ $attendance->finish }}</td>
                        @endif
                    </tr>

                    {{-- Edit Model --}}
                    <article class="modal fade" id="staticBackdrop{{ $attendance->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <section class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel{{ $attendance->id }}">Edit Attendance</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </section>
                                <section class="modal-body">
                                    <form action="{{ route('attendance.edit') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $attendance->id }}">
                                        <input type="hidden" name="name" value="{{ $attendance->employee->name }}">
                                        <div class="my-2">
                                            <label for="status">Status</label>
                                            <select name="status" id="status" class="form-select">
                                                <option value="On time" @if ($attendance->status == "On time")
                                                    selected
                                                @endif>On time</option>
                                                <option value="Absent" @if ($attendance->status == "Absent")
                                                    selected
                                                @endif>Absent</option>
                                                <option value="Late" @if ($attendance->status == "Late")
                                                    selected
                                                @endif>Late</option>
                                            </select>
                                        </div>
                                        <div class="my-2">
                                            <label for="start">Start</label>
                                            <input type="time" name="start" id="start" value="{{ old('start',$attendance->start) }}" class="form-control">
                                        </div>
                                        <div class="my-2">
                                            <label for="break">Break</label>
                                            <input type="text" name="break" id="break" pattern="[0-9]{2}:[0-59]{2}" value="{{ old('break',$attendance->break) }}" placeholder="01:00" class="form-control">
                                        </div>
                                        <div class="my-2">
                                            <label for="finish">Finish</label>
                                            <input type="time" name="finish" id="finish" value="{{ old('finish',$attendance->finish) }}" class="form-control">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </section>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </tbody>
            </table>
        </section>
        @else
            <h1 class="text-center display-3 mt-5 text-danger">There is no data!</h1>
        @endif
    </article>
@endsection

    @section('script')
        $("#department").change(function(){
            changeInput();
        });

        $("#position").change(function(){
            changeInput();
        });

        $("#searchNameBtn").click(function(){
            changeInput();
        });

        $('#name').keypress(function(event){
            if(event.keyCode == '13'){
                changeInput();
            }
        });

        function changeInput(){
            let department = $('#department').val();
            let position = $('#position').val();
            let name = $('#name').val();

            $.ajax({
                url: '{{ route('attendance.search') }}',
                method: "POST",
                headers: {'X-CSRF-Token' : '{{ csrf_token() }}'},
                data: {department, position, name},
                success: function({attendances}){
                    if(attendances.length > 0){
                        $("#emptyData").remove();

                        let data = "";
                        attendances.forEach(attendance => {
                            data += `
                            <tr>
                                <td>${attendance.name}</td>
                                <td class="d-none d-md-table-cell">${attendance.department}</td>
                                <td class="d-none d-md-table-cell text-capitalize">${attendance.location.replace('_',' ')}</td>
                                <td class="d-none d-md-table-cell">${attendance.position}</td>
                            </tr>
                            `;
                        })
                        $('#attendanceData').html(data);

                        let status = "";
                        attendances.forEach(attendance => {
                            let statusClass = "";
                            let onTimeSelected = "";
                            let absentSelected = "";
                            let lateSelected = "";
                            if(attendance.status == "On time"){
                                statusClass = "text-success";
                                onTimeSelected = "selected";
                            } else if (attendance.status == "Late"){
                                statusClass = "text-warning";
                                absentSelected = "selected";
                            } else {
                                statusClass = "text-danger";
                                lateSelected = "selected";
                            }

                            let tableColumn = "";
                            if(attendance.status == "Absent"){
                                tableColumn = `
                                <td class="text-danger">A</td>
                                <td class="text-danger">A</td>
                                <td class="text-danger">A</td>
                                `;
                            }else{
                                tableColumn = `
                                <td class=${statusClass}>${attendance.start}</td>
                                <td class=${statusClass}>${attendance.break}</td>
                                <td class=${statusClass}>${attendance.finish}</td>
                                `
                            }

                            let route = '{{ route('attendance.edit') }}';
                            let csrf = '{{ csrf_token() }}';

                            status += `
                            <tr data-bs-toggle="modal" data-bs-target="#staticBackdrop${attendance.id}">
                                <td class=${statusClass}>${attendance.status}</td>
                                ${tableColumn}
                            </tr>

                            <article class="modal fade" id="staticBackdrop${attendance.id}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <section class="modal-header">
                                            <h1 class="modal-title fs-5" id="staticBackdropLabel${attendance.id}">Edit Attendance</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </section>
                                        <section class="modal-body">
                                            <form action=${route} method="POST">
                                                ${csrf}
                                                <input type="hidden" name="id" value="${attendance.id}">
                                                <input type="hidden" name="name" value="${attendance.name}">
                                                <div class="my-2">
                                                    <label for="status">Status</label>
                                                    <select name="status" id="status" class="form-select">
                                                        <option value="On time" ${onTimeSelected}>On time</option>
                                                        <option value="Absent" ${absentSelected}>Absent</option>
                                                        <option value="Late" ${lateSelected}>Late</option>
                                                    </select>
                                                </div>
                                                <div class="my-2">
                                                    <label for="start">Start</label>
                                                    <input type="time" name="start" id="start" value=${attendance.start} class="form-control">
                                                </div>
                                                <div class="my-2">
                                                    <label for="break">Break</label>
                                                    <input type="text" name="break" id="break" pattern="[0-9]{2}:[0-59]{2}" value=${attendance.break} placeholder="01:00" class="form-control">
                                                </div>
                                                <div class="my-2">
                                                    <label for="finish">Finish</label>
                                                    <input type="time" name="finish" id="finish" value=${attendance.finish} class="form-control">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </form>
                                        </section>
                                    </div>
                                </div>
                            </article>
                            `;
                        })
                        $("#attendanceStatus").html(status);
                    } else {
                        $('#attendanceData').html("");
                        $("#attendanceStatus").html("");

                        let data = `<h1 id="emptyData" class="text-center display-3 mt-5 text-danger">There is no data!</h1>`;
                        $("#tableField").append(data);
                    }
                }
            })
        }
    @endsection
