@extends('layouts.default')

@section('meta')
    <title>Attendances | Attendance Keeper</title>
    <meta name="description"
          content="Attendance Keeper attendance, view all employee attendances, clock-in, edit, and delete attendances.">
@endsection

@section('content')

<h1>Employee Salary Page</h1>

<section>
  <div class="container">
    <div class="row">
      <div class="col-5">
        <form class="ui form" method="post" action="{{ url('admin/add_employee_salary')}}">
          @csrf
          <div class="field">
            <h4>Employee</h4>
            <select class="ui search dropdown getid uppercase" name="employee_id">
                <option value="">Select Employee</option>
                @isset($employee)
                    @foreach ($employee as $data)
                        <option value="{{ $data->id }}" data-id="{{ $data->id }}">{{ $data->lastname }}, {{ $data->firstname }}</option>
                    @endforeach
                @endisset
            </select>
          </div>
          <div class="field">
            <h4>Salary Type</h4>
            <select class="ui search dropdown getid uppercase" name="salary_type">
                <option value="">Select Salary Type</option>
                @isset($salary_types)
                    @foreach ($salary_types as $data)
                        <option value="{{ $data->id }}" data-id="{{ $data->id }}">{{ $data->type }}</option>
                    @endforeach
                @endisset
            </select>
          </div>
          <div class="field">
            <h4>Currency</h4>
            <select class="ui search dropdown getid uppercase" name="currency">
                <option value="">Select Currency Type</option>

                        <option value="bdt" data-id="bdt">BDT</option>
                        <option value="usd" data-id="usd">USD</option>

            </select>
          </div>
          <div class="field">
            <h4>Amount</h4>
            <input type="number" name="salary_amount" placeholder="Type salary amount">
          </div>

          <button class="ui button blue ml-2" type="submit"><i class="plus square outline icon"></i> Add Salary</button>
        </form>
      </div>
      <div class="col-7">
        <table class="ui celled table">
          <thead>
            <tr><th>Employee</th>
            <th>Salary Type</th>
            <th>Amount</th>
            <th>Actions</th>
          </tr></thead>
          <tbody>
            @isset($salary_collection)
              @foreach($salary_collection as $employee_salary)
                <tr>
                  <td data-label="Employee">{{$employee_salary->employee}}</td>
                  <td data-label="Salary Type">{{$employee_salary->salary_type}}</td>
                  <td data-label="Amount">{{$employee_salary->amount}} <span class="text-uppercase">{{$employee_salary->currency}}</span></td>
                  <td data-label="Actions">
                    <a href="{{ url('admin/salary_types') }}" class="ui circular basic icon button tiny"><i class="icon edit outline"></i></a>
                    <a href="{{ url('admin/delete_salary_type/') }}" class="ui circular basic icon button tiny"><i class="icon trash "></i></a>
                  </td>
                </tr>
              @endforeach
            @endisset
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

@endsection

@section('scripts')

@endsection
