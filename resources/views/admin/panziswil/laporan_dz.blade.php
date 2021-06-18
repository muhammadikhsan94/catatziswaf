@extends('template.app')

@section('title')
- Laporan Data Duta Zakat
@endsection

@section('content')

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title"><strong>Laporan Data Duta Zakat</strong></h3>
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
                url: "{{ url('/panziswil/laporan/dutazakat/getdata') }}",
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
    });
</script>
@endpush
@endsection