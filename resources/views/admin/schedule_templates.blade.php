@extends('layouts.default')

    @section('meta')
        <title>Schedules | Attendance Keeper</title>
        <meta name="description" content="Attendance Keeper schedules, view all employee schedules, add schedule or shift, edit, and delete schedules.">
    @endsection

    @section('styles')

    @endsection

    @section('content')



    <div class="row">
      <div class="col-8">
        <h1>Schedule Templates</h1>
        <p>Manage existing schedule and create new templates.</p>
      </div>
      <div class="col-4">
        <a href="/schedules/templates/create" class="ui button blue float-right"><i class="ui icon plus square outline"></i> Create Template</a>
      </div>
    </div>

    <div class="templates my-5">
      <table class="ui table">
  <thead>
    <tr>
      <th>Name</th>
      <th>Sat</th>
      <th>Sun</th>
      <th>Mon</th>
      <th>Tue</th>
      <th>Wed</th>
      <th>Thu</th>
      <th>Fri</th>
      <th>Break</th>
      <th>Restday</th>
    </tr>
  </thead>
  <tbody>
    @isset($templates)
      @foreach ($templates as $t)
        <tr>
          <td>{{$t->name}}</td>
          <td>{{$t->saturday}}</td>
          <td>{{$t->sunday}}</td>
          <td>{{$t->monday}}</td>
          <td>{{$t->tuesday}}</td>
          <td>{{$t->wednesday}}</td>
          <td>{{$t->thursday}}</td>
          <td>{{$t->friday}}</td>
          <td>{{$t->break_allowence}} Min</td>
          <td>{{$t->restdays}}</td>
        </tr>
      @endforeach
    @endisset
  </tbody>
</table>
    </div>

    @endsection

    @section('scripts')


    <script type="text/javascript">


    </script>
    @endsection
