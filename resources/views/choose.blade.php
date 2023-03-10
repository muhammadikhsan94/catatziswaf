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
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body class="hold-transition" background="{{ url('images/bg.png') }}" style="background-size: 100%;">
<div id="myModal" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Cara Membuat Auto Pop Up Responsive Menggunakan Bootstrap</h4>
			</div>
			<div class="modal-body">
				<p>One fine body&hellip;</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
	$(document).ready(function() {
		$('#myModal').show();
		console.log('test test');
	});
</script>
</body>
</html>