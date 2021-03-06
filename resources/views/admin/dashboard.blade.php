@extends('layouts.default')

@section('meta')
    <title>Dashboard | Attendance Keeper</title>
    <meta name="description"
          content="Attendance Keeper dashboard, view recent attendance, recent leaves of absence, and newest employees">
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2 class="page-title">Dashboard</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-paste"><i class="ui icon user circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">EMPLOYEES</span>
                        <div class="progress-group">
                            <div class="progress sm">
                                <div class="progress-bar progress-bar-paste" style="width: 100%"></div>
                            </div>
                            <div class="stats_d">
                                <table style="width: 100%;">
                                    <tbody>
                                    <tr>
                                        <td>Regular</td>
                                        <td>@isset($emp_typeR) {{ $emp_typeR }} @endisset</td>
                                    </tr>
                                    <tr>
                                        <td>Trainee</td>
                                        <td>@isset($emp_typeT) {{ $emp_typeT }} @endisset</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-ash"><i class="ui icon clock outline"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">ATTENDANCES</span>
                        <div class="progress-group">
                            <div class="progress sm">
                                <div class="progress-bar progress-bar-green" style="width: 100%"></div>
                            </div>
                            <div class="stats_d">
                                <table style="width: 100%;">
                                    <tbody>
                                    <tr>
                                        <td>Online</td>
                                        <td>@isset($is_online_now) {{ $is_online_now }} @endisset</td>
                                    </tr>
                                    <tr>
                                        <td>Offline</td>
                                        <td>@isset($is_offline_now) {{ $is_offline_now }} @endisset</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-white"><i class="ui icon home"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">LEAVES OF ABSENCE</span>
                        <div class="progress-group">
                            <div class="progress sm">
                                <div class="progress-bar progress-bar-white" style="width: 100%"></div>
                            </div>
                            <div class="stats_d">
                                <table style="width: 100%;">
                                    <tbody>
                                    <tr>
                                        <td>Approved</td>
                                        <td>@isset($emp_leaves_approve) {{ $emp_leaves_approve }} @endisset</td>
                                    </tr>
                                    <tr>
                                        <td>Pending</td>
                                        <td>@isset($emp_leaves_pending) {{ $emp_leaves_pending }} @endisset</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
              <div class="col bg-light box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Task Deadline Tracker</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                    class="fa fa-times"></i></button>
                    </div>
                </div>
                <table class="table responsive nobordertop">
                    <thead>
                    <tr>
                        <th class="text-left">Employee Name</th>
                        <th class="text-left">Original Deadline</th>
                        <th class="text-left">New Deadline</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @isset($task_collection)
                        @foreach ($task_collection as $data)
                            <tr>
                                <td class="text-left name-title">{{ $data->assigned_to }}
                                    </td>
                                <td class="text-left">@php echo e(date('M d, Y', strtotime($data->original_deadline))) @endphp</td>
                                <td class="text-left">@php echo e(date('M d, Y', strtotime($data->deadline))) @endphp</td>
                                <td><a href="{{ url('task/details/'.$data->id) }}" class="ui circular basic icon button tiny"><i class="icon align justify "></i></a></td>
                            </tr>
                        @endforeach
                    @endisset
                    </tbody>
                </table>
              </div>
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Newest Employees</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table responsive nobordertop">
                            <thead>
                            <tr>
                                <th class="text-left">Name</th>
                                <th class="text-left">Position</th>
                                <th class="text-left">Start Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @isset($emp_all_type)
                                @foreach ($emp_all_type as $data)
                                    <tr>
                                        <td class="text-left name-title">{{ $data->lastname }}
                                            , {{ $data->firstname }}</td>
                                        <td class="text-left">{{ $data->jobposition }}</td>
                                        <td class="text-left">@php echo e(date('M d, Y', strtotime($data->startdate))) @endphp</td>
                                    </tr>
                                @endforeach
                            @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Recent Attendances</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table responsive nobordertop">
                            <thead>
                            <tr>
                                <th class="text-left">Name</th>
                                <th class="text-left">Type</th>
                                <th class="text-left">Time</th>
                            </tr>
                            </thead>
                            <tbody>
                            @isset($sortedActivities)
                                @foreach($sortedActivities as $v)

                                <tr>
                                  <td>{{$v->employee}}</td>
                                  <td>{{$v->label}}</td>
                                  <td>@php echo e(date('h:i:s A', strtotime($v->datetime))); @endphp</td>
                                </tr>


                                @endforeach
                            @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Recent Leaves of Absence</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table responsive nobordertop">
                            <thead>
                            <tr>
                                <th class="text-left">Name</th>
                                <th class="text-left">Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @isset($recent_leaves)
                                @foreach ($recent_leaves as $leaves)
                                    <tr>
                                        <td class="text-left name-title">{{ $leaves->employee }}</td>
                                        <td class="text-left">@php echo e(date('M d, Y', strtotime($leaves->leavefrom))) @endphp</td>
                                    </tr>
                                @endforeach
                            @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
