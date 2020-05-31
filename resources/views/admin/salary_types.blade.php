@extends('layouts.default')

@section('meta')
    <title>Attendances | Attendance Keeper</title>
    <meta name="description"
          content="Attendance Keeper attendance, view all employee attendances, clock-in, edit, and delete attendances.">
@endsection

@section('content')

<h1>This is salary types page</h1>
<section class="py-5">
  <div class="container">
    <div class="row">
      <div class="col-6 pr-5">
        <h2>Add Salary Type</h2>
        <p>Fill the form below to create new type</p>
        <form class="ui form" method="post" action="{{ url('admin/add_salary_types') }}">
          @csrf
          <div class="field">
            <label>Salary Type Name</label>
            <input type="text" name="type_name" placeholder="Type the salary type name. e.g: hourly, monthly">
          </div>


          <button class="ui button blue ml-2" type="submit"><i class="icon plus square outline"></i> Create</button>
        </form>
      </div>
      <div class="col-6">
        <h2>Salary Types</h2>
        <table class="ui celled table">
          <thead>
            <tr><th>Type</th>
            <th>Actions</th>

          </tr></thead>
          <tbody>
            @isset($salary_types)
              @foreach($salary_types as $salary_type)
              <tr>
                <td data-label="Name">{{$salary_type->type}}</td>
                <td data-label="Age">
                  <a href="{{ url('admin/salary_types') }}" class="ui circular basic icon button tiny"><i class="icon edit outline"></i></a>
                  <a href="{{ url('admin/delete_salary_type/'.$salary_type->id) }}" class="ui circular basic icon button tiny"><i class="icon trash "></i></a>
                </td>
              </tr>
              @endforeach
            @endisset
          </tbody>
        </table>

      </div>
    <div>
  </div>
</section>
@endsection

@section('scripts')

@endsection
