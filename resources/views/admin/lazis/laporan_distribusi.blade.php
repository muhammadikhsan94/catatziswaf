@extends('template.app')

@section('title')
- Laporan Realisasi Distribusi
@endsection

@section('content')

<div class="box box-info">

    <div class="box-header with-border">
        <h3 class="box-title"><strong>Laporan Realisasi Distribusi</strong></h3>
    </div>

    <div class="box-body">
        <div class="dataTables_scrollBody">
            <div class="col-md-12">
                <table id="tabel-realisasi" class="display" style="width: 100%;">
                    <thead>
                        <tr class="bg-success">
                            <th width="5%"> No </th>
                            <th width="20%"> Paket Ziswaf </th>
                            <th width="15%"> Panzisnas </th>
                            <th width="15%"> Panziswil </th>
                            <th width="15%"> Panzisda </th>
                            <th width="15%"> Mitra ( {{ strtoupper(strtolower($data['lembaga']->nama_lembaga)) }} ) </th>
                            <th width="15%"> Jumlah </th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th style="text-align:right">TOTAL:</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
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

        function convertToRupiah(angka)
        {
            var rupiah = '';		
            var angkarev = angka.toString().split('').reverse().join('');
            for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
            return rupiah.split('',rupiah.length-1).reverse().join('');
        }

        var asal = <?php echo json_encode($data['lembaga']) ?>;

        var table = $('#tabel-realisasi').DataTable({
            dom: 'Blfrtip',
            buttons: [
                {name: 'excelHtml5', extend: 'excelHtml5', text: 'Export to EXCEL', messageTop: 'Laporan Realisasi Distribusi - Lembaga '+asal.nama_lembaga, className: 'btn btn-default btn-sm', pageSize: 'A4', autoFilter: true, customize: function ( xlsx ){ var sheet = xlsx.xl.worksheets['sheet1.xml']; $('row c', sheet).attr( 's', '25' ); }, footer: true},
                {name: 'pdfHtml5', extend: 'pdfHtml5', text: 'Export to PDF', messageTop: 'Laporan Realisasi Distribusi - Lembaga '+asal.nama_lembaga, className: 'btn btn-default btn-sm', pageSize: 'A4', footer: true},
                {name: 'print', extend: 'print', text: 'PRINT', messageTop: 'Laporan Realisasi Distribusi - Lembaga '+asal.nama_lembaga, className: 'btn btn-default btn-sm', pageSize: 'A4', footer: true}
            ],
            "language": {
                "sEmptyTable": "DATA KOSONG ATAU TIDAK DITEMUKAN !",
                "sLengthMenu": "Tampilkan _MENU_ records",
                "sSearch": "Cari Data/Filter:",
            },
            "lengthMenu": [25, 50, 100],
            "columnDefs": [
                {"className": "dt-center", "targets": [0]},
                {"className": "dt-head-left", "targets": [1]},
                {"className": "dt-right", "targets": "_all"}
            ],
            ajax: {
                url: "{{ route('lazis.getDataLaporanDistribusi') }}",
            },
            columns: [
                { data: "id", render: function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                { data: 'paket' },
                { data: 'panzisnas', render: $.fn.dataTable.render.number( ',', '.', 0 ) },
                { data: 'panziswil', render: $.fn.dataTable.render.number( ',', '.', 0 ) },
                { data: 'panzisda', render: $.fn.dataTable.render.number( ',', '.', 0 ) },
                { data: 'lembaga', render: $.fn.dataTable.render.number( ',', '.', 0 ) },
                { data: 'jumlah', render: $.fn.dataTable.render.number( ',', '.', 0 ) },
            ],
            footerCallback: function( tfoot, data, start, end, display ) {
                var api = this.api();
                $(api.column(2).footer()).html(
                    api.column(2).data().reduce(function ( a, b ) {
                        a = parseInt(a.toString().replace(/,.*|[^0-9]/g, ''), 10);
                        b = parseInt(b.toString().replace(/,.*|[^0-9]/g, ''), 10);
                        total = a+b;
                        return convertToRupiah(total);
                    }, 0)
                );
                $(api.column(3).footer()).html(
                    api.column(3).data().reduce(function ( a, b ) {
                        a = parseInt(a.toString().replace(/,.*|[^0-9]/g, ''), 10);
                        b = parseInt(b.toString().replace(/,.*|[^0-9]/g, ''), 10);
                        total = a+b;
                        return convertToRupiah(total);
                    }, 0)
                );
                $(api.column(4).footer()).html(
                    api.column(4).data().reduce(function ( a, b ) {
                        a = parseInt(a.toString().replace(/,.*|[^0-9]/g, ''), 10);
                        b = parseInt(b.toString().replace(/,.*|[^0-9]/g, ''), 10);
                        total = a+b;
                        return convertToRupiah(total);
                    }, 0)
                );
                $(api.column(5).footer()).html(
                    api.column(5).data().reduce(function ( a, b ) {
                        a = parseInt(a.toString().replace(/,.*|[^0-9]/g, ''), 10);
                        b = parseInt(b.toString().replace(/,.*|[^0-9]/g, ''), 10);
                        total = a+b;
                        return convertToRupiah(total);
                    }, 0)
                );
                $(api.column(6).footer()).html(
                    api.column(6).data().reduce(function ( a, b ) {
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