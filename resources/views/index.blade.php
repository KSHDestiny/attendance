@extends('layouts.app')

@section('title',"Dashboard")

@section('CR')
    <a href="{{ route('employee.create') }}" class="text-decoration-none">{{ __('create.createEmployee') }} +</a>
@endsection

@section('content')
<link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">

    <article>
        <table class="table table-dark align-middle" id="employeeTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('create.name') }}</th>
                    <th>{{ __('create.email') }}</th>
                    <th>{{ __('create.age') }}</th>
                    <th>{{ __('create.phone') }}</th>
                    <th>{{ __('create.address') }}</th>
                    <th>{{ __('create.department') }}</th>
                    <th>{{ __('create.location') }}</th>
                    <th>{{ __('create.position') }}</th>
                    <th>{{ __('create.edit') }}</th>
                    <th>{{ __('create.delete') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($employees as $employee)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $employee->name }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->age }}</td>
                        <td>{{ $employee->phone }}</td>
                        <td>{{ $employee->address }}</td>
                        <td>{{ $employee->department }}</td>
                        <td>{{ $employee->location }}</td>
                        <td>{{ $employee->position }}</td>
                        <td>
                            <a href="{{ route('employee.edit',$employee->id) }}" class="btn btn-warning">{{ __('create.edit') }}</a>
                        </td>
                        <td>
                            <a href="javascript:;" class="btn btn-danger" onclick="destroy({{ $employee->id }})">{{ __('create.delete') }}</a>
                        </td>
                    </tr>
                @empty

                @endforelse
            </tbody>
        </table>
    </article>

    {{ $employees->links('pagination::bootstrap-5') }}

@endsection

@section('script')

function destroy(id){
    Swal.fire({
        title: '{{ __('create.areYouSure') }}',
        text: "{{ __('create.cantRevert') }}",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '{{ __('create.yes') }}',
        cancelButtonText: '{{ __('create.no') }}',
    }).then((result) => {
        if (result.isConfirmed) {
            let route = "{{ route('employee.destroy',':id') }}";
            route = route.replace(':id',id);
            $.ajax({
                url: route,
                method: "DELETE",
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}'
                },
                data: {id},
                success: function({status,message}){
                    Swal.fire('Deleted!',message,status);

                    $('#employeeTable tbody').load(
                        location.href + '#employeeTable tbody tr'
                    );
                }
            })

        }
    })
}
@endsection
