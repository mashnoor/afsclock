<div class="ui modal add medium">
    <div class="header">Add New Task</div>
    <div class="content">
        <form id="add_schedule_form" action="{{ url('task/add') }}" class="ui form" method="post"
              accept-charset="utf-8">
            @csrf
            <div class="field">
                <label>Employee</label>
                <select class="ui search dropdown getid uppercase" name="employee">
                    <option value="">Select Employee</option>
                    @isset($employee)
                        @foreach ($employee as $data)
                            <option value="{{ $data->lastname }}, {{ $data->firstname }}"
                                    data-id="{{ $data->id }}">{{ $data->lastname }}, {{ $data->firstname }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>

            <div class="field">
                <label for="">Title</label>
                <input type="text" placeholder="Task Title" name="title"/>
            </div>


            <div class="field">
                <label for="">Description</label>
                <textarea type="text" placeholder="Task Description" name="description"></textarea>
            </div>
            <div class="field">
                <label for="">Deadline</label>
                <!-- <input type="text" placeholder="Date" autocomplete="off" name="deadline" class="airdatepicker" data-position="top right"/> -->


                <div class="form-group">

                    <input type='text' id="datetimepicker" name="deadline" class="form-control" />

                </div>
            </div>
            </div>


            <div class="actions">
                <input type="hidden" name="id" value="">
                <button class="ui positive small button" type="submit" name="submit"><i class="ui checkmark icon"></i>
                    Assign
                </button>
                <button class="ui grey small button cancel" type="button"><i class="ui times icon"></i> Cancel</button>
            </div>
        </form>
    </div>
</div>
