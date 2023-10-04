@extends('layouts.app')

@section('CR')
    <a href="{{ route('employee.create') }}" class="text-decoration-none">Create One +</a>
@endsection

@section('content')
<link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">

    <article>
        <table class="table table-dark align-middle" id="employeeTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Age</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Position</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($employees as $employee)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $employee->name }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->age }}</td>
                        <td>{{ $employee->phone }}</td>
                        <td>{{ $employee->address }}</td>
                        <td>{{ $employee->position }}</td>
                        <td>
                            <a href="{{ route('employee.edit',$employee->id) }}" class="btn btn-warning">Edit</a>
                        </td>
                        <td>
                            <a href="javascript:;" class="btn btn-danger" onclick="destroy({{ $employee->id }})">Delete</a>
                        </td>
                    </tr>
                @empty

                @endforelse
            </tbody>
        </table>
    </article>

@endsection

@section('script')

function destroy(id){
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
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
