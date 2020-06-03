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
      <div class="col-6">
        <h2>Add Salary Type</h2>
        <p>Fill the form below to create new type</p>
        <form class="ui form" method="post" action="{{ url('admin/update_salary_types') }}">
          @csrf
          <input type="number" name="id" value="{{$salary_type->id}}" hidden>
          <div class="field">
            <label>Salary Type Name</label>
            <input type="text" name="type_name" placeholder="Type the salary type name. e.g: hourly, monthly" value="{{$salary_type->type}}" required>
          </div>


          <button class="ui button blue ml-2" type="submit"><i class="icon plus square outline"></i> Update</button>
        </form>
      </div>
    </div>
  </div>
</section>

@endsection

@section('scripts')

@endsection
