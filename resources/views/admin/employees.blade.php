@extends('layouts.default')

    @section('meta')
        <title>Employees | Attendance Keeper</title>
        <meta name="description" content="Attendance Keeper employees, view all employees, add, edit, delete, and archive employees.">
    @endsection

    @section('content')

    <div class="container-fluid">
        <div class="row">
            <h2 class="page-title">EMPLOYEES
                <a class="ui positive button mini offsettop5 float-right" href="{{ url('employees/new') }}"><i class="ui icon plus"></i>Add</a>
            </h2>
        </div>

        <div class="row">
            <div class="box box-success">
                <div class="box-body">
                <table width="100%" class="table table-striped table-hover" id="dataTables-example" data-order='[[ 0, "desc" ]]'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee</th>
                            <th>Company</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Status</th>
                            <th class=""></th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($employee_collection)
                        @foreach ($employee_collection as $employee)
                          @isset($employee->company)
                            <tr class="">
                            <td>{{ $employee->idno }}</td>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->company }}</td>
                            <td>{{ $employee->department }}</td>
                            <td>{{ $employee->jobtitle }}</td>
                            <td>@if($employee->status == 'Active') Active @else Archived @endif</td>
                            <td class="align-right">
                            <a href="{{ url('/profile/view/'.$employee->id) }}" class="ui circular basic icon button tiny"><i class="file alternate outline icon"></i></a>
                            <a href="{{ url('/profile/edit/'.$employee->id) }}" class="ui circular basic icon button tiny"><i class="edit outline icon"></i></a>
                            <a href="{{ url('/profile/delete/'.$employee->id) }}" class="ui circular basic icon button tiny"><i class="trash alternate outline icon"></i></a>
                            <a href="{{ url('/profile/archive/'.$employee->id) }}" class="ui circular basic icon button tiny"><i class="archive icon"></i></a>
                            </td>
                            </tr>
                          @endisset
                        @endforeach
                        @endisset
                    </tbody>
                </table>
                </div>
            </div>
        </div>

    </div>

    @endsection

    @section('scripts')
    <script type="text/javascript">
    $('#dataTables-example').DataTable({responsive: true,pageLength: 15,lengthChange: false,searching: true,ordering: true});
    </script>
    @endsection
