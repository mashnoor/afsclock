@extends('layouts.default')

    @section('meta')
        <title>Active Schedule | Attendance Keeper</title>
        <meta name="description" content="Attendance Keeper schedules, view all employee schedules, add schedule or shift, edit, and delete schedules.">
    @endsection

    @section('styles')

    @endsection

    @section('content')
    <div class="container">
      <h4>Employee Name: {{$employee->firstname}} {{$employee->lastname}}</h4>
      <h4>ID: {{$employee->idno}}</h4>
    </div>
      <div class="container">
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
        @isset($sch_template)

            <tr>
              <td>{{$sch_template->name}}</td>
              <td>
                @if($sch_template->saturday)
                {{$sch_template->saturday}}
                @else
                N/A
                @endif
              </td>
              <td>
                @if($sch_template->sunday)
                {{$sch_template->sunday}}
                @else
                N/A
                @endif
              </td>
              <td>
                @if($sch_template->monday)
                {{$sch_template->monday}}
                @else
                N/A
                @endif
              </td>
              <td>
                @if($sch_template->tuesday)
                {{$sch_template->tuesday}}
                @else
                N/A
                @endif
              </td>
              <td>
                @if($sch_template->wednesday)
                {{$sch_template->wednesday}}
                @else
                N/A
                @endif
              </td>
              <td>
                @if($sch_template->thursday)
                {{$sch_template->thursday}}
                @else
                N/A
                @endif
              </td>
              <td>
                @if($sch_template->friday)
                {{$sch_template->friday}}
                @else
                N/A
                @endif
              </td>
              <td>{{$sch_template->break_allowence}} Min</td>

            </tr>

        @endisset
      </tbody>
    </table>
        </div>
      </div>

    @endsection

    @section('scripts')
    <script src="{{ asset('/assets/vendor/air-datepicker/dist/js/datepicker.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/air-datepicker/dist/js/i18n/datepicker.en.js') }}"></script>
    <script src="{{ asset('/assets/vendor/mdtimepicker/mdtimepicker.min.js') }}"></script>

    <script type="text/javascript">
    $('#dataTables-example').DataTable({responsive: true,pageLength: 15,lengthChange: false,searching: true,ordering: true});

    $('.jtimepicker').mdtimepicker({ format: 'h:mm:ss tt', hourPadding: true });
    $('.airdatepicker').datepicker({ language: 'en', dateFormat: 'yyyy-mm-dd' });

    $('.ui.dropdown.getid').dropdown({ onChange: function(value, text, $selectedItem) {
        $('select[name="employee"] option').each(function() {
            if($(this).val()==value) {var id = $(this).attr('data-id');$('input[name="id"]').val(id);};
        });
    }});
    </script>
    @endsection
