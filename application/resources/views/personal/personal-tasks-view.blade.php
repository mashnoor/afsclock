@extends('layouts.personal')

@section('meta')
    <title>My Tasks | Smart Timesheet</title>
    <meta name="description"
          content="smart timesheet my schedules, view my schedule records, view present and previous schedules.">
@endsection

@section('styles')
    <link href="{{ asset('/assets/vendor/air-datepicker/dist/css/datepicker.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2 class="page-title">My Schedules</h2>
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
                                        <td>
                                            @if($task->done_status == 1)
                                                <span class="green">Done</span>
                                            @else
                                                <span class="red">Pending</span>
                                            @endif
                                        </td>
                                        <td>{{ $task->comment }}</td>
                                        <td class="align-right">

                                            <a href="{{ url('/task/edit/'.$task->id) }}"
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
