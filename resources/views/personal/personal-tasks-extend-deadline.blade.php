@extends('layouts.personal')

@section('meta')
    <title>My Tasks | Attendnace Keeper</title>
    <meta name="description"
          content="Attendance Keeper my schedules, view my schedule records, view present and previous schedules.">
@endsection

@section('styles')
<link href="{{ asset('/assets/vendor/datetimepicker/datetimepicker.css') }}" rel="stylesheet">

@endsection

@section('content')

  <div class="container">
    <form id="add_schedule_form" action="{{ url('personal/update-deadline') }}" class="ui form" method="post"
          accept-charset="utf-8">
        @csrf
        <div class="field">
          <h5>Task title: <span class="text-primary">{{$task->title}}</span></h5>
          <p>Original deadline : {{$task->deadline}}</p>
        </div>
        <input type="number" name="task_id" value="{{$task->id}}"  hidden />
        <div class="field">
            <label for="">Reason</label>
            <textarea type="text" placeholder="Explain why you need to extend the deadline" name="reason"></textarea>
        </div>
        <div class="field">
            <label for="">New Deadline</label>
            <!-- <input type="text" placeholder="Date" autocomplete="off" name="deadline" class="airdatepicker" data-position="top right"/> -->
            <div class="form-group">

                <input type='text' id="datetimepicker" name="new_deadline" class="form-control" />

            </div>
        </div>


        <div class="actions">
            <input type="hidden" name="id" value="">
            <button class="ui positive small button" type="submit" name="submit"><i class="ui checkmark icon"></i>
                Submit
            </button>
        </div>
    </form>
  </div>

@endsection
@section('scripts')

<script src="{{ asset('/assets/vendor/datetimepicker/datetimepicker.js') }}"></script>

<script type="text/javascript">

  $('#datetimepicker').datetimepicker();
</script>

@endsection
