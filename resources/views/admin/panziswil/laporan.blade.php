@extends('template.app')

@section('title')
- Laporan Data User
@endsection

@section('content')

<div class="box box-info">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">Data User</a></li>
                <li><a href="#tab_2" data-toggle="tab">Realisasi Ziswaf</a></li>
                <li><a href="#tab_3" data-toggle="tab">Transaksi by Jenis Ziswaf</a></li>
            </ul>

            <div class="tab-content">

                <div class="tab-pane active" id="tab_1">
                    <div class="box-header with-border">
                        <h3 class="box-title"><strong>Laporan Data User</strong></h3>
                    </div>
                    <div class="box-body">
                        <div class="dataTables_scrollBody">
                            <div class="col-md-12">
                                <table id="tabel-user" class="display" style="width: 100%">
                                    <thead>
                                        <tr class="bg-success">
                                            <!--<th> No </th>-->
                                            <th> No Punggung </th>
                                            <th> Duta Zakat </th>
                                            <th> Manajer Group </th>
                                            <th> Manajer Area </th>
                                            <th> Wilayah </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="tab_2">

                    <div class="box-header with-border">
                        <h3 class="box-title"><strong>Laporan Realisasi Ziswaf</strong></h3>
                    </div>
                    <div class="box-body">
                        <div class="dataTables_scrollBody">
                            <div class="col-md-12">
                                <table id="tabel-realisasi" class="display" style="width: 100%">
                                    <thead>
                                        <tr class="bg-success">
                                            <th> No </th>
                                            <th> Kab/Kota </th>
                                            <th> Target </th>
                                            <th> Realisasi </th>
                                            <th> Persentase (%) </th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" style="text-align:right">TOTAL:</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="tab-pane" id="tab_3">

                    <div class="box-header with-border">
                        <h3 class="box-title"><strong>Laporan Ziswaf Berdasarkan Jenis</strong></h3>
                    </div>
                    <div class="box-body">
                        <div class="dataTables_scrollBody">
                            <div class="col-md-12">
                                <table id="tabel-jenis" class="display" style="width: 100%">
                                    <thead>
                                        <tr class="bg-success">
                                            <th> No </th>
                                            <th> Kab/Kota </th>
                                            <th> Lembaga </th>
                                            <th> Jenis Ziswaf </th>
                                            <th> Jumlah </th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" style="text-align:right">TOTAL:</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>
<script>
    $(document).ready(function() {

        function convertToRupiah(angka)
        {
            var rupiah = '';		
            var angkarev = angka.toString().split('').reverse().join('');
            for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
            return 'Rp. '+rupiah.split('',rupiah.length-1).reverse().join('');
        }
        
        var table = $('#tabel-user').DataTable({
            dom: 'Blfrtip',
            buttons: [
                {name: 'excelHtml5', extend: 'excelHtml5', text: 'Export to Excel', messageTop: 'Laporan Data Duta Zakat', className: 'btn btn-default btn-sm', pageSize: 'A4', autoFilter: true, customize: function ( xlsx ){ var sheet = xlsx.xl.worksheets['sheet1.xml']; $('row c', sheet).attr( 's', '25' ); }},
                {name: 'pdfHtml5', extend: 'pdfHtml5', text: 'Export to PDF', messageTop: 'Laporan Data Duta Zakat', className: 'btn btn-default btn-sm', pageSize: 'A4'},
                {name: 'print', extend: 'print', text: 'Print', messageTop: 'Laporan Data Duta Zakat', className: 'btn btn-default btn-sm', pageSize: 'A4'}
            ],
            "order": [[ 2, "desc" ]],
            "language": {
                "sEmptyTable": "DATA KOSONG ATAU TIDAK DITEMUKAN !",
                "sLengthMenu": "Tampilkan _MENU_ records",
                "sSearch": "Cari Data/Filter:",
            },
            ajax: {
                url: "{{ url('/panziswil/laporan/getdata') }}",
            },
            columns: [{
                data: 'no_punggung',
                name: 'no_punggung',
            },
            {
                data: 'duta_zakat',
                name: 'duta_zakat',
            },
            {
                data: 'manajer_group',
                name: 'manajer_group',
            },
            {
                data: 'manajer_area',
                name: 'manajer_area',
            },
            {
                data: 'wilayah',
                name: 'wilayah',
            }]
        });

        var table = $('#tabel-realisasi').DataTable({
            dom: 'Blfrtip',
            buttons: [
                {name: 'excelHtml5', extend: 'excelHtml5', text: 'Export to Excel', messageTop: 'Laporan Realiasi Duta Zakat', className: 'btn btn-default btn-sm', pageSize: 'A4', autoFilter: true, customize: function ( xlsx ){ var sheet = xlsx.xl.worksheets['sheet1.xml']; $('row c', sheet).attr( 's', '25' ); }},
                {name: 'pdfHtml5', extend: 'pdfHtml5', text: 'Export to PDF', messageTop: 'Laporan Realiasi Duta Zakat', className: 'btn btn-default btn-sm', pageSize: 'A4'},
                {name: 'print', extend: 'print', text: 'Print', messageTop: 'Laporan Realiasi Duta Zakat', className: 'btn btn-default btn-sm', pageSize: 'A4'}
            ],
            "language": {
                "sEmptyTable": "DATA KOSONG ATAU TIDAK DITEMUKAN !",
                "sLengthMenu": "Tampilkan _MENU_ records",
                "sSearch": "Cari Data/Filter:",
            },
            "columnDefs": [
                {"className": "dt-center", "targets": [0, 2, 3, 4]}
            ],
            ajax: {
                url: "{{ url('/panziswil/laporan/transaksi') }}",
            },
            columns: [{
                data: "id",
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'nama_wilayah',
                name: 'nama_wilayah',
            },
            {
                data: 'target',
                name: 'target',
            },
            {
                data: 'realisasi',
                name: 'realisasi',
            },
            {
                data: 'persentase',
                name: 'persentase',
            }],
            footerCallback: function( tfoot, data, start, end, display ) {
                var api = this.api();
                $(api.column(3).footer()).html(
                    api.column(3).data().reduce(function ( a, b ) {
                        a = parseInt(a.toString().replace(/,.*|[^0-9]/g, ''), 10);
                        b = parseInt(b.toString().replace(/,.*|[^0-9]/g, ''), 10);
                        total = a+b;
                        return convertToRupiah(total);
                    }, 0)
                );
            }
        });

        var table = $('#tabel-jenis').DataTable({
            dom: 'Blfrtip',
            buttons: [
                {name: 'excelHtml5', extend: 'excelHtml5', text: 'Export to Excel', messageTop: 'Laporan Ziswaf Berdasarkan Lembaga', className: 'btn btn-default btn-sm', pageSize: 'A4', autoFilter: true, customize: function ( xlsx ){ var sheet = xlsx.xl.worksheets['sheet1.xml']; $('row c', sheet).attr( 's', '25' ); }},
                {name: 'pdfHtml5', extend: 'pdfHtml5', text: 'Export to PDF', messageTop: 'Laporan Ziswaf Berdasarkan Lembaga', className: 'btn btn-default btn-sm', pageSize: 'A4'},
                {name: 'print', extend: 'print', text: 'Print', messageTop: 'Laporan Ziswaf Berdasarkan Lembaga', className: 'btn btn-default btn-sm', pageSize: 'A4'}
            ],
            "language": {
                "sEmptyTable": "DATA KOSONG ATAU TIDAK DITEMUKAN !",
                "sLengthMenu": "Tampilkan _MENU_ records",
                "sSearch": "Cari Data/Filter:",
            },
            "columnDefs": [
                {"className": "dt-center", "targets": [0, 4]}
            ],
            ajax: {
                url: "{{ url('/panziswil/laporan/transaksi/jenis-ziswaf') }}",
            },
            columns: [{
                data: "id",
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'nama_wilayah',
                name: 'nama_wilayah',
            },
            {
                data: 'nama_lembaga',
                name: 'nama_lembaga',
            },
            {
                data: 'nama_paket_zakat',
                name: 'nama_paket_zakat',
            },
            {
                data: 'jumlah',
                name: 'jumlah',
            }],
            footerCallback: function( tfoot, data, start, end, display ) {
                var api = this.api();
                $(api.column(4).footer()).html(
                    api.column(4).data().reduce(function ( a, b ) {
                        a = parseInt(a.toString().replace(/,.*|[^0-9]/g, ''), 10);
                        b = parseInt(b.toString().replace(/,.*|[^0-9]/g, ''), 10);
                        total = a+b;
                        return convertToRupiah(total);
                    }, 0)
                );
            }
        });
    });
</script>
@endpush
@endsection