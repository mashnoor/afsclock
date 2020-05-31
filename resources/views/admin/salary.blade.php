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

@endsection

@section('scripts')

@endsection
