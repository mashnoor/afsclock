@extends('layouts.default')

@section('meta')
    <title>Edit Task | Smart Timesheet</title>
    <meta name="description" content="smart timesheet edit employee attendance.">
@endsection

@section('styles')
    <link href="{{ asset('/assets/vendor/mdtimepicker/mdtimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/vendor/air-datepicker/dist/css/datepicker.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2 class="page-title">Edit Task</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-content">
                        @if ($errors->any())
                            <div class="ui error message">
                                <i class="close icon"></i>
                                <div class="header">There were some errors with your submission</div>
                                <ul class="list">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form id="edit_task_form" action="{{ url('personal/tasks/update') }}" class="ui form" method="post" accept-charset="utf-8">
                            @csrf
                            <div class="field">
                                <label>Assigned By</label>
                                <input type="text"  class="readonly" readonly="" value="@isset($task->assignedTo){{ $task->assignedTo->firstname }} {{ $task->assignedTo->lastname }}@endisset">
                            </div>

                            <div class="field">
                                <label>Title</label>
                                <input type="text" name="title" class="readonly" readonly=""  value="@isset($task->title){{ $task->title }}@endisset">
                            </div>
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <label>Description</label>
                                    <textarea class="" rows="5" class="readonly" readonly="" >@isset($task->description){{ $task->description }}@endisset</textarea>
                                </div>
                            </div>

                            <div class="field">
                                <label for="">Deadline</label>
                                <input type="text" placeholder="0000-00-00" class="readonly" readonly="" value="@isset($task->deadline){{ $task->deadline }}@endisset"/>
                            </div>
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <label>Comment</label>
                                    <textarea class="" rows="5" name="comment">@isset($task->comment){{ $task->comment }}@endisset</textarea>
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui error message">
                                    <i class="close icon"></i>
                                    <div class="header"></div>
                                    <ul class="list">
                                        <li class=""></li>
                                    </ul>
                                </div>
                            </div>
                    </div>


                    <div class="box-footer">
                        <input type="hidden" name="id" value="@isset($e_id){{ $e_id }}@endisset">
                        <button class="ui positive small button" type="submit" name="submit"><i class="ui checkmark icon"></i> Mark Done</button>
                        <a class="ui grey small button" href="{{ route('viewPersonalTask') }}"><i class="ui times icon"></i> Cancel</a>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('/assets/vendor/mdtimepicker/mdtimepicker.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/air-datepicker/dist/js/datepicker.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/air-datepicker/dist/js/i18n/datepicker.en.js') }}"></script>

    <script type="text/javascript">
        $('.jtimepicker').mdtimepicker({format:'h:mm:ss tt', theme: 'blue', hourPadding: true});
        $('.airdatepicker').datepicker({ language: 'en', dateFormat: 'yyyy-mm-dd' });
    </script>
@endsection