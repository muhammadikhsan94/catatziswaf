@extends('template.app')

@section('title')
- Laporan Data User
@endsection

@section('content')

<div class="box box-info">

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab">Rekonsiliasi Transaksi</a></li>
        </ul>

        <div class="tab-content">

            <div class="tab-pane active" id="tab_1">
                <div class="box-header with-border">
                    <h3 class="box-title"><strong>Laporan Rekonsiliasi Transaksi</strong></h3>
                </div>
                <div class="box-body">
                    <div class="dataTables_scrollBody">
                        <div class="col-md-12">
                            <table id="tabel-rekonsiliasi" class="display" style="width: 100%">
                                <thead>
                                    <tr class="bg-success">
                                        <th> No </th>
                                        <th> No Punggung </th>
                                        <th> Tanggal Transfer/Kirim </th>
                                        <th> Nomor Rekening </th>
                                        <th> Paket Ziswaf </th>
                                        <th> Jumlah </th>
                                        <th> Wilayah </th>
                                    </tr>
                                </thead>
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
        var asal = <?php echo json_encode($data['user']) ?>;

        var table = $('#tabel-rekonsiliasi').DataTable({
            dom: 'Blfrtip',
            buttons: [
                {name: 'excelHtml5', extend: 'excelHtml5', text: 'Export to EXCEL', messageTop: 'Laporan Rekonsiliasi Data Transaksi Pada LAZIS '+strtoupper(asal.nama_wilayah), className: 'btn btn-default btn-sm', pageSize: 'A4', autoFilter: true, customize: function ( xlsx ){ var sheet = xlsx.xl.worksheets['sheet1.xml']; $('row c', sheet).attr( 's', '25' ); }},
                {name: 'pdfHtml5', extend: 'pdfHtml5', text: 'Export to PDF', messageTop: 'Laporan Rekonsiliasi Data Transaksi Pada LAZIS '+strtoupper(asal.nama_wilayah), className: 'btn btn-default btn-sm', pageSize: 'A4'},
                {name: 'print', extend: 'print', text: 'PRINT', messageTop: 'Laporan Rekonsiliasi Data Transaksi Pada LAZIS '+strtoupper(asal.nama_wilayah), className: 'btn btn-default btn-sm', pageSize: 'A4'}
            ],
            "language": {
                "sEmptyTable": "DATA KOSONG ATAU TIDAK DITEMUKAN !",
                "sLengthMenu": "Tampilkan _MENU_ records",
                "sSearch": "Cari Data/Filter:",
            },
            ajax: {
                url: "{{ url('/panzisda/laporan/getdata') }}",
            },
            columns: [{
                data: "id",
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'no_punggung',
                name: 'no_punggung',
            },
            {
                data: 'tanggal_transfer',
                name: 'tanggal_transfer',
            },
            {
                data: 'rek_bank',
                name: 'rek_bank',
            },
            {
                data: 'nama_paket_zakat',
                name: 'nama_paket_zakat',
            },
            {
                data: 'jumlah',
                name: 'jumlah',
            },
            {
                data: 'nama_wilayah',
                name: 'nama_wilayah',
            }]
        });
    });
</script>
@endpush
@endsection