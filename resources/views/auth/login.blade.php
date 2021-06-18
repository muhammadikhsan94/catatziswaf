<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
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
            <p class="login-box-msg">Masuk untuk memulai sesi</p>
            @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button> 
                <strong>{{ $message }}</strong>
            </div>
            @endif
            <form action="{{ url('login') }}" method="post">
                @csrf
                <div class="form-group has-feedback">
                    <input name="username" id="username" type="text" class="form-control @error('username') is-invalid @enderror" placeholder="Nomor Punggung/ Nomor HP/ Email" required autofocus>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input name="password" type="password" class="form-control" placeholder="Masukan Kata Sandi" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-7">
                        <div class="checkbox icheck" style="margin-left: 20px;">
                            <label>
                                <input type="checkbox"> Ingat saya
                            </label>
                        </div>
                    </div>
                    <div class="col-xs-5">
                        <button type="submit" class="btn btn-success btn-block btn-flat">{{ __('Masuk') }}</button>
                    </div>
                </div>

                @error('username')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                @if(session('failed'))
                <div class="alert alert-danger alert-dismissible" role="alert" style="margin-top:10px;">
                    {{ session('failed') }}.
                </div>
                @endif
            </form>
        </div>
        <div class="row">
            <p class="login-box-msg">Belum memiliki akun? Silahkan <a type="button" href="{{url('/daftar')}}" class="btn btn-primary btn-xs">DAFTAR</a></p>
            <p class="login-box-msg"><a href="{{ url('/forgot-password') }}">Lupa Password?</a></p>
        </div>
        <!-- /.login-box-body -->
    </div>
</body>
</html>