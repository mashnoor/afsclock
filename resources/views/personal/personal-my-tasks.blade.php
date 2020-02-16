@extends('layouts.personal')

@section('meta')
    <title>My Tasks | Attendnace Keeper</title>
    <meta name="description"
          content="Attendance Keepert my schedules, view my schedule records, view present and previous schedules.">
@endsection

@section('styles')
    <link href="{{ asset('/assets/vendor/air-datepicker/dist/css/datepicker.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2 class="page-title">My Tasks</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-body reportstable">

                        <table width="100%" class="table table-striped table-bordered table-hover"
                               id="dataTables-example" data-order='[[ 6, "desc" ]]'>
                            <thead>
                            <tr>
                                <th>Assigned By</th>
                                <th>Title</th>
                                <th>Deadline</th>
                                <th>Finish Date</th>
                                <th>Status</th>
                                <th>Comment</th>
                                <th class="align-right">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @isset($tasks)
                                @foreach ($tasks as $task)
                                    <tr>
                                        <td>{{ $task->assignedBy->firstname }} {{ $task->assignedBy->lastname }}</td>
                                        <td>{{ $task->title }}</td>
                                        <td>{{ $task->deadline }}</td>
                                        <td>@isset($task->finishdate){{ $task->finishdate }}@endisset</td>
                                        @php
                                            $color = "red";
                                            $done_status = "Pending";
                                            if(isset($task->finishdate))
                                                {

                                                     if($task->finishdate>$task->deadline)
                                                     {
                                                         $done_status = "Done (Delayed)";
                                                         $color = "red";
                                                     }
                                                     else
                                                     {
                                                         $done_status = "Done (In Time)";
                                                         $color = "green";
                                                     }
                                                }

                                        @endphp
                                        <td><span class="{{ $color }}">{{ $done_status }}</span></td>
                                        <td>{{ $task->comment }}</td>
                                        <td class="align-right">

                                            <a href="{{ url('personal/tasks/edit/'.$task->id) }}"
                                               class="ui circular basic icon button tiny"><i
                                                        class="icon edit outline"></i></a>

                                        </td>
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
