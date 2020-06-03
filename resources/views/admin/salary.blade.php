@extends('layouts.default')

@section('meta')
    <title>Attendances | Attendance Keeper</title>
    <meta name="description"
          content="Attendance Keeper attendance, view all employee attendances, clock-in, edit, and delete attendances.">
@endsection

@section('content')

<section>
  <div class="container text-right">
    <a class="ui button tiny blue" href="{{ url('admin/salary_types') }}"><i class="ui icon th list"></i>Salary Types</a>
    <a class="ui button tiny blue" href="{{ url('admin/employee_salary') }}"><i class="ui icon money bill alternate outline"></i>Employee Salary</a>
    <a class="ui button tiny blue" href="{{ url('admin/holidays') }}"><i class="ui icon flag"></i>Holidays</a>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <h2>Salary Calculation</h2>
    <p>Choose the month and year to calculate salary</p>
    <div class="row py-5">
      <div class="col-6 py-5">
        <form class="ui form" method="post" action="{{ url('admin/calculate_salary')}}">
          @csrf
          <div class="field">
            <h4>Month</h4>
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

          <div class="field">
            <h4>Year</h4>
            <select class="ui search dropdown getid uppercase" name="year">
                <option value="2020" data-id="2020">2020</option>
            </select>
          </div>


          <button class="ui button blue ml-2" type="submit"><i class="calculator icon"></i> Calculate Salary</button>
        </form>
      </div>
      <div class="col-6"></div>
    </div>
  </div>
</section>

@endsection

@section('scripts')

@endsection
