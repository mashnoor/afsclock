@extends('layouts.default')

@section('meta')
    <title>Attendances | Attendance Keeper</title>
    <meta name="description"
          content="Attendance Keeper attendance, view all employee attendances, clock-in, edit, and delete attendances.">
@endsection
@section('styles')
    <link href="{{ asset('/assets/vendor/air-datepicker/dist/css/datepicker.min.css') }}" rel="stylesheet">
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
              <input id="datefrom" type="text" name="datefrom" value="" placeholder="Start Date" class="airdatepicker" autocomplete="off">
              <i class="ui icon calendar alternate outline calendar-icon"></i>
          </div>

          <div class="field">
              <input id="dateto" type="text" name="dateto" value="" placeholder="End Date" class="airdatepicker" autocomplete="off">
              <i class="ui icon calendar alternate outline calendar-icon"></i>
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
<script src="{{ asset('/assets/vendor/air-datepicker/dist/js/datepicker.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/air-datepicker/dist/js/i18n/datepicker.en.js') }}"></script>

<script type="text/javascript">
  $('.airdatepicker').datepicker({ language: 'en', dateFormat: 'yyyy-mm-dd' });
</script>

@endsection
