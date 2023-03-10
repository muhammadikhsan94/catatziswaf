<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Aplikasi Pencatatan Ziswaf @yield('title')</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.5.4"></script>
    <!-- Latest compiled and minified JavaScript -->    
    <style>
        .dataTables_wrapper .dt-buttons {
            float:none;  
            text-align:center;
        }
        .dataTables_scrollBody {
            width: 100%;
            overflow-x: auto !important;
        }
        th { font-size: 13px; }
        td { font-size: 12px; }
        .highcharts-table-caption {
            margin-bottom: 5px;
            font-family: sans-serif;
            font-size: 14pt;
        }
        .highcharts-data-table table {
            border-collapse: collapse;
            border-spacing: 0;
            background: white;
            min-width: 100%;
            margin-top: 10px;
        }
        .highcharts-data-table td, .highcharts-data-table th {
            text-align: center;
            font-family: sans-serif;
            font-size: 10pt;
            border: 1px solid silver;
            padding: 0.5em;
        }
        .highcharts-data-table tr:nth-child(even), .highcharts-data-table thead tr {
            background: #f8f8f8;
        }
        .highcharts-data-table tr:hover {
            background: #eff;
        }
        .btn-primary-spacing 
        {
            margin-right: 5px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body class="hold-transition skin-green sidebar-mini" background="#">
    <div class="wrapper">
        @include('template.header')
        @include('template.nav')
        <div class="content-wrapper">
            <section class="content">
                @yield('content')
            </section>
        </div>
        @include('template.footer')
    </div>
    @stack('scripts')
    <script type="text/javascript">
        function konversi(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
        const capitalize = (s) => {
            if (typeof s !== 'string') return ''
            return s.charAt(0).toUpperCase() + s.slice(1)
        }
    </script>
</body>

</html>
