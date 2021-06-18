@extends('template.app')

@section('title')
- Laporan Data Muzakki
@endsection

@section('content')

<div class="box box-info">

    <div class="box-header with-border">
        <div class="col-md-6">
            <h3 class="box-title"><strong>Laporan Data Muzakki</strong></h3>
        </div>
    </div>

    <div class="box-body">
        <div class="dataTables_scrollBody">
            <div class="col-md-12">
                <table id="tabel-donatur" class="display" style="width: 100%">
                    <thead>
                        <tr class="bg-success">
                            <th width="3%"> No </th>
                            <th> ID Muzakki </th>
                            <th> Nama </th>
                            <th> Alamat </th>
                            <th> NPWP </th>
                            <th> No HP </th>
                            <th> Penghasilan </th>
                            <th> Tanggungan </th>
                            <th> Status Rumah </th>
                        </tr>
                    </thead>
                </table>
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

        var table = $('#tabel-donatur').DataTable({
            dom: 'Blfrtip',
            buttons: [
                {name: 'excelHtml5', extend: 'excelHtml5', text: 'Export to EXCEL', messageTop: 'Laporan Data Muzakki', className: 'btn btn-default btn-sm', pageSize: 'A4', autoFilter: true, customize: function ( xlsx ){ var sheet = xlsx.xl.worksheets['sheet1.xml']; $('row', sheet).attr( 's', '25' ); } },
            ],
            "language": {
                "sEmptyTable": "DATA KOSONG ATAU TIDAK DITEMUKAN !",
                "sLengthMenu": "Tampilkan _MENU_ records",
                "sSearch": "Cari Data/Filter:",
            },
            ajax: {
                url: "{{ url('/panzisda/laporan/donatur/getdata') }}",
            },
            "columnDefs": [
                {"className": "dt-center", "targets": [0, 1, 4, 5, 6, 7, 8]}
            ],
            columns: [
                { data: "id", orderable: false, render: function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                { data: 'id_donatur' },
                { data: 'nama' },
                { data: 'alamat' },
                { data: 'npwp' },
                { data: 'no_hp' },
                { data: 'penghasilan' },
                { data: 'tanggungan' },
                { data: 'status_rumah' },
            ]
        });
    });
</script>
@endpush

@endsection