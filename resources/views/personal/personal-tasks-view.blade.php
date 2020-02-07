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
        <div class="fixedcenter">

            <a class="btn btn-primary" href="{{ url('personal/tasks/mytasks') }}" role="button">My Tasks</a>
            <a class="btn btn-primary" href="{{ url('personal/tasks/assignatask') }}" role="button">Assign a task</a>

        </div>
    </div>

@endsection
