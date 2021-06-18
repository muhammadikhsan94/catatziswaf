<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Aplikasi Ziswaf</title>
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" type="text/css" href="{{ asset('lte/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('lte/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('lte/bower_components/Ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('lte/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('lte/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('lte/dist/css/skins/_all-skins.min.css') }}">
    <!-- <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-confirm.min.css') }}"> -->
    <link rel="stylesheet" type="text/css" href="{{ asset('lte/plugins/iCheck/square/blue.css') }}">

    <script type="text/javascript" href="{{ asset('lte/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript" href="{{ asset('lte/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" href="{{ asset('lte/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" href="{{ asset('lte/plugins/iCheck/icheck.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script type="text/javascript" href="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>
    <script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
    </script>
</head>
<body class="hold-transition" background="{{ url('images/bg.png') }}" style="background-size: 100%;">
    <div class="login-box" style="border-radius: 15px; background-color : white !important;" >
        <div class="" style="padding-top:12px">    
            <img class="img-responsive center-block" src="{{ asset('images/toplogin.jpg') }}" >    
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Status Login Anda Belum Di Verifikasi.<br>Silahkan Hubungi Panzisda Wilayah Anda.<br>Terima Kasih.</p>
            <p class="login-box-msg"><a type="button" class="btn btn-danger" href="{{route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span>Logout</span></a></p>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
        <!-- /.login-box-body -->
    </div>
</body>
</html>