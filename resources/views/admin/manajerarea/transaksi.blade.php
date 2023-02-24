@extends('template.app')

@section('title')
- Data Transaksi
@endsection

@section('content')

<div class="box box-info">

    <div class="box-header with-border">
        <div class="col-md-6">
            <h3 class="box-title"><strong>Data Transaksi</strong></h3>
        </div>
    </div>

    <div class="box-body">

        <div class="dataTables_scrollBody">
            <div class="col-md-12">

                <table id="tabel-transaksi" class="display" style="width: 100%">
                    <thead>
                        <tr class="bg-success">
                            <th> No </th>
                            <th> Muzakki </th>
                            <th> Duta Zakat </th>
                            <th> Penyalur </th>
                            <th> Paket </th>
                            <th> Item </th>
                            <th> Jumlah </th>
                            <th width="14%"> <center>Aksi</center> </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Validasi Transaksi -->
<div id="validasi-transaksi" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLongTitle">KONFIRMASI</h5>
            </div>
            <div class="modal-body">
                <span id="form-result"></span>
                <form class="form-horizontal" role="form" action="javascript:void(0)" id="formValidasi" method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" id="id" name="id">
                    <input type="hidden" name="setujui" id="setujui" value="OK">

                    Yakin ingin memvalidasi transaksi ini ?

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button id="ok-button" name="ok-button" type="submit" class="btn btn-success">Validasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Validasi Transaksi End -->

<!-- Form Modal Start Edit Manajer -->
<div id="formDetail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLongTitle">Detail Transaksi</h5>
            </div>
            <div class="modal-body">
                <span id="form-result"></span>
                <form class="form-horizontal" role="form" action="javascript:void(0)" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="id" id="id">

                    <div class="form-group ">
                        <label for="nama_donatur" class="col-sm-3 control-label">Nama Muzakki</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="nama_donatur" name="nama_donatur" disabled>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="lembaga_penyalur" class="col-sm-3 control-label">Lembaga Penyalur</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="lembaga_penyalur" name="lembaga_penyalur" disabled>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="jenis_transaksi" class="col-sm-3 control-label">Jenis Transaksi</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="jenis_transaksi" name="jenis_transaksi" disabled>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="jumlah_paket" class="col-sm-3 control-label">Jumlah Paket</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="jumlah_paket" name="jumlah_paket" disabled>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="paket_zakat" class="col-sm-3 control-label">Detail Paket Zakat</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="paket_zakat" name="paket_zakat" contentEditable disabled></textarea>
                        </div>
                    </div>

                    <div class="form-group " id="output_barang">
                        <label for="nama_barang" class="col-sm-3 control-label">Nama Barang</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="nama_barang" name="nama_barang" disabled>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="jumlah" class="col-sm-3 control-label">Total Jumlah</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <span class="input-group-addon">Rp.</span>
                                <input type="text" class="form-control" id="jumlah" name="jumlah" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="form-group " id="output_bank">
                        <label for="rek_bank" class="col-sm-3 control-label">Rekening Bank</label>
                        <div class="col-sm-5">
                            <input class="form-control" id="rek_bank" name="rek_bank" disabled>
                        </div>
                    </div>
                    
                    <div class="form-group" id="transfer">
                        <label for="tanggal_transfer" class="col-sm-3 control-label">Tanggal Transfer/Kirim</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <span class="input-group-addon glyphicon glyphicon-th"></span>
                                <input class="form-control" id="tanggal_transfer" name="tanggal_transfer" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="keterangan" class="col-sm-3 control-label">Keterangan</label>
                        <div class="col-sm-5">
                            <input class="form-control" id="keterangan" name="keterangan" disabled>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="bukti_transaksi" class="col-sm-3 control-label">Bukti Transaksi</label>
                        <div class="col-sm-9">
                            <a class="pop"><img name="bukti_transaksi" id="bukti_transaksi" alt="bukti transaksi" style="width: 100%;cursor:zoom-in;" /></a>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Form Modal End Edit Manajer -->

@push('scripts')
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script>
    $(document).ready(function() {

        var table = $('#tabel-transaksi').DataTable({
            responsive: true,
            processing: true,
            scrollCollapse: true,
            serverSide: true,
            lengthMenu: [10, 25, 50],
            "language": {
                "sEmptyTable": "DATA KOSONG ATAU TIDAK DITEMUKAN !",
                "sLengthMenu": "Tampilkan _MENU_ records",
                "sSearch": "Cari Data:",
            },
            ajax: {
                url: "{{ url('manajerarea/transaksi/getdata') }}",
            },
            columns: [{
                data: "transaks.id",
                orderable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'donatur',
                name: 'donatur',
            },
            {
                data: 'user',
                name: 'user',
            },
            {
                data: 'lembaga',
                name: 'lembaga',
            },
            {
                data: 'paket',
                name: 'paket',
            },
            {
                data: 'item',
                name: 'item',
            },
            {
                data: 'jumlah',
                name: 'jumlah',
            },
            {
                data: 'aksi',
                name: 'aksi',
            }]
        });

        $(document).on('click', '.validasi', function() {
            var id = $(this).attr('id');
            $.ajax({
                method: "GET",
                url: "{{ url('manajerarea/transaksi') }}/" + id,
                dataType: "json",
                success: function(data) {
                    $('#id').val(id);
                    $('#setujui').val(data.setujui);
                    $('#validasi-transaksi').modal('show');
                },
                error: function() {
                    alert('Error : Cannot get data!');
                }
            });
        });

        //Submit
        $('#formValidasi').submit(function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{route('manajerarea.updateStatus')}}",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#ok-button').text('Validasi...');
                },
                success: function (data) {
                    $('#formValidasi')[0].reset();
                    $('#tabel-transaksi').DataTable().ajax.reload();
                    $('#validasi-transaksi').modal('hide');
                    $('#ok-button').text('Validasi');
                },
                error: function (data) {
                    $('#ok-button').text('Validasi');
                    var html = '';
                    alert("Data gagal disimpan, silahkan di cek kembali dan jangan ada data kosong!")
                    html = '<div class="alert alert-danger">' + data + '</div>';
                }
            })
        });

        $(document).on('click', '.detail', function() {
            var id = $(this).attr('id');
            $.ajax({
                method: "GET",
                url: "{{ url('manajerarea/transaksi/detail') }}/" + id,
                dataType: "json",
                success: function(data) {
                    $('#id').val(id);
                    $('#nama_donatur').val(data.donatur);
                    $('#lembaga_penyalur').val(data.lembaga);

                    var paket = '';
                    for(x in data.detail) {
                        if(x == 0) {
                            paket = data.detail[x].nama_paket_zakat + ': ' + convertToRupiah(data.detail[x].jumlah);
                        } else {
                            paket = paket + '\n' + data.detail[x].nama_paket_zakat + ': ' + convertToRupiah(data.detail[x].jumlah);
                        }
                    }
                    
                    $('#paket_zakat').val(paket);
                    $('#jumlah_paket').val(data.jumlah_paket);
                    $('#jenis_transaksi').val(data.jenis_transaksi);
                    if(data.jenis_transaksi == 'barang') {
                        $('#output_barang').show();
                        $('#nama_barang').val(data.nama_barang);
                        $('#output_bank').hide();
                        $('#transfer').hide();
                    } else if(data.jenis_transaksi == 'non tunai') {
                        $('#output_barang').hide();
                        $('#output_bank').show();
                        $('#transfer').show();
                        $('#tanggal_transfer').val(data.tanggal_transfer);
                        $('#rek_bank').val(data.rek_bank);
                    } else {
                        $('#output_barang').hide();
                        $('#output_bank').hide();
                        $('#transfer').hide();
                    }
                    $('#jumlah').val(konversi(data.jumlah));
                    $('#keterangan').val(data.keterangan);
                    $('#bukti_transaksi').attr('src','/bukti/'+ data.bukti_transaksi);
                    $('#button-edit').val('Edit');
                    $('#formDetail').modal('show');
                },
                error: function() {
                    alert('Error : Cannot get data!');
                }
            });
        });

    });

</script>\
@endpush
@endsection