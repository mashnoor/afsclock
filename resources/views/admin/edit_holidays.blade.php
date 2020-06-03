@extends('layouts.default')

@section('meta')
    <title>Attendances | Attendance Keeper</title>
    <meta name="description"
          content="Attendance Keeper attendance, view all employee attendances, clock-in, edit, and delete attendances.">
@endsection

@section('content')

<section>
  <div class="container">
    <div class="row">
      <div class="col-6 pr-5">
        <h2>Add new holidays</h2>
        <form method="post" action="{{ url('admin/add_holidays') }}">
          @csrf
        <div class="field">
          <h4>Salary Type</h4>
          <select class="ui search dropdown getid uppercase" name="month">
              <option value="1" data-id="JAN">January</option>
              <option value="2" data-id="FEB">February</option>
              <option value="3" data-id="MAR">March</option>
              <option value="4" data-id="JAN">April</option>
              <option value="5" data-id="JAN">May</option>
              <option value="6" data-id="JUN">June</option>
              <option value="7" data-id="JUL">July</option>
              <option value="8" data-id="AUG">August</option>
              <option value="9" data-id="SEP">September</option>
              <option value="10" data-id="OCT">October</option>
              <option value="11" data-id="NOV">November</option>
              <option value="12" data-id="DEC">December</option>
          </select>
        </div>
        <div class="field mt-4">
          <h4>Bootstrap Multi Select Date Picker</h4>
        	<input type="text" class="form-control date ui bg-light" name="dates" placeholder="Pick the multiple dates">
        </div>
        <button class="ui button blue ml-2 mt-3" type="submit"><i class="plus square outline icon"></i> Add Holidays</button>
      </form>
      </div>
    </div>
  </div>
</section>

@endsection

@section('scripts')

@endsection
