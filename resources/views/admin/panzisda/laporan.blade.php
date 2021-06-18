@extends('template.app')

@section('title')
- Laporan Data User
@endsection

@section('content')

<div class="box box-info">

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab">Duta Zakat</a></li>
            <li><a href="#tab_2" data-toggle="tab">Rekonsiliasi Ziswaf</a></li>
            <li><a href="#tab_3" data-toggle="tab">Realisasi Duta Zakat</a></li>
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
                    <h3 class="box-title"><strong>Laporan Rekonsiliasi Ziswaf</strong></h3>
                </div>
                <div class="box-body">
                    <div class="dataTables_scrollBody">
                        <div class="col-md-12">
                            <table id="tabel-rekonsiliasi" class="display" style="width: 100%">
                                <thead>
                                    <tr class="bg-success">
                                        <th> No </th>
                                        <th> Kab/Kota </th>
                                        <th> Lembaga </th>
                                        <th> Jenis Transaksi </th>
                                        <th> Rek Bank </th>
                                        <th> Duta Zakat </th>
                                        <th> Paket Zakat </th>
                                        <th> Tanggal Transaksi </th>
                                        <th> Jumlah </th>
                                        <th> Keterangan </th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th colspan="8" style="text-align:right">TOTAL:</th>
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
                    <h3 class="box-title"><strong>Laporan Realisasi Duta Zakat</strong></h3>
                </div>
                <div class="box-body">
                    <div class="dataTables_scrollBody">
                        <div class="col-md-12">
                            <table id="tabel-realisasi" class="display" style="width: 100%">
                                <thead>
                                    <tr class="bg-success">
                                        <th> No </th>
                                        <th> Kab/Kota </th>
                                        <th> Duta Zakat </th>
                                        <th> Manajer Group </th>
                                        <th> Target </th>
                                        <th> Realisasi </th>
                                        <th> Persentase (%) </th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" style="text-align:right">TOTAL:</th>
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
        
        var asal = <?php echo json_encode($data['user']) ?>;

        var table = $('#tabel-user').DataTable({
            dom: 'Blfrtip',
            buttons: [
                {name: 'excelHtml5', extend: 'excelHtml5', text: 'Export to EXCEL', messageTop: 'Laporan Data Duta Zakat - Kabupaten/Kota '+asal.nama_wilayah, className: 'btn btn-default btn-sm', pageSize: 'A4', autoFilter: true, customize: function ( xlsx ){ var sheet = xlsx.xl.worksheets['sheet1.xml']; $('row c', sheet).attr( 's', '25' ); }},
                {name: 'pdfHtml5', extend: 'pdfHtml5', text: 'Export to PDF', messageTop: 'Laporan Data Duta Zakat - Kabupaten/Kota '+asal.nama_wilayah, className: 'btn btn-default btn-sm', pageSize: 'A4'},
                {name: 'print', extend: 'print', text: 'PRINT', messageTop: 'Laporan Data Duta Zakat - Kabupaten/Kota '+asal.nama_wilayah, className: 'btn btn-default btn-sm', pageSize: 'A4'}
            ],
            "order": [[ 2, "desc" ]],
            "language": {
                "sEmptyTable": "DATA KOSONG ATAU TIDAK DITEMUKAN !",
                "sLengthMenu": "Tampilkan _MENU_ records",
                "sSearch": "Cari Data/Filter:",
            },
            ajax: {
                url: "{{ url('/panzisda/laporan/getdata') }}",
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

        var table = $('#tabel-rekonsiliasi').DataTable({
            dom: 'Blfrtip',
            buttons: [
                {name: 'excelHtml5', extend: 'excelHtml5', text: 'Export to EXCEL', messageTop: 'Laporan Rekonsiliasi Ziswaf - Kabupaten/Kota '+asal.nama_wilayah, className: 'btn btn-default btn-sm', pageSize: 'A4', autoFilter: true, customize: function ( xlsx ){ var sheet = xlsx.xl.worksheets['sheet1.xml']; $('row c', sheet).attr( 's', '25' ); }},
                {name: 'pdfHtml5', extend: 'pdfHtml5', text: 'Export to PDF', messageTop: 'Laporan Rekonsiliasi Ziswaf - Kabupaten/Kota '+asal.nama_wilayah, className: 'btn btn-default btn-sm', pageSize: 'A4'},
                {name: 'print', extend: 'print', text: 'PRINT', messageTop: 'Laporan Rekonsiliasi Ziswaf - Kabupaten/Kota '+asal.nama_wilayah, className: 'btn btn-default btn-sm', pageSize: 'A4'}
            ],
            "language": {
                "sEmptyTable": "DATA KOSONG ATAU TIDAK DITEMUKAN !",
                "sLengthMenu": "Tampilkan _MENU_ records",
                "sSearch": "Cari Data/Filter:",
            },
            "columnDefs": [
                {"className": "dt-center", "targets": [0, 8]}
            ],
            ajax: {
                url: "{{ url('/panzisda/laporan/transaksi/rekonsiliasi') }}",
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
                data: 'jenis_transaksi',
                name: 'jenis_transaksi',
            },
            {
                data: 'rek_bank',
                name: 'rek_bank',
            },
            {
                data: 'no_punggung',
                name: 'no_punggung',
            },
            {
                data: 'nama_paket_zakat',
                name: 'nama_paket_zakat',
            },
            {
                data: 'tanggal_transfer',
                name: 'tanggal_transfer',
            },
            {
                data: 'jumlah',
                name: 'jumlah',
            },
            {
                data: 'keterangan',
                name: 'keterangan',
            }],
            footerCallback: function( tfoot, data, start, end, display ) {
                var api = this.api();
                $(api.column(8).footer()).html(
                    api.column(8).data().reduce(function ( a, b ) {
                        a = parseInt(a.toString().replace(/,.*|[^0-9]/g, ''), 10);
                        b = parseInt(b.toString().replace(/,.*|[^0-9]/g, ''), 10);
                        total = a+b;
                        return convertToRupiah(total);
                    }, 0)
                );
            }
        });

        var table = $('#tabel-realisasi').DataTable({
            dom: 'Blfrtip',
            buttons: [
                {name: 'excelHtml5', extend: 'excelHtml5', text: 'Export to EXCEL', messageTop: 'Laporan Realisasi Duta Zakat - Kabupaten/Kota '+asal.nama_wilayah, className: 'btn btn-default btn-sm', pageSize: 'A4', autoFilter: true, customize: function ( xlsx ){ var sheet = xlsx.xl.worksheets['sheet1.xml']; $('row c', sheet).attr( 's', '25' ); }},
                {name: 'pdfHtml5', extend: 'pdfHtml5', text: 'Export to PDF', messageTop: 'Laporan Realisasi Duta Zakat - Kabupaten/Kota '+asal.nama_wilayah, className: 'btn btn-default btn-sm', pageSize: 'A4'},
                {name: 'print', extend: 'print', text: 'PRINT', messageTop: 'Laporan Realisasi Duta Zakat - Kabupaten/Kota '+asal.nama_wilayah, className: 'btn btn-default btn-sm', pageSize: 'A4'}
            ],
            "language": {
                "sEmptyTable": "DATA KOSONG ATAU TIDAK DITEMUKAN !",
                "sLengthMenu": "Tampilkan _MENU_ records",
                "sSearch": "Cari Data/Filter:",
            },
            "columnDefs": [
                {"className": "dt-center", "targets": [0, 4, 5, 6]}
            ],
            // "order": [[ 6, "DESC" ]],
            ajax: {
                url: "{{ url('/panzisda/laporan/transaksi/realisasi') }}",
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
                data: 'nama',
                name: 'nama',
            },
            {
                data: 'manajer_group',
                name: 'manajer_group',
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
                $(api.column(5).footer()).html(
                    api.column(5).data().reduce(function ( a, b ) {
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