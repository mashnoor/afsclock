@extends('layouts.personal')

@section('meta')
    <title>My Tasks | Attendnace Keeper</title>
    <meta name="description"
          content="Attendance Keeper my schedules, view my schedule records, view present and previous schedules.">
@endsection

@section('styles')


@endsection

@section('content')

<div class="container p-5">
  <div class="col">
    <h3 class="px-3">Task Title: <span>{{$task->title}}</span></h3>
    <p class="px-3"><strong>Description :</strong> {{$task->description}} </p>
    <div class="row">
      <div class="col-4">
        <p class="px-3"><strong>Original Deadline : </strong>{{$task->deadline}}</p>
      </div>
      <div class="col-4">
        <p class="px-3"><strong>Assigned To : </strong> {{$assigned_to->name}}</p>
      </div>
      <div class="col-4">
        <p class="px-3"><strong>Assigned By : </strong> {{$assigned_by->name}}</p>
      </div>
    </div>

    <div class="col-12 mt-5">
      <h3>Deadline Extension History</h3>
      <table class="ui celled table">
  <thead>
    <tr><th>Time</th>
    <th>Reason</th>
    <th>New Deadline</th>
  </tr></thead>
  <tbody>
    @isset($task_history)
        @foreach ($task_history as $data)
          <tr>
            <td data-label="Name">{{$data->datetime}}</td>
            <td data-label="Age">{{$data->reason}}</td>
            <td data-label="Job">{{$data->new_deadline}}</td>
          </tr>

        @endforeach
      @endisset

  </tbody>
</table>
    </div>
  </div>
</div>


@endsection
@section('scripts')

<script src="{{ asset('/assets/vendor/datetimepicker/datetimepicker.js') }}"></script>

<script type="text/javascript">


</script>

@endsection
