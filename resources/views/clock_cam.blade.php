@extends('layouts.clock')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="fixedcenter">
                    <div class="clockwrapper">
                        <div class="clockinout">
                            <button class="btnclock timein active" data-type="timein">Time In</button>
                            <button class="btnclock timeout" data-type="timeout">Time Out</button>
                        </div>
                    </div>
                    <div class="clockwrapper">
                        <div class="timeclock">
                            <span id="show_day" class="clock-text"></span>
                            <span id="show_time" class="clock-time"></span>
                            <span id="show_date" class="clock-day"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="fixedcenter">
                    <div id="my_camera"></div>
                    <div id="results">Your captured image will appear here...</div>
                    <input type=button value="Take Snapshot" onClick="take_snapshot()">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="fixedcenter">


                <div class="clockwrapper">
                    <div class="userinput">
                        <form action="" method="get" accept-charset="utf-8" class="ui form">
                            @isset($cc)
                                @if($cc == 1)
                                    <div class="inline field comment">
                                    <textarea name="comment" class="uppercase lightblue" rows="1"
                                              placeholder="Enter comment" value=""></textarea>
                                    </div>
                                @endif
                            @endisset
                            <div class="inline field">
                                <input class="enter_idno uppercase" disabled name="idno" value="" type="text"
                                       placeholder="ENTER YOUR ID" required="">
                                <button id="btnclockin" type="button" class="ui positive large icon button">Confirm
                                </button>
                                <input type="hidden" id="_url" value="{{url('/')}}">
                            </div>
                        </form>
                    </div>
                </div>

                <div class="message-after">
                    <p>
                        <span id="greetings">Welcome!</span>
                        <span id="fullname"></span>
                    </p>
                    <p id="messagewrap">
                        <span id="type"></span>
                        <span id="message"></span>
                        <span id="time"></span>
                    </p>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('assets/js/webcam.min.js') }}"></script>
    <script type="text/javascript">
        // elements day, time, date
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

        $('.btnclock').click(function (event) {
            var is_comment = $(this).text();
            if (is_comment == "Time In") {
                $('.comment').slideDown('200').show();
            } else {
                $('.comment').slideUp('200');
            }
            $('.btnclock').removeClass('active animated fadeIn')
            $(this).toggleClass('active animated fadeIn');
        });

        $('#btnclockin').click(function (event) {
            var url, type, idno, comment;
            url = $("#_url").val();
            type = $('.btnclock.active').data("type");
            idno = $('input[name="idno"]').val();
            idno.toUpperCase();
            comment = $('textarea[name="comment"]').val();

            $.ajax({
                url: url + '/attendance/add',
                type: 'post',
                dataType: 'json',
                data: {idno: idno, type: type, clock_comment: comment},
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},

                success: function (response) {
                    if (response['error'] != null) {
                        $('.message-after').addClass('notok').hide()
                        $('#type, #fullname').text("").hide();
                        $('#time').html("").hide();
                        $('.message-after').removeClass("ok");

                        $('#message').text(response['error']);
                        $('#fullname').text(response['employee']);
                        $('.message-after').slideToggle().slideDown('400');
                    } else {
                        function type(clocktype) {
                            if (clocktype == "timein") {
                                return "Time In";
                            } else {
                                return "Time Out";
                            }
                        }

                        $('.message-after').addClass('ok').hide();
                        $('.message-after').removeClass("notok");
                        $('#type, #fullname, #message').text("").show();
                        $('#time').html("").show();

                        $('#type').text(type(response['type']));
                        $('#fullname').text(response['firstname'] + ' ' + response['lastname']);
                        $('#time').html('at ' + '<span id=clocktime>' + response['time'] + '</span>' + '.' + '<span id=clockstatus> Success!</span>');
                        $('.message-after').slideToggle().slideDown('400');
                    }
                }
            })
        });


    </script>

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
                $.ajax({
                    type: 'POST',
                    url: 'https://attendancekeeper.net:5002/face_rec',
                    data: {'image_data': data_uri},
                    success: function (data) {
                        console.log(data);
                        $('input[name="idno"]').val(data);

                    }
                });
                document.getElementById('results').innerHTML =
                    '<h2>Here is your image:</h2>' +
                    '<img src="' + data_uri + '"/>';
            });
        }

    </script>


@endsection