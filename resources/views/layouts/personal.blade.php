<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <meta name="viewport" content="width=device-width"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    @yield('meta')

    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendor/semantic-ui/semantic.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendor/DataTables/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendor/semantic-ui/semantic.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/clock.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="{{ asset('/assets/vendor/html5shiv/html5shiv.min.js') }}></script>
            <script src=" {{ asset('/assets/vendor/respond/respond.min.js') }}"></script>
    <![endif]-->

    @yield('styles')
</head>
<body>

<div class="wrapper">

    <nav id="sidebar">
        <div class="sidebar-header bg-lightblue">
            <div class="logo">
                <a href="/" class="simple-text">
                    <img src="{{ asset('/assets/images/img/logo-small.png') }}">
                </a>
            </div>
        </div>

        <ul class="list-unstyled components">
            <li class="">
                <a href="{{ url('personal/dashboard') }}">
                    <i class="ui icon sliders horizontal"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="">
                <a href="{{ url('personal/attendance/view') }}">
                    <i class="ui icon clock outline"></i>
                    <p>My Attendances</p>
                </a>
            </li>
            <li class="">
                <a href="{{ url('personal/schedules/view') }}">
                    <i class="ui icon calendar alternate outline"></i>
                    <p>My Schedules</p>
                </a>
            </li>

            <li class="">
                <a href="{{ url('personal/tasks/manager') }}">
                    <i class="ui icon tasks"></i>
                    <p>My Tasks</p>
                </a>
            </li>

            <li class="">
                <a href="{{ url('personal/leaves/view') }}">
                    <i class="ui icon calendar plus outline"></i>
                    <p>My Leave</p>
                </a>
            </li>
            <li>
                <a href="{{ url('clock') }}" target="_blank" class="item">
                    <i class="ui icon clock outline"></i>
                    <p>Clock In/Out</p>
                </a>
            </li>
        </ul>
    </nav>

    <div id="body">
        <nav class="navbar navbar-expand-lg navbar-light bg-lightblue">
            <div class="container-fluid">

                <button type="button" id="slidesidebar" class="ui icon button btn-light-outline">
                    <i class="ui icon bars"></i> Menu
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="nav navbar-nav ml-auto navmenu">
                        <li class="nav-item">
                            <div class="ui pointing link dropdown item" tabindex="0">
                                <i class="ui icon linkify"></i> <span class="navmenutext">Quick Access</span>
                                <i class="dropdown icon"></i>
                                <div class="menu" tabindex="-1">
                                    <a href="{{ url('clock') }}" target="_blank" class="item"><i
                                                class="ui icon clock outline"></i> Clock In/Out</a>
                                    <div class="divider"></div>
                                    <a href="{{ url('personal/profile/view') }}" target="_blank" class="item"><i
                                                class="ui icon user outline"></i> My Profile</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="ui pointing link dropdown item" tabindex="0">
                                <i class="ui icon user outline"></i><span
                                        class="navmenutext">@isset(Auth::user()->name) {{ Auth::user()->name }} @endisset</span>
                                <i class="dropdown icon"></i>
                                <div class="menu" tabindex="-1">
                                    <a href="{{ url('personal/update-user') }}" class="item"><i
                                                class="ui icon user"></i> Update User</a>
                                    <a href="{{ url('personal/update-password') }}" class="item"><i
                                                class="ui icon lock"></i> Change Password</a>
                                    <div class="divider"></div>
                                    <a href="{{ url('logout') }}" class="item"><i class="ui icon power"></i> Logout</a>
                                </div>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>

        <div class="content">
            @yield('content')
        </div>

        <!-- <input type="hidden" id="_url" value="{{url('/')}}"> -->
        <script>
            //var y = '@isset($var){{$var}}@endisset';
            //var d = {"t":"Activation required!","b":"You need to activate this app otherwise you can't use some features.","a":"You only have 30 days trial to use this app."}
        </script>
    </div>
</div>

<div class="ui tiny modal reminderModal">
    <i class="close icon"></i>
    <div class="header">
        <h3>Hey Deadline is arriving soon for the following task..</h3>
    </div>
    <div class="content">

      <table class="table table-striped nobordertop">
          <thead>
          <tr>
              <th class="text-left">Title</th>
              <th class="text-left">Deadline</th>
          </tr>
          </thead>
          <tbody id="task_info_table">


          </tbody>
      </table>

    </div>
    <div class="actions">

        <div class="ui positive right labeled icon button">
            I'm aware
            <i class="checkmark icon"></i>
        </div>
    </div>
</div></td>

<audio id="bellSound">
  <source src="{{ asset('/assets/audio/bell.mp3') }}" type="audio/mpeg">
</audio>

<span id="_url" style="display: none;">{{url('/')}}</span>



<script src="{{ asset('/assets/vendor/jquery/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/semantic-ui/semantic.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/DataTables/datatables.min.js') }}"></script>
<script src="{{ asset('/assets/js/script.js') }}"></script>
<script src="{{ asset('/assets/vendor/momentjs/moment.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/momentjs/moment-timezone-with-data.js') }}"></script>
<script src="{{ asset('/assets/vendor/semantic-ui/semantic.min.js') }}"></script>
@if ($success = Session::get('success'))
    <script>
        $(document).ready(function () {
            $.notify({icon: 'ti-check', message: "{{ $success }}"}, {type: 'success', timer: 600});
        });
    </script>
@endif

@if ($error = Session::get('error'))
    <script>
        $(document).ready(function () {
            $.notify({icon: 'ti-close', message: "{{ $error }}"}, {type: 'danger', timer: 600});
        });
    </script>
@endif

@yield('scripts')

<script type="text/javascript">



function taskReminderMonitor(){
  var reference_id = {{Auth::user()->reference}};
  reference_id = parseInt(reference_id);
  if (reference_id) {

    var url = document.getElementById('_url').textContent;

    $.get( url + '/personal/dashboard/task_reminder', {reference_id:reference_id}, function(data){

      if (data != "") {


        var task_info_table = document.getElementById('task_info_table');



        task_info_table.innerHTML = "<tr><td>"+ data.title +"</td><td>"+ data.deadline +"</td></tr>";

        $(".reminderModal").modal('show');

        var bellSound = document.getElementById("bellSound");
        bellSound.play();
      }

    })

  }
}


setInterval(taskReminderMonitor, 15000);


</script>

</body>
</html>
