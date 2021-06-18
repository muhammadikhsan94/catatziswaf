@extends('template.app')

@section('title')
- Laporan Realisasi Paket Ziswaf Berdasarkan Duta Zakat
@endsection

@section('content')

<div class="box box-info">

    <div class="box-header with-border">
        <h3 class="box-title"><strong>Laporan Realisasi Paket Ziswaf Berdasarkan Duta Zakat</strong></h3>
    </div>

    <form class="form-horizontal">
        <div class="box-body">
            <div class="col-md-6">
                <label for="pilih_wilayah" class="control-label">WILAYAH:</label>
                <select id="pilih_wilayah" data-size="5" class="selectpicker col-sm-5" data-live-search="true" data-style="btn-success">
                    <option value="0">Tampil Semua</option>
                    @foreach($data['wil'] as $wilayah)
                    <option value="{{ $wilayah->id }}">{{ $wilayah->nama_wilayah }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    <div class="box-body">
        <div class="dataTables_scrollBody">
            <div class="col-md-12">
                <table id="tabel-realisasi" class="display" style="width: 100%;">
                    <thead>
                        <tr class="bg-success">
                            <th rowspan="2" width="5%"> No </th>
                            <th rowspan="2"> Wilayah </th>
                            <th rowspan="2"> Paket Ziswaf </th>
                            <th colspan="4"> <center>LEMBAGA</center> </th>
                            <th rowspan="2" width="13%"> Jumlah </th>
                        </tr>
                        <tr class="bg-success">
                            <th width="13%"> IZI </th>
                            <th width="13%"> LAZDAI </th>
                            <th width="13%"> YAYASAN </th>
                            <th width="13%"> DANA MANDIRI </th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th style="text-align:right">TOTAL:</th>
                            <th style="text-align: right"></th>
                            <th style="text-align: right"></th>
                            <th style="text-align: right"></th>
                            <th style="text-align: right"></th>
                            <th style="text-align: right"></th>
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
<script src="https://cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
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
        
        var table = $('#tabel-realisasi').DataTable({
            dom: 'Blfrtip',
            buttons: [
                {name: 'excelHtml5', extend: 'excelHtml5', text: 'Export to EXCEL', messageTop: 'Laporan Realisasi Paket Ziswaf Berdasarkan Duta Zakat - PANZISWIL', className: 'btn btn-default btn-sm', pageSize: 'A4', autoFilter: true, customize: function ( xlsx ){ var sheet = xlsx.xl.worksheets['sheet1.xml']; $('row c', sheet).attr( 's', '25' ); }, footer: true},
                {name: 'pdfHtml5', extend: 'pdfHtml5', text: 'Export to PDF', messageTop: 'Laporan Realisasi Paket Ziswaf Berdasarkan Duta Zakat - PANZISWIL', className: 'btn btn-default btn-sm', pageSize: 'A4', footer: true},
                {name: 'print', extend: 'print', text: 'PRINT', messageTop: 'Laporan Realisasi Paket Ziswaf Berdasarkan Duta Zakat - PANZISWIL', className: 'btn btn-default btn-sm', pageSize: 'A4', footer: true}
            ],
            "language": {
                "sEmptyTable": "DATA KOSONG ATAU TIDAK DITEMUKAN !",
                "sLengthMenu": "Tampilkan _MENU_ records",
                "sSearch": "Cari Data/Filter:",
            },
            "columnDefs": [
                {"className": "dt-center", "targets": [0]},
                {"className": "dt-right", "targets": [3, 4, 5, 6, 7]},
            ],
            ajax: {
                url: "",
            },
            "lengthMenu": [25, 50, 100],
            "orderFixed": [0, 'asc'],
            columns: [
                { data: "id", render: function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                { data: 'wilayah' },
                { data: 'paket' },
                { data: 'izi', render: $.fn.dataTable.render.number( ',', '.', 0 ) },
                { data: 'lazdai', render: $.fn.dataTable.render.number( ',', '.', 0 ) },
                { data: 'yayasan', render: $.fn.dataTable.render.number( ',', '.', 0 ) },
                { data: 'dana_mandiri', render: $.fn.dataTable.render.number( ',', '.', 0 ) },
                { data: 'jumlah', render: $.fn.dataTable.render.number( ',', '.', 0 ) },
            ],
            'rowsGroup': [1],
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
                $(api.column(7).footer()).html(
                    api.column(7).data().reduce(function ( a, b ) {
                        a = parseInt(a.toString().replace(/,.*|[^0-9]/g, ''), 10);
                        b = parseInt(b.toString().replace(/,.*|[^0-9]/g, ''), 10);
                        total = a+b;
                        return convertToRupiah(total);
                    }, 0)
                );
            }
        });

        $('select').selectpicker();
        $('#pilih_wilayah').change(function() {
            table.ajax.url('/panziswil/laporan/realisasi-paketziswaf-dutazakat/getdata/'+$(this).val()).load();
        });
        $('#pilih_wilayah').trigger("change");
    });
</script>
@endpush
@endsection