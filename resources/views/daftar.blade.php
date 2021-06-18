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
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading"><center>Register</center></div>
                    <div class="panel-body">
     
                        <form id="formRegister" class="form-horizontal" method="post" action="{{url('/daftar/simpan')}}" enctype="multipart/form-data">

                            @if ($message = Session::get('errors'))
                            <div class="alert alert-warning alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button> 
                                <strong>{{ $message }}</strong>
                            </div>
                            @endif
     
                            @csrf

                            <div class="form-group ">
                                <label for="id_wilayah" class="col-sm-3 control-label">Wilayah</label>
                                <div class="col-sm-5">
                                    <select data-size="5" data-live-search="true" id="id_wilayah" name="id_wilayah" class="selectpicker" title="Pilih Wilayah.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                                        @foreach($data['wilayah'] as $ket => $wilayah)
                                        <option value="{{ $wilayah->id }}">{{ $wilayah->nama_wilayah }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group ">
                                <label for="nama" class="col-sm-3 control-label">Nama Lengkap</label>
                                <div class="col-sm-5">
                                    <input class="form-control" id="nama" name="nama" placeholder="Masukkan Nama" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                                </div>
                            </div>

                            <div class="form-group ">
                                <label for="alamat" class="col-sm-3 control-label">Alamat</label>
                                <div class="col-sm-5">
                                    <textarea class="form-control" id="alamat" name="alamat" placeholder="Masukkan Alamat" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required></textarea>
                                </div>
                            </div>

                            <div class="form-group ">
                                <label for="npwp" class="col-sm-3 control-label">NPWP</label>
                                <div class="col-sm-5">
                                    <input type="number" maxlength="15" class="form-control" id="npwp" name="npwp" placeholder="Contoh: 999888777666555" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')">
                                </div>
                            </div>

                            <div class="form-group ">
                                <label for="no_hp" class="col-sm-3 control-label">Nomor HP</label>
                                <div class="col-sm-5">
                                    <input type="number" maxlength="13" class="form-control" id="no_hp" name="no_hp" placeholder="Contoh: 081122223333" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                                </div>
                            </div>

                            <div class="form-group " id="tambah_email_donatur">
                                <label for="email" class="col-sm-3 control-label">email</label>
                                <div class="col-sm-5">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan Email" oninvalid="this.setCustomValidity('alamat email salah!')" onchange="setCustomValidity('')" required>
                                </div>
                            </div>

                            <div class="form-group ">
                                <label for="password" class="col-sm-3 control-label">Password</label>
                                <div class="col-sm-5">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                                </div>
                            </div>

                            <div class="form-group ">
                                <label for="password_confirmation" class="col-sm-3 control-label">Confirm Password</label>
                                <div class="col-sm-5">
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Masukkan Ulang Password">
                                </div>
                            </div>

                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary">Simpan</button>&nbsp;
                                <a type="button" href="{{url('/')}}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>