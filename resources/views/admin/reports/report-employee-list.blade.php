@extends('layouts.default')
    
    @section('meta')
        <title>Reports | Attendance Keeper</title>
        <meta name="description" content="Attendance Keeper reports, view reports, and export or download reports.">
    @endsection

    @section('content')
    
    <div class="container-fluid">
        <div class="row">
            <h2 class="page-title">Employee List Report
                <a href="{{ url('export/report/employees') }}" class="ui basic button mini offsettop5 btn-export float-right"><i class="ui icon download"></i>Export to CSV</a>
                <a href="{{ url('reports') }}" class="ui basic blue button mini offsettop5 float-right"><i class="ui icon chevron left"></i>Return</a>
            </h2>
        </div>

        <div class="row">
            <div class="box box-success">
                <div class="box-body">
                    <table width="100%" class="table table-striped table-hover" id="dataTables-example" data-order='[[ 0, "asc" ]]'>
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Civil Status</th>
                                <th>Mobile Number</th>
                                <th>E-mail</th>
                                <th>Employment Type</th>
                                <th>Employment Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($empList)
                            @foreach ($empList as $et)
                                <tr>
                                    <td>{{ $et->lastname }}, {{ $et->firstname }} {{ $et->mi }}</td>
                                    @php
                                    $now = new DateTime();
                                    $bday = new DateTime($et->birthday);

                                    $difference = $now->diff($bday);

                                    $et->age = $difference->format("%y years");

                                    @endphp
                                    <td>{{ $et->age }}</td>
                                    <td>{{ $et->gender }}</td>
                                    <td>{{ $et->civilstatus }}</td>
                                    <td>{{ $et->mobileno }}</td>
                                    <td>{{ $et->emailaddress }}</td>
                                    <td>{{ $et->employmenttype }}</td>
                                    <td>{{ $et->employmentstatus }}</td>
                                </tr>
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