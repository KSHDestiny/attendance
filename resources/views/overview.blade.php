@extends('layouts.app')

@section('title',"Overview Attendance")

@section('content')
    <article class="row justify-content-center align-items-center">
        @if(count($onTime) > 0 ||  count($absent) > 0 ||  count($late) > 0)
            <section class="col-12 col-md-4">
                <div class="card">
                    <div class="card-header">
                    Attendance
                    </div>
                    <ul class="list-group list-group-flush">
                        <a href="javascript:;" class="text-decoration-none" @if(count($onTime) > 0) data-bs-toggle="modal" data-bs-target="#onTime" @endif>
                            <li class="list-group-item">On Time <span class="float-end fw-bolder">{{ count($onTime) }}</span></li>
                        </a>
                        <a href="javascript:;" class="text-decoration-none" @if(count($absent) > 0) data-bs-toggle="modal" data-bs-target="#absent" @endif>
                            <li class="list-group-item">Absent <span class="float-end fw-bolder">{{ count($absent) }}</span></li>
                        </a>
                        <a href="javascript:;" class="text-decoration-none" @if(count($late) > 0) data-bs-toggle="modal" data-bs-target="#late" @endif>
                            <li class="list-group-item">Late <span class="float-end fw-bolder">{{ count($late) }}</span></li>
                        </a>
                    </ul>
                </div>
            </section>
            <section class="col-12 col-md-4">
                {!! $piejs->render() !!}
            </section>
            <section class="col-12 col-md-4">
                {!! $barjs->render() !!}
            </section>

            <section class="modal fade" id="onTime" tabindex="-1" aria-labelledby="onTimeLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable">
                    <ol class="list-group list-group-numbered" style="width: 600px">
                        @foreach ($onTime as $item)
                            <li class="list-group-item">{{ $item->employee->name }} - {{ $item->employee->position }} / {{ $item->employee->department }} <span class="float-end">({{ $item->employee->location }})</span></li>
                        @endforeach
                    </ol>
                </div>
            </section>

            <section class="modal fade" id="absent" tabindex="-1" aria-labelledby="absentLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable">
                    <ol class="list-group list-group-numbered" style="width: 600px">
                        @foreach ($absent as $item)
                            <li class="list-group-item">{{ $item->employee->name }} - {{ $item->employee->position }} / {{ $item->employee->department }} <span class="float-end">({{ $item->employee->location }})</span></li>
                        @endforeach
                    </ol>
                </div>
            </section>

            <section class="modal fade" id="late" tabindex="-1" aria-labelledby="lateLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable">
                    <ol class="list-group list-group-numbered" style="width: 600px">
                        @foreach ($late as $item)
                            <li class="list-group-item">{{ $item->employee->name }} - {{ $item->employee->position }} / {{ $item->employee->department }} <span class="float-end">({{ $item->employee->location }})</span></li>
                        @endforeach
                    </ol>
                </div>
            </section>
        @else
            <h1 class="text-center display-3 mt-5 text-danger">There is no data!</h1>
        @endif
    </article>
@endsection
