@extends('template.app')

@section('title')
- Laporan Transaksi Berdasarkan Jenis Ziswaf
@endsection

@section('content')

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title"><strong>Laporan Transaksi Berdasarkan Jenis Ziswaf</strong></h3>
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
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="text-align: right">TOTAL:</th>
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

        var table = $('#tabel-jenis').DataTable({
            dom: 'Blfrtip',
            buttons: [
                {name: 'excelHtml5', extend: 'excelHtml5', text: 'Export to Excel', messageTop: 'Laporan Ziswaf Berdasarkan Lembaga', className: 'btn btn-default btn-sm', pageSize: 'A4', autoFilter: true, customize: function ( xlsx ){ var sheet = xlsx.xl.worksheets['sheet1.xml']; $('row c', sheet).attr( 's', '25' ); }, footer: true},
                {name: 'pdfHtml5', extend: 'pdfHtml5', text: 'Export to PDF', messageTop: 'Laporan Ziswaf Berdasarkan Lembaga', className: 'btn btn-default btn-sm', pageSize: 'A4', footer: true},
                {name: 'print', extend: 'print', text: 'Print', messageTop: 'Laporan Ziswaf Berdasarkan Lembaga', className: 'btn btn-default btn-sm', pageSize: 'A4', footer: true}
            ],
            "language": {
                "sEmptyTable": "DATA KOSONG ATAU TIDAK DITEMUKAN !",
                "sLengthMenu": "Tampilkan _MENU_ records",
                "sSearch": "Cari Data/Filter:",
            },
            "lengthMenu": [25, 50, 100],
            "columnDefs": [
                {"className": "dt-center", "targets": [0]},
                {"className": "dt-right", "targets": [4]}
            ],
            ajax: {
                url: "",
            },
            columns: [{
                data: "id",
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'nama_wilayah',
            },
            {
                data: 'nama_lembaga',
            },
            {
                data: 'nama_paket_zakat',
            },
            {
                data: 'jumlah',
                render: $.fn.dataTable.render.number( ',', '.', 0 )
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

        $('select').selectpicker();
        $('#pilih_wilayah').change(function() {
            table.ajax.url('/panziswil/laporan/jenisziswaf/getdata/'+$(this).val()).load();
        });
        $('#pilih_wilayah').trigger("change");
    });
</script>
@endpush
@endsection