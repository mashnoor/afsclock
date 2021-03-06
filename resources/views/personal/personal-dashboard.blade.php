@extends('layouts.personal')

@section('meta')
    <title>My Dashboard | Attendance Keeper</title>
    <meta name="description"
          content="Attendance Keeper my dashboard, view recent attendance, view recent leave of absence, and view previous schedules.">
@endsection

@section('content')


    <div class="container-fluid">
        <!---
              <div class="row">


                  <div class="col-md-4">
                      <div class="box box-success">
                          <div class="box-header with-border">
                              <h3 class="box-title">Recent Attendances</h3>
                          </div>
                          <div class="box-body">
                              Hello
                          </div>
                      </div>
                  </div>

                  <div class="col-md-4">
                      <div class="box box-success">
                          <div class="box-body center">
                              <h3>Current Status:</h3>
                              <a href="#" class="btn btn-success btn-md active" role="button" aria-pressed="true">Clock In</a>
                              <a href="#" class="btn btn-danger btn-md active" role="button" aria-pressed="true">Clock Out</a>

                          </div>
                      </div>
                  </div>

                  <div class="col-md-4">
                      <div class="box box-success">

                          <div class="box-body">
                              <div class="fixedcenter">
                                  <div class="clockwrapper">
                                      <div class="timeclock">
                                          <span id="show_day" class="clock-text"></span>
                                          <span id="show_time" class="clock-time"></span>
                                          <span id="show_date" class="clock-day"></span>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>


              </div>
      --->

        <div class="row">
            <div class="col-md-12">
                <h2 class="page-title">Dashboard</h2>

            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-paste"><i class="ui icon clock outline"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">ATTENDANCE <span class="text-hint">(Current Month)</span> </span>
                        <div class="progress-group">
                            <div class="progress sm">
                                <div class="progress-bar progress-bar-paste" style="width: 100%"></div>
                            </div>
                            <div class="stats_d">
                                <table style="width: 100%;">
                                    <tbody>
                                    <tr>
                                        <td>Late Arrivals</td>
                                        <td><span class="bolder">@isset($la) {{ $la }} @endisset</span></td>
                                    </tr>
                                    <tr>
                                        <td>Early Departures</td>
                                        <td><span class="bolder">@isset($ed) {{ $ed }} @endisset</span></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">

                <div class="info-box">
                    <span class="info-box-icon bg-ash"><i class="ui icon user circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Present Schedule</span>
                        <div class="progress-group">
                            <div class="progress sm">
                                <div class="progress-bar progress-bar-ash" style="width: 100%"></div>
                            </div>
                            <div class="stats_d">
                                <table style="width: 100%;">
                                    <tbody>
                                    <tr>
                                        <td>Time</td>
                                        <td>
                                            <span class="bolder">@isset($cs->intime) {{ $cs->intime }} @endisset
                                                - @isset($cs->outime) {{ $cs->outime }} @endisset</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Rest Days</td>
                                        <td>
                                            <span class="bolder">@isset($cs->restday) {{ $cs->restday }} @endisset</span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-orange"><i class="ui icon home"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">MY TASKS</span>
                        <div class="progress-group">
                            <div class="progress sm">
                                <div class="progress-bar progress-bar-orange" style="width: 100%"></div>
                            </div>
                            <div class="stats_d">
                                <table style="width: 100%;">
                                    <tbody>
                                    <tr>
                                        <td>Done</td>
                                        <td><span class="bolder">@isset($no_of_done_tasks){{ $no_of_done_tasks }}@endisset</span></td>
                                    </tr>
                                    <tr class="text-danger">
                                        <td>Pending</td>
                                        <td><span class="bolder " id="pending_task_count">@isset($no_of_pending_tasks){{ $no_of_pending_tasks }}@endisset</span></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-4">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Recent Attendances</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table table-striped nobordertop">
                            <thead>
                            <tr>
                                <th class="text-left">Date</th>
                                <th class="text-left">Time</th>
                                <th class="text-left">Description</th>
                            </tr>
                            </thead>
                            <tbody>
                            @isset($sortedActivities)
                                @foreach($sortedActivities as $v)

                                    <tr>
                                      <td>@php echo e(date('M d, Y', strtotime($v->datetime))); @endphp</td>
                                      <td>@php echo e(date('h:i:s A', strtotime($v->datetime))); @endphp</td>
                                      <td>{{$v->label}}</td>
                                    </tr>

                                @endforeach
                            @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
              <div class="col bg-light box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Task Deadline Tracker</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                    class="fa fa-times"></i></button>
                    </div>
                </div>
                <table class="table responsive nobordertop">
                    <thead>
                    <tr>
                        <th class="text-left">Employee Name</th>
                        <th class="text-left">Original Deadline</th>
                        <th class="text-left">New Deadline</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @isset($task_collection)
                        @foreach ($task_collection as $data)
                            <tr>
                                <td class="text-left name-title">{{ $data->assigned_to }}
                                    </td>
                                <td class="text-left">@php echo e(date('M d, Y', strtotime($data->original_deadline))) @endphp</td>
                                <td class="text-left">@php echo e(date('M d, Y', strtotime($data->deadline))) @endphp</td>
                                <td><a href="{{ url('personal/tassk/details/'.$data->id) }}" class="ui circular basic icon button tiny"><i class="icon align justify "></i></a></td>
                            </tr>
                        @endforeach
                    @endisset
                    </tbody>
                </table>
              </div>
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Previous Schedules</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table table-striped nobordertop">
                            <thead>
                            <tr>
                                <th class="text-left">Time</th>
                                <th class="text-left">From Date / Until</th>
                            </tr>
                            </thead>
                            <tbody>
                            @isset($ps)
                                @foreach($ps as $s)
                                    <tr>
                                        <td>{{ $s->intime }} - {{ $s->outime }}</td>
                                        <td>
                                            @php
                                                $date4 = date('M d',strtotime($s->datefrom)).' - '.date('M d, Y',strtotime($s->dateto));
                                            @endphp
                                            {{ $date4 }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Latest pending tasks</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table table-striped nobordertop">
                            <thead>
                            <tr>
                                <th class="text-left">Title</th>
                                <th class="text-left">Deadline</th>
                            </tr>
                            </thead>
                            <tbody>
                            @isset($tasks)
                                @foreach($tasks as $task)
                                    <tr>
                                      <td onload="pending_task_reminder()">{{ $task->title }}</td>
                                        <td>{{ $task->deadline }}</td>
                                    </tr>
                                @endforeach
                            @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>





    <div class="ui tiny modal pendingTask">
        <i class="close icon"></i>
        <div class="header">
            <h3>You have {{ count($pending_tasks)}} pending tasks..</h3>
        </div>
        <div class="content">

          <table class="table table-striped nobordertop">
              <thead>
              <tr>
                  <th class="text-left">Title</th>
                  <th class="text-left">Deadline</th>
              </tr>
              </thead>
              <tbody>
              @isset($pending_tasks)
                  @foreach($pending_tasks as $task)
                      <tr>
                        <td>{{ $task->title }}</td>
                          <td>{{ $task->deadline }}</td>
                      </tr>
                  @endforeach
              @endisset
              </tbody>
          </table>

        </div>
        <div class="actions">

            <div class="ui positive right labeled icon button">
                I'm aware
                <i class="checkmark icon"></i>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <!-- <script type="text/javascript">
        var timezone = "@isset($tz){{ $tz }}@endisset";
        var elTime = document.getElementById('show_time');
        var elDate = document.getElementById('show_date');
        var elDay = document.getElementById('show_day');

        // time function to prevent the 1s delay
        var setTime = function () {
            // initialize clock with timezone
            var time = moment().tz(timezone);

            // set time in html
            elTime.innerHTML = time.format("hh:mm:ss A");

            // set date in html
            elDate.innerHTML = time.format('MMMM D, YYYY');

            // set day in html
            elDay.innerHTML = time.format('dddd');
        }

        setTime();
        setInterval(setTime, 1000);

    </script> -->
    <script>




    // The pending tasks modal. Only shows up if there exists pending tasks.
    $(document).ready(function(){
      var pCount = document.getElementById("pending_task_count").textContent;
      var pCountInt = parseInt(pCount);
      if(pCountInt){
        $(".pendingTask").modal('show');
      }
    });

    </script>
@endsection
