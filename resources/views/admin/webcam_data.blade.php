@extends('layouts.default')

@section('meta')
    <title>Webcam Data | Attendance Keeper</title>
    <meta name="description"
          content="Realtime Webcam data">
@endsection

@section('content')

<table class="ui celled table">
  <thead>
    <th>User ID</th>
    <th>Last Seen</th>
  </tr></thead>
  <tbody>
    @isset($webcam_data)
        @foreach ($webcam_data as $d)
          <tr>
            <td>{{$d->idno}}</td>
            <td>{{$d->last_seen}}</td>
          </tr>
    @endforeach
  @endisset
  </tbody>
</table>

@endsection
