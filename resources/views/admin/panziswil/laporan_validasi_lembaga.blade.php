@extends('template.app')

@section('title')
- Laporan Monitoring Validasi Data Transaksi LAZIS
@endsection

@section('content')

<div class="box box-info">

    <div class="box-header with-border">
        <h3 class="box-title"><strong>Laporan Monitoring Validasi LAZIS</strong></h3>
    </div>

    <div class="box-body">
        <div class="dataTables_scrollBody">
            <div class="col-md-12">
                <table id="tabel-validasi" class="display" style="width: 100%">
                    <thead>
                        <tr class="bg-success">
                            <th> No </th>
                            <th> Nama LAZIS </th>
                            <th width="13%"> Transaksi Duta Zakat </th>
                            <th width="13%"> Validasi Manajer Group </th>
                            <th width="13%"> Validasi Panzisda </th>
                            <th width="13%"> Validasi Lazis </th>
                            <th> % Data Valid </th>
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
        
        var asal = <?php echo json_encode($data['user']) ?>;

        var table = $('#tabel-validasi').DataTable({
            dom: 'Blfrtip',
            buttons: [
                {name: 'excelHtml5', extend: 'excelHtml5', text: 'Export to EXCEL', messageTop: 'Laporan Data Duta Zakat - Kabupaten/Kota '+asal.nama_wilayah, className: 'btn btn-default btn-sm', pageSize: 'A4', autoFilter: true, customize: function ( xlsx ){ var sheet = xlsx.xl.worksheets['sheet1.xml']; $('row c', sheet).attr( 's', '25' ); }, footer: true},
                {name: 'pdfHtml5', extend: 'pdfHtml5', text: 'Export to PDF', messageTop: 'Laporan Data Duta Zakat - Kabupaten/Kota '+asal.nama_wilayah, className: 'btn btn-default btn-sm', pageSize: 'A4', footer: true},
                {name: 'print', extend: 'print', text: 'PRINT', messageTop: 'Laporan Data Duta Zakat - Kabupaten/Kota '+asal.nama_wilayah, className: 'btn btn-default btn-sm', pageSize: 'A4', footer: true}
            ],
            "language": {
                "sEmptyTable": "DATA KOSONG ATAU TIDAK DITEMUKAN !",
                "sLengthMenu": "Tampilkan _MENU_ records",
                "sSearch": "Cari Data/Filter:",
            },
            "lengthMenu": [25, 50, 100],
            "columnDefs": [
                {"className": "dt-center", "targets": [0]},
                {"className": "dt-right", "targets": [2, 3, 4, 5, 6]}
            ],
            ajax: {
                url: "{{ url('/panziswil/laporan/validasi/lembaga/getdata') }}",
            },
            columns: [{
                data: "id",
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'nama_lembaga',
            },
            {
                data: 'total',
                render: $.fn.dataTable.render.number( ',', '.', 0 )
            },
            {
                data: 'valid_mg',
                render: $.fn.dataTable.render.number( ',', '.', 0 )
            },
            {
                data: 'valid_pz',
                render: $.fn.dataTable.render.number( ',', '.', 0 )
            },
            {
                data: 'valid_lz',
                render: $.fn.dataTable.render.number( ',', '.', 0 )
            },
            {
                data: 'persentase',
            }],
            footerCallback: function( tfoot, data, start, end, display ) {
                var api = this.api();
                var lazis = 0;
                var sumTotal = 0;
                $(api.column(2).footer()).html(
                    api.column(2).data().reduce(function ( a, b ) {
                        a = parseInt(a.toString().replace(/,.*|[^0-9]/g, ''), 10);
                        b = parseInt(b.toString().replace(/,.*|[^0-9]/g, ''), 10);
                        total = a+b;
                        sumTotal = total;
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
                        lazis = total;
                        return convertToRupiah(total);
                    }, 0)
                );
                $(api.column(6).footer()).html(
                    api.column(6).reduce(function () {
                        total = (lazis / sumTotal) * 100;
                        return total.toFixed(2) + ' %';
                    }, 0 + ' %')
                );
            }
        });
    });
</script>
@endpush
@endsection