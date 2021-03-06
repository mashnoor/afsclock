@extends('layouts.default')

    @section('meta')
        <title>Schedules | Attendance Keeper</title>
        <meta name="description" content="Attendance Keeper schedules, view all employee schedules, add schedule or shift, edit, and delete schedules.">
    @endsection

    @section('styles')

    @endsection

    @section('content')

    <div class="template_form">
      <h1>Create Schedule Template</h1>
      <p>Fill up to the form below to create a schedule template.</p>

      <form id="add_schedule_form" action="{{ url('schedules/templates/add') }}" class="ui form my-5" method="post" accept-charset="utf-8">
          @csrf

          <div class="field">
              <h4>Template Name</h4>
              <input type="text" placeholder="Template Name" name="template_name" required>
          </div>

          <div class="field">
              <h4>Saturday</h4>
              <div class="row">
                <div class="col-6">
                  <label for="">Time In</label>
                  <input type="time" id="appt" name="sat_time_in">
                </div>
                <div class="col-6">
                  <label for="">Time Out</label>
                  <input type="time" id="appt" name="sat_time_out">
                </div>
              </div>
          </div>

          <div class="field">
              <h4>Sunday</h4>
              <div class="row">
                <div class="col-6">
                  <label for="">Time In</label>
                  <input type="time" id="appt" name="sun_time_in">
                </div>
                <div class="col-6">
                  <label for="">Time Out</label>
                  <input type="time" id="appt" name="sun_time_out">
                </div>
              </div>
          </div>

          <div class="field">
              <h4>Monday</h4>
              <div class="row">
                <div class="col-6">
                  <label for="">Time In </label>
                  <input type="time" id="appt" name="mon_time_in">
                </div>
                <div class="col-6">
                  <label for="">Time Out</label>
                  <input type="time" id="appt" name="mon_time_out">
                </div>
              </div>
          </div>

          <div class="field">
              <h4>Tuesday</h4>
              <div class="row">
                <div class="col-6">
                  <label for="">Time In</label>
                  <input type="time" id="appt" name="tue_time_in">
                </div>
                <div class="col-6">
                  <label for="">Time Out</label>
                  <input type="time" id="appt" name="tue_time_out">
                </div>
              </div>
          </div>

          <div class="field">
              <h4>Wednesday</h4>
              <div class="row">
                <div class="col-6">
                  <label for="">Time In</label>
                  <input type="time" id="appt" name="wed_time_in">
                </div>
                <div class="col-6">
                  <label for="">Time Out</label>
                  <input type="time" id="appt" name="wed_time_out">
                </div>
              </div>
          </div>

          <div class="field">
              <h4>Thursday</h4>
              <div class="row">
                <div class="col-6">
                  <label for="">Time In</label>
                  <input type="time" id="appt" name="thu_time_in">
                </div>
                <div class="col-6">
                  <label for="">Time Out</label>
                  <input type="time" id="appt" name="thu_time_out">
                </div>
              </div>
          </div>

          <div class="field">
              <h4>Friday</h4>
              <div class="row">
                <div class="col-6">
                  <label for="">Time In</label>
                  <input type="time" id="appt" name="fri_time_in">
                </div>
                <div class="col-6">
                  <label for="">Time Out</label>
                  <input type="time" id="appt" name="fri_time_out">
                </div>
              </div>
          </div>

          <div class="field">
            <h4>Break Allowence</h4>
            <input type="number" id="appt" name="break_allowence" required>
          </div>


        <div class="field">
          <button class="ui button green" type="submit" value="submit">Create</button>
        </div>
      </form>
    </div>


    @endsection

    @section('scripts')

    <script type="text/javascript">




    </script>
    @endsection
