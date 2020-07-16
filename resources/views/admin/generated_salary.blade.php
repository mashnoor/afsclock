@extends('layouts.default')

@section('meta')
    <title>Attendances | Attendance Keeper</title>
    <meta name="description"
          content="Attendance Keeper attendance, view all employee attendances, clock-in, edit, and delete attendances.">
@endsection

@section('content')

<section class="py-3">
  <div class="container text-center">
    <h2 class="m-0">Salary Report</h3>
    <h5 class="m-0 py-2">Month: <span>{{$monthName}}</span></h4>
    <h5 class="m-0">Year: <span>{{$year}}</span></h4>
  </div>
</section>


<section>
  <div class="container">
    <h2>Monthly Salaries</h2>
    <table class="ui celled table">
  <thead>
    <tr><th>ID</th>
    <th>Name</th>
    <th>Salary Type</th>
    <th>Monthly Salary</th>
    <th>Working Days</th>
    <th>Calculated Salary</th>
  </tr></thead>
  <tbody>
    @isset($salary_collection)
    @foreach($salary_collection as $salary)
    <tr>
      <td data-label="id">{{ $salary->id }}</td>
      <td data-label="Age">{{ $salary->employee }}</td>
      <td data-label="Job">Monthly</td>
      <td data-label="id">{{ $salary->gross_salary }}</td>
      <td data-label="Age">{{ $salary->office_days }}</td>
      <td data-label="Job">{{ $salary->calculated_salary}} <span class="text-uppercase">{{ $salary->currency}}</span></td>
    </tr>
    @endforeach
    @endisset
  </tbody>
</table>
  </div>
</section>

<section class="mt-5">
  <div class="container">
    <h2>Hourly Salaries</h2>
    <table class="ui celled table">
  <thead>
    <tr><th>ID</th>
    <th>Name</th>
    <th>Salary Type</th>
    <th>Total Hours</th>
    <th>Hourly Salary</th>
    <th>Working Days</th>
    <th>Calculated Salary</th>
  </tr></thead>
  <tbody>
    @isset($hourly_salary_collection)
    @foreach($hourly_salary_collection as $salary)
    <tr>
      <td data-label="id">{{ $salary->id }}</td>
      <td data-label="Age">{{ $salary->employee }}</td>
      <td data-label="Job">Hourly</td>
      <td>
        @isset($salary->total_hours)
            @if($salary->total_hours != null)
                @php
                    if(stripos($salary->total_hours, ".") === false) {
                        $h = $salary->total_hours;
                    } else {
                        $HM = explode('.', $salary->total_hours);
                        $h = $HM[0];
                        $m = $HM[1];
                    }
                @endphp
            @endif
            @if($salary->total_hours != null)
                @if(stripos($salary->total_hours, ".") === false)
                    {{ $h }} hr
                @else
                    {{ $h }} hr {{ $m }} minutes
                @endif
            @endif
        @endisset

      </td>
      <td data-label="id">{{ $salary->gross_salary }}</td>
      <td data-label="Age">{{ $salary->office_days }}</td>
      <td data-label="Job">{{ $salary->calculated_salary}} <span class="text-uppercase">{{ $salary->currency}}</span></td>
    </tr>
    @endforeach
    @endisset
  </tbody>
</table>
  </div>
</section>


@endsection

@section('scripts')

@endsection
