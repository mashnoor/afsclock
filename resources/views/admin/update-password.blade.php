@extends('layouts.default')

    @section('meta')
        <title>Change Password | Attendance Keeper</title>
        <meta name="description" content="Attendance Keeper update your password.">
    @endsection 

    @section('content')
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2 class="page-title">Change Password</h2>
            </div>    
        </div>

        <div class="row">
            <div class="col-md-6">
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
                        <form action="{{ url('user/update-password') }}" class="ui form" method="post" accept-charset="utf-8">
                        @csrf

                        <div class="field">
                            <label>Current Password</label>
                            <input type="password" name="currentpassword" value="" placeholder="Enter Current Password">
                        </div>

                        <div class="field">
                            <label for="">New Password</label>
                            <input type="password" name="newpassword" value="" placeholder="Enter Password">
                        </div>

                        <div class="field">
                            <label for="">Confirm Password</label>
                            <input type="password" name="confirmpassword" value="" placeholder="Enter Password Confirmation">
                        </div>
                    </div>
                    <div class="box-footer">
                        <button class="ui positive button" type="submit" name="submit"><i class="ui checkmark icon"></i> Update</button>
                        <a class="ui grey button" href="{{ url('dashboard') }}"><i class="ui times icon"></i> Cancel</a>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @endsection
    
    @section('scripts')
    <script type="text/javascript">

    </script>
    @endsection 