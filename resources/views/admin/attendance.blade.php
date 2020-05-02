@extends('layouts.default')

@section('meta')
    <title>Attendances | Attendance Keeper</title>
    <meta name="description"
          content="Attendance Keeper attendance, view all employee attendances, clock-in, edit, and delete attendances.">
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row">
            <h2 class="page-title">Attendances
                <a href="{{ url('clock') }}" class="ui positive button mini offsettop5 float-right"><i
                            class="ui icon clock"></i>Clock In/Out</a>
            </h2>
        </div>

        <div class="row">
            <div class="box box-success">
                <div class="box-body">
                    <table width="100%" class="table table-striped table-hover" id="dataTables-example"
                           data-order='[[ 0, "desc" ]]'>
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Employee</th>
                            <th>Time In</th>
                            <th>Time Out</th>
{{--                            <th>Break In</th>--}}
{{--                            <th>Break Out</th>--}}
                            <th>Total Hours</th>
                            <th>Break Duration</th>
                            <th>Note (In / Out)</th>
                            @isset($cc)
                                @if($cc == 1)
                                    <th>Comment</th>
                                @endif
                            @endisset
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($data)
                            @foreach ($data as $d)
                                <tr>
                                    <td>@isset($d->created_at) @php echo e(date('d-m-Y', strtotime($d->created_at))) @endphp @endisset</td>
                                    <td>{{ $d->employee }}</td>
                                    <td>@php $IN = date('h:i:s A', strtotime($d->created_at)); echo $IN; @endphp</td>
                                    <td>
                                        @isset($d->updated_at)
                                            @php
                                                $OUT = date('h:i:s A', strtotime($d->updated_at));
                                            @endphp
                                            @if($d->updated_at != NULL)
                                                {{ $OUT }}
                                            @endif
                                        @endisset
                                    </td>
{{--                                    <td>--}}
{{--                                        @isset($d->break_in)--}}
{{--                                            @php $break_in_time = date('h:i:s A', strtotime($d->break_in)); @endphp--}}
{{--                                            {{ $break_in_time }}--}}
{{--                                        @endisset--}}

{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        @isset($d->break_out)--}}
{{--                                            @php $break_out_time = date('h:i:s A', strtotime($d->break_out)); @endphp--}}
{{--                                            {{ $break_out_time }}--}}
{{--                                        @endisset--}}

{{--                                    </td>--}}
                                    <td>
                                        @isset($d->totalhours)
                                            @if($d->totalhours != null)
                                                @php
                                                    if(stripos($d->totalhours, ".") === false) {
                                                        $h = $d->totalhours;
                                                    } else {
                                                        $HM = explode('.', $d->totalhours);
                                                        $h = $HM[0];
                                                        $m = $HM[1];
                                                    }
                                                @endphp
                                            @endif
                                            @if($d->totalhours != null)
                                                @if(stripos($d->totalhours, ".") === false)
                                                    {{ $h }} hr
                                                @else
                                                    {{ $h }} hr {{ $m }} mins
                                                @endif
                                            @endif
                                        @endisset
                                    </td>
                                    <td>
                                        @isset($d->total_break_hours)
                                            @if($d->total_break_hours != null)
                                                @php
                                                    if(stripos($d->total_break_hours, ".") === false) {
                                                        $h = $d->total_break_hours;
                                                    } else {
                                                        $HM = explode('.', $d->total_break_hours);
                                                        $h = $HM[0];
                                                        $m = $HM[1];
                                                    }
                                                @endphp
                                            @endif
                                            @if($d->total_break_hours != null)
                                                @if(stripos($d->total_break_hours, ".") === false)
                                                    {{ $h }} hr
                                                @else
                                                    {{ $h }} hr {{ $m }} mins
                                                @endif
                                            @endif
                                        @endisset
                                    </td>
                                    <td>
                                        @if($d->status_timein != null OR $d->status_timeout != null)
                                            <span class="@if($d->status_timein == 'Late Arrival') orange @else blue @endif">{{ $d->status_timein }}</span>
                                            /
                                            @isset($d->status_timeout)
                                                <span class="@if($d->status_timeout == 'Early Departure') red @else green @endif">
                                                {{ $d->status_timeout }}
                                            </span>
                                            @endisset
                                        @else
                                            <span class="blue">{{ $d->status_timein }}</span>
                                        @endif
                                    </td>

                                    @isset($cc)
                                        @if($cc == 1)
                                            <td>{{ $d->comment }}</td>
                                        @endif
                                    @endisset
                                    <td class="align-right">
                                        <a href="{{ url('/attendance/edit/'.$d->id) }}"
                                           class="ui circular basic icon button tiny"><i class="edit outline icon"></i></a>
                                        <a href="{{ url('/attendance/delete/'.$d->id) }}"
                                           class="ui circular basic icon button tiny"><i
                                                    class="trash alternate outline icon"></i></a>
                                    <button class="ui button yellow create_btn" type="button" id="attendance-details" onclick="getAttendanceDetails('{{$d->id}}')">Details</button>
                                        <div class="ui modal test">
                                            <i class="close icon"></i>
                                            <div class="header">
                                                <h3>Attendance Details</h3>

                                            </div>
                                            <div class="content">
                                                <div class="ui two column grid">
                                                  <div class="row">
                                                    <div class="column">
                                                      <h5>Employee Name : {{ $d->employee }}</h5>
                                                      <h5>Date : @isset($d->created_at) @php echo e(date('d-m-Y', strtotime($d->created_at))) @endphp @endisset</h5>
                                                    </div>

                                                  </div>

                                                    <div class="row">
                                                        <div class="column">
                                                            <h3>Clock In/Out</h3>
                                                            <table width="100%" class="table table-bordered table-hover" id="dataTables-example" data-order='[[ 0, "desc" ]]'>
                                                                <thead>
                                                                <tr>
                                                                    <th>In</th>
                                                                    <th>Out</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody id="entry_tbody">

                                                                </tbody>

                                                            </table>
                                                        </div>
                                                        <div class="column">
                                                            <h3>Break In/Out</h3>
                                                            <table width="100%" class="table table-bordered table-hover" id="dataTables-example" data-order='[[ 0, "desc" ]]'>
                                                                <thead>
                                                                <tr>
                                                                    <th>In</th>
                                                                    <th>Out</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody id="break_tbody">

                                                                </tbody>

                                                            </table>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="actions">

                                                <div class="ui positive right labeled icon button">
                                                    Done
                                                    <i class="checkmark icon"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endisset
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <span id="_url" style="display: none;">{{url('/')}}</span>

@endsection

@section('scripts')
    <script type="text/javascript">
        $('#dataTables-example').DataTable({
            responsive: true,
            pageLength: 15,
            lengthChange: false,
            searching: true,
            ordering: true
        });


        // Attendance Details modal
        $(function(){
            $("#attendance-details").click(function(e){
                $(".test").modal('show');
            });
            $(".test").modal({
                closable: true
            });

        });



        // Finds attendance details for specific row.
        function getAttendanceDetails(attendanceID) {

            console.log("Inside the get attendance details. With the parameter " + attendanceID );

            var EntryTbody = document.getElementById("entry_tbody");
            var BreakTbody = document.getElementById("break_tbody");

            var url = document.getElementById('_url').textContent;


            $.get(url+'/personal/attendance/details', { attendanceID: attendanceID }, function(data){

                EntryTbody.innerHTML = "";
                BreakTbody.innerHTML = "";

                var entries = data[0];
                var breaks = data[1];

                for(i = 0; i <= entries.length; i++){
                    if(entries[i]){



                        EntryTbody.innerHTML += "<tr>" +
                            "<td>" + entries[i].start_at + "</td>" +
                            "<td>" + entries[i].end_at + "</td>"
                            +"</tr>";
                    }

                }


                for(i = 0; i <= breaks.length; i++){
                    if(breaks[i]){
                        BreakTbody.innerHTML += "<tr>" +
                            "<td>"+ breaks[i].start_at +"</td>" +
                            "<td>" + breaks[i].end_at + "</td>"
                            +"</tr>";
                    }
                }


            })


        }


    </script>
@endsection
