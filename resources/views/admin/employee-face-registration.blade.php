@extends('layouts.clock')

@section('content')

    <div class="container-fluid">
        <div id="my_camera"></div>
        <div id="results">Your captured image will appear here...</div>
        <input type=button value="Take Snapshot" onClick="take_snapshot()">
    </div>



@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('assets/js/webcam.min.js') }}"></script>
    <script type="text/javascript">
        Webcam.set({
            width: 320,
            height: 240,
            image_format: 'jpeg',
            jpeg_quality: 90
        });
        Webcam.attach('#my_camera');

        function take_snapshot() {
            // take snapshot and get image data
            Webcam.snap(function (data_uri) {
                // display results in page
                console.log(data_uri)
                document.getElementById('results').innerHTML =
                    '<h2>Here is your image:</h2>' +
                    '<img src="' + data_uri + '"/>';
                $.ajax({
                    type:'POST',
                    url: '{{ url('employee/registerface/') }}/{{ $id }}' ,
                    data: {'_token' : '{{ csrf_token() }}', 'img_data' : data_uri},
                    success:function(data) {
                        console.log(data.msg);
                    }
                });


            });
        }

    </script>

@endsection