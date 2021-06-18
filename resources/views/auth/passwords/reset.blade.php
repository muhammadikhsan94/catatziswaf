<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" type="text/css" href="{{asset('lte/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('lte/bower_components/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('lte/bower_components/Ionicons/css/ionicons.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('lte/dist/css/AdminLTE.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('lte/dist/css/skins/_all-skins.min.css')}}">
    <!-- <link rel="stylesheet" type="text/css" href="{{asset('css/jquery-confirm.min.css')}}"> -->
    @yield('styles')

    <script type="text/javascript" src="{{asset('lte/bower_components/jquery/dist/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('lte/bower_components/jquery-ui/jquery-ui.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('lte/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('lte/dist/js/adminlte.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('lte/plugins/iCheck/icheck.min.js')}}"></script>

    <!-- DataTables -->
    <script type="text/javascript" src="{{asset('lte/dist/js/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('lte/dist/js/dataTables.bootstrap4.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('lte/dist/js/bootstrap-select.min.js')}}"></script>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <!-- Latest compiled and minified JavaScript -->  
    <script>
        $(document).ready(function() {
            $('select').selectpicker();
        });
    </script>
</head>
<body class="hold-transition" background="{{ url('images/bg.png') }}" style="background-size: 100%;">
    <div class="container" style="padding-top: 5%;">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading"><center>Reset Password</center></div>
                    <div class="panel-body">
     
                        <form id="formRegister" class="form-horizontal" method="post" action="{{ route('password.update') }}" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="form-group ">
                                <label for="email" class="col-sm-4 control-label">Email</label>
                                <div class="col-sm-8">
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $email ?? old('email') }}" oninvalid="this.setCustomValidity('alamat email salah!')" onchange="setCustomValidity('')" required>
                                </div>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group ">
                                <label for="email" class="col-sm-4 control-label">Password Baru</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password Baru" oninvalid="this.setCustomValidity('tidak boleh kosong!')" onchange="setCustomValidity('')" required autofocus>
                                </div>
                            </div>

                            <div class="form-group ">
                                <label for="email" class="col-sm-4 control-label">Konfirmasi Password Baru</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" id="password-confirm" name="password_confirmation" placeholder="Masukkan Konfirmasi Password Baru" oninvalid="this.setCustomValidity('tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                                </div>
                            </div>

                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>