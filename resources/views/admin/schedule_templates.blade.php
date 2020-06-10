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
        <a href="{{ url('schedules/templates/create')}}" class="ui button blue float-right"><i class="ui icon plus square outline"></i> Create Template</a>
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
      
    </tr>
  </thead>
  <tbody>
    @isset($templates)
      @foreach ($templates as $t)
        <tr>
          <td>{{$t->name}}</td>
          <td>
            @if($t->saturday)
            {{$t->saturday}}
            @else
            N/A
            @endif
          </td>
          <td>
            @if($t->sunday)
            {{$t->sunday}}
            @else
            N/A
            @endif
          </td>
          <td>
            @if($t->monday)
            {{$t->monday}}
            @else
            N/A
            @endif
          </td>
          <td>
            @if($t->tuesday)
            {{$t->tuesday}}
            @else
            N/A
            @endif
          </td>
          <td>
            @if($t->wednesday)
            {{$t->wednesday}}
            @else
            N/A
            @endif
          </td>
          <td>
            @if($t->thursday)
            {{$t->thursday}}
            @else
            N/A
            @endif
          </td>
          <td>
            @if($t->friday)
            {{$t->friday}}
            @else
            N/A
            @endif
          </td>
          <td>{{$t->break_allowence}} Min</td>

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
