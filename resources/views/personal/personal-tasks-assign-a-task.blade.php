@extends('layouts.personal')

@section('meta')
    <title>Task Manager | Attendance Keeper</title>
    <meta name="description"
          content="attendance keeper schedules, view all employee schedules, add schedule or shift, edit, and delete schedules.">
@endsection

@section('styles')
    <link href="{{ asset('/assets/vendor/mdtimepicker/mdtimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/vendor/air-datepicker/dist/css/datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/vendor/datetimepicker/datetimepicker.css') }}" rel="stylesheet">

    <style>
        /* .ui.active.modal {position: relative !important;} */
        .datepicker {
            z-index: 999 !important;
        }

        .datepickers-container {
            z-index: 9999 !important;
        }
    </style>
@endsection

@section('content')
    @include('personal.modals.modal-add-task')

    <div class="container-fluid">
        <div class="row">
            <h2 class="page-title">Tasks
                <button class="ui positive button mini offsettop5 btn-add float-right"><i class="ui icon plus"></i>Add
                </button>
            </h2>
        </div>

        <div class="row">
            <div class="box box-success">
                <div class="box-body">
                    <table width="100%" class="table table-striped table-hover" id="dataTables-example"
                           data-order='[[ 6, "asc" ]]'>
                        <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Title</th>
                            <th>Deadline</th>
                            <th>Finish Date</th>
                            <th>Status</th>
                            <th>Comment</th>
                            <th class="align-right">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($tasks)
                            @foreach ($tasks as $task)
                                <tr>
                                    <td>{{ $task->assignedTo->firstname }} {{ $task->assignedTo->lastname }}</td>
                                    <td>{{ $task->title }}</td>
                                    <td>{{ $task->deadline }}</td>
                                    <td>@isset($task->finishdate){{ $task->finishdate }}@endisset</td>
                                    @php
                                        $color = "red";
                                        $done_status = "Pending";
                                        if(isset($task->finishdate))
                                            {

                                                 if($task->finishdate>$task->deadline)
                                                 {
                                                     $done_status = "Done (Delayed)";
                                                     $color = "red";
                                                 }
                                                 else
                                                 {
                                                     $done_status = "Done (In Time)";
                                                     $color = "green";
                                                 }
                                            }

                                    @endphp
                                    <td><span class="{{ $color }}">{{ $done_status }}</span></td>
                                    <td>{{ $task->comment }}</td>
                                    <td class="align-right">

                                        <a href="{{ url('personal/tasks/assignatask/edit/'.$task->id) }}"
                                           class="ui circular basic icon button tiny"><i
                                                    class="icon edit outline"></i></a>

                                        <a href="{{ url('/task/delete/'.$task->id) }}"
                                           class="ui circular basic icon button tiny"><i
                                                    class="icon trash alternate outline"></i></a></td>
                                </tr>
                            @endforeach
                        @endisset
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('scripts')
    <script src="{{ asset('/assets/vendor/air-datepicker/dist/js/datepicker.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/air-datepicker/dist/js/i18n/datepicker.en.js') }}"></script>
    <script src="{{ asset('/assets/vendor/mdtimepicker/mdtimepicker.min.js') }}"></script>
        <script src="{{ asset('/assets/vendor/datetimepicker/datetimepicker.js') }}"></script>

    <script type="text/javascript">

        $('#datetimepicker').datetimepicker();

        $('.jtimepicker').mdtimepicker({format: 'h:mm:ss tt', hourPadding: true});
        $('.airdatepicker').datepicker({language: 'en', dateFormat: 'yyyy-mm-dd'});

        $('.ui.dropdown.getid').dropdown({
            onChange: function (value, text, $selectedItem) {
                $('select[name="employee"] option').each(function () {
                    if ($(this).val() == value) {
                        var id = $(this).attr('data-id');
                        $('input[name="id"]').val(id);
                    }

                });
            }
        });
    </script>
@endsection
