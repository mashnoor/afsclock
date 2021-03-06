@extends('layouts.default')

    @section('meta')
        <title>Reports | Attendance Keeper</title>
        <meta name="description" content="Attendance Keeper reports, view reports, and export or download reports.">
    @endsection

    @section('styles')
        <link href="{{ asset('/assets/vendor/air-datepicker/dist/css/datepicker.min.css') }}" rel="stylesheet">
    @endsection

    @section('content')

    <div class="container-fluid">
        <div class="row">
            <h2 class="page-title">Employee Attendance Report
                <a href="{{ url('reports') }}" class="ui basic blue button mini offsettop5 float-right"><i class="ui icon chevron left"></i>Return</a>
            </h2>
        </div>

        <div class="row">
            <div class="box box-success">
                <div class="box-body reportstable">
                    <form action="{{ url('export/report/attendance') }}" method="post" accept-charset="utf-8" class="ui small form form-filter" id="filterform">
                        {{ csrf_field() }}
                        <div class="inline three fields">
                            <!-- <div class="three wide field">
                                <select name="employee" class="ui search dropdown getid">
                                    <option value="">Employee</option>
                                    @isset($employee)
                                        @foreach($employee as $e)
                                            <option value="{{ $e->lastname }}, {{ $e->firstname }}" data-id="{{ $e->idno }}">{{ $e->lastname }}, {{ $e->firstname }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div> -->

                            <input id="EmployeeIDhiddenField" type="hidden" name="emp_id">

                            <div class="seven wide field">
                                <input id="smartsearch" type="text" name="smartsearch" value="" placeholder="Search anything" onkeyup="getEmployeeAttendance()" autocomplete="off">
                            </div>



                            <div class="two wide field">
                                <input id="datefrom" type="text" name="datefrom" value="" placeholder="Start Date" class="airdatepicker" autocomplete="off">
                                <i class="ui icon calendar alternate outline calendar-icon"></i>
                            </div>

                            <div class="two wide field">
                                <input id="dateto" type="text" name="dateto" value="" placeholder="End Date" class="airdatepicker" autocomplete="off">
                                <i class="ui icon calendar alternate outline calendar-icon"></i>
                            </div>

                            <!-- <input type="hidden" name="emp_id" value=""> -->
                            <a id="filterButton" class="ui icon button positive small inline-button" onclick="getEmployeeAttendance()"><i class="ui icon filter alternate"></i> Filter Data</a>

                            <!-- <button id="btnfilter" class="ui icon button positive small inline-button"><i class="ui icon filter alternate"></i> Filter</button> -->
                            <button type="submit" name="submit" class="ui icon button blue small inline-button"><i class="ui icon download"></i> Download</button>
                        </div>
                    </form>



                    <table width="100%" class="table table-striped table-hover" id="dataTables-example" data-order='[[ 0, "desc" ]]'>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Employee Name</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Total Hours</th>
                            </tr>
                        </thead>
                        <tbody id="report_tbody">
                            @isset($employeeAttendance)
                            @foreach ($employeeAttendance as $v)
                                <tr>
                                    <td>@isset($v->timein) @php echo e(date('d-m-Y', strtotime($v->timein))) @endphp @endisset</td>
                                    <td>{{ $v->employee }}</td>
                                    <td>@isset($v->timein)  @php echo e(date('h:i:s A', strtotime($v->timein))) @endphp @endisset</td>
                                    <td>@isset($v->timeout) @php echo e(date('h:i:s A', strtotime($v->timeout))) @endphp @endisset</td>
                                    <td>@isset($v->totalhours)
                                        @if($v->totalhours != null)
                                            @php
                                                if(stripos($v->totalhours, ".") === false) {
                                                    $h = $v->totalhours;
                                                } else {
                                                    $HM = explode('.', $v->totalhours);
                                                    $h = $HM[0];
                                                    $m = $HM[1];
                                                }
                                            @endphp
                                        @endif
                                        @if($v->totalhours != null)
                                            @if(stripos($v->totalhours, ".") === false)
                                                {{ $h }} hr
                                            @else
                                                {{ $h }} hr {{ $m }} mins
                                            @endif
                                        @endif
                                    @endisset</td>
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
    <script src="{{ asset('/assets/vendor/air-datepicker/dist/js/datepicker.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/air-datepicker/dist/js/i18n/datepicker.en.js') }}"></script>

    <script type="text/javascript">


    $('#dataTables-example').DataTable({responsive: true,pageLength: 15,lengthChange: false,searching: false,ordering: true});

    $('.airdatepicker').datepicker({ language: 'en', dateFormat: 'yyyy-mm-dd' });

    // transfer idno
    $('.ui.dropdown.getid').dropdown({ onChange: function(value, text, $selectedItem) {
        $('select[name="employee"] option').each(function() {
            if($(this).val()==value) {var id = $(this).attr('data-id');$('input[name="emp_id"]').val(id);};
        });
    }});

    $('#btnfilterNone').click(function(event) {
        event.preventDefault();
        var emp_id = $('input[name="emp_id"]').val();
        var date_from = $('#datefrom').val();
        var date_to = $('#dateto').val();
        var url = $("#_url").val();
        var gtr = 0;

        $.ajax({
            url: url + '/get/employee-attendance/', type: 'get', dataType: 'json', data: {id: emp_id, datefrom: date_from, dateto: date_to}, headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                showdata(response);
                function showdata(jsonresponse) {
                    var employee = jsonresponse;
                    var tbody = $('#dataTables-example tbody');

                    // clear data and destroy datatable
                    $('#dataTables-example').DataTable().destroy();
                    tbody.children('tr').remove();

                    // append table row data
                    for (var i = 0; i < employee.length; i++) {
                        gtr += +employee[i].totalhours;
                        var time_in = employee[i].timein;
                        var t_in = time_in.split(" ");
                        var time_out = employee[i].timeout;
                        var t_out = time_out.split(" ");

                        tbody.append("<tr>"+
                                        "<td>"+employee[i].date+"</td>" +
                                        "<td>"+employee[i].employee+"</td>" +
                                        "<td>"+ t_in[1]+" "+t_in[2] +"</td>" +
                                        "<td>"+ t_out[1]+" "+t_out[2] +"</td>" +
                                        "<td>"+employee[i].totalhours+"</td>" +
                                    "</tr>");
                    }

                    tbody.append("<tr class='tablefooter'>"+
                        "<td colspan='4'><strong>TOTAL HOURS</strong></td>"+
                        "<td><strong>"+gtr.toFixed(2)+"</strong></td>"+
                        "<td class='hide'></td>"+
                        "<td class='hide'></td>"+
                        "<td class='hide'></td>"+
                        "<td class='hide'></td>"+
                        "<td class='hide'></td>"+
                    "</tr>");

                    // initialize datatable
                    $('#dataTables-example').DataTable({responsive: true,pageLength: 15,lengthChange: false,searching: false,ordering: false});
                }
            }
        })
    });



    // Finds employee attendance for specific row.
    function getEmployeeAttendance() {

          var smartSearchFieldValue = document.getElementById('smartsearch').value;
          var datefrom = document.getElementById('datefrom').value;
          var dateto = document.getElementById('dateto').value;

          var url = document.getElementById('_url').textContent;

          var report_tbody = document.getElementById('report_tbody');



        $.get(url + '/get/employee-report/search', {searchContent: smartSearchFieldValue, datefrom:datefrom, dateto:dateto}, function(data){

          report_tbody.innerHTML = "";

          if (data[0]) {
            document.getElementById("EmployeeIDhiddenField").value = data[0].reference

          }

          console.log(data);

          for (var i = 0; i < data.length; i++) {
            var total_working_hour = data[i].totalhours;
            var hr_array = total_working_hour.split(".");

            report_tbody.innerHTML += "<tr><td>"+ data[i].timein +"</td><td>"+ data[i].employee +"</td><td>"+ data[i].timein +"</td><td>"+ data[i].timein +"</td><td>"+hr_array[0]+" hr " + hr_array[1]+ "mins" + "</td></tr>";
          }

        })
    }




    </script>
    @endsection
