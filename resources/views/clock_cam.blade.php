@extends('layouts.clock')

@section('content')

    <div class="container-fluid">


        <div class="fixedcenter">
            <div class="clockwrapper">
                <div class="clockinout">
                    <button class="btnclock timein active" data-type="timein">Time In</button>
                    <button class="btnclock break" data-type="break">Break In/Out</button>
                    <button class="btnclock timeout" data-type="timeout">Time Out</button>
                </div>
            </div>
            <div class="clockwrapper">
                <br><br>
                <span id="show_time" class="clock-time"></span><br>
                <span id="show_date" style="color: #0c0c0c" class="clock-day"></span><br><br>

            </div>

            <div class="clockwrapper">
                <center>
                    <div id="my_camera"></div>
                </center>
                <br>
                <button id="fetch_id" class="ui primary small icon button" type="button" onClick="take_snapshot()">Fetch
                    ID
                </button>
            </div>


            <div class="clockwrapper">
                <div class="userinput">
                    <form action="" method="get" accept-charset="utf-8" class="ui form">
                        @isset($cc)
                            @if($cc == 1)
                                <div class="inline field comment">
                                    <textarea name="comment" class="uppercase lightblue" rows="1"
                                              placeholder="Enter comment"></textarea>
                                </div>
                            @endif
                        @endisset
                        <h3 id="fetch_msg">Place your face and click Fetch ID to proceed</h3>
                        <div class="spinner-border" role="status" hidden>

                        </div>
                            <input class="enter_idno uppercase"  name="idno" disabled hidden  value="" type="text"
                                   placeholder="YOUR ID" required="">


                            <div class="inline field">
                            <button id="btn_confirm" type="button" hidden  class="ui positive large button">Confirm
                            </button>
                                <button id="btn_reset" onClick="reset_all()" type="button" hidden class="ui negative large button">Reset
                                </button>
                            </div>
                            <input type="hidden" id="_url" value="{{url('/')}}">

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

<span id="site_url" style="display: none;">{{url('/')}}</span>

@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('assets/js/webcam.min.js') }}"></script>
    <script type="text/javascript">

        function showToast(heading, msg, icon) {
            $.toast({
                text : msg,
                hideAfter : 5000,
                position : 'bottom-center',
                textAlign : 'center',
                icon : icon,
                heading: heading,
                allowToastClose : false,
            });

        }
        // elements day, time, date
        var elTime = document.getElementById('show_time');
        var elDate = document.getElementById('show_date');
        //var elDay = document.getElementById('show_day');

        // time function to prevent the 1s delay
        var setTime = function () {
            // initialize clock with timezone
            var time = moment().tz(timezone);

            // set time in html
            elTime.innerHTML = time.format("hh:mm:ss A");

            // set date in html
            elDate.innerHTML = time.format('MMMM D, YYYY');

            // set day in html
            //elDay.innerHTML = time.format('dddd');
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

        $('#btn_confirm').click(function (event) {
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
                        // $('.message-after').addClass('notok').hide()
                        // $('#type, #fullname').text("").hide();
                        // $('#time').html("").hide();
                        // $('.message-after').removeClass("ok");

                        //$('#message').text(response['error']);
                        showToast("Error!", "<h2>" + response['error'] + "</h2>", 'error');
                        //$('#fullname').text(response['employee']);
                        //$('.message-after').slideToggle().slideDown('400');
                    } else {
                        function type(clocktype) {
                            if (clocktype == "timein") {
                                return "Time In";
                            } else if (clocktype == "break_in") {
                                return "Break In";
                            } else if (clocktype == "break_out") {
                                return "Break Out";
                            } else {
                                return "Time Out";
                            }
                        }

                        //$('.message-after').addClass('ok').hide();
                        //$('.message-after').removeClass("notok");
                        //$('#type, #fullname, #message').text("").show();
                        //$('#time').html("").show();

                        //$('#type').text(type(response['type']));
                        //$('#fullname').text(response['firstname'] + ' ' + response['lastname']);
                        //$('#time').html('at ' + '<span id=clocktime>' + response['time'] + '</span>' + '.' + '<span id=clockstatus> Success!</span>');
                        //$('.message-after').slideToggle().slideDown('400');
                        var msg = "<h2>" + type(response['type']) + ' at ' + response['time'] + "</h2>";
                        var title = "Hello, " + response['firstname'] + ' ' + response['lastname'];
                        showToast(title, msg, 'success');
                        $('#fetch_id').attr('hidden', false);
                        $('#fetch_msg').text("Place your face and click Fetch ID to proceed");
                        $('#btn_confirm').attr('hidden', true);
                        $('#btn_reset').attr('hidden', true);
                        $('input[name="idno"]').val("")

                    }
                }
            })
        });


    </script>

    <script type="text/javascript">
        Webcam.set({
            // live preview size
            width: 320,
            height: 240,

            // device capture size
            dest_width: 320,
            dest_height: 240,

            // final cropped size
            crop_width: 320,
            crop_height: 240,

            // format and quality
            image_format: 'jpeg',
            jpeg_quality: 90,

        });
        Webcam.attach('#my_camera');

        function reset_all() {
            $('#fetch_id').attr('hidden', false);
            $('#fetch_msg').text("Place your face and click Fetch ID to proceed");
            $('#btn_confirm').attr('hidden', true);
            $('#btn_reset').attr('hidden', true);
            $('input[name="idno"]').val("");
            Webcam.attach('#my_camera');
        }

        function take_snapshot() {
            $('.spinner-border').removeAttr('hidden');
            $('#fetch_id').attr('hidden', true);
            $('#fetch_msg').text("Image captured. Fetching your ID. Please wait...");
            $('#btn_confirm').attr('hidden', true);
            $('#btn_reset').attr('hidden', true);

            var url = document.getElementById('site_url').textContent;

            Webcam.snap(function (data_uri) {
                // display results in page
                $.ajax({
                    type: 'POST',

                    url: 'https://attendancekeeper.net:5009/face_rec/{{ $company_name }}',
                    data: {'image_data': data_uri},
                    success: function (data) {
                        console.log(data);
                        $('input[name="idno"]').val(data);
                        $('.spinner-border').attr("hidden", true);
                        $('#fetch_msg').text("Success! Your ID is: " + data);
                        $('#btn_confirm').attr("hidden", false);
                        $('#btn_reset').attr('hidden', false);
                        $('#fetch_id').attr('hidden', false);
                        Webcam.attach('#my_camera');

                    }
                });
                document.getElementById('my_camera').innerHTML =

                    '<img src="' + data_uri + '"/>';
            });
        }

    </script>


@endsection
