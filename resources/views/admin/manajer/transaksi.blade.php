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
                            <th> Tgl Trf/Kirim </th>
                            <th> Muzakki </th>
                            <th> Duta Zakat </th>
                            <th> Lembaga </th>
                            <th> Paket Zakat </th>
                            <th> Jenis Transaksi </th>
                            <th> Total Jumlah </th>
                            <th> Status </th>
                            <th> <center>Aksi</center> </th>
                        </tr> 
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="text-align:right">TOTAL:</th>
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

<!-- Validasi Transaksi -->
<div id="modalValid" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                <form class="form-horizontal" role="form" id="formValid" method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" id="idTrx1" name="idTrx1">
                    <input type="hidden" name="setujui" id="setujui" value="OK">

                    Yakin ingin memvalidasi transaksi ini ?

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Validasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Validasi Transaksi End -->

<!-- Validasi Transaksi -->
<div id="modalNonValid" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                <form class="form-horizontal" role="form" id="formNonValid" method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" id="idTrx2" name="idTrx2">
                    <input type="hidden" name="setujui" id="setujui" value="BATAL">

                    <div class="form-group">
                        <label for="komentar" class="col-md-3 control-label">Komentar</label>
                        <div class="col-md-9">
                            <textarea class="form-control" id="komentar" name="komentar" placeholder="Masukkan komentar"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Kirim</button>
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
                <form class="form-horizontal" role="form" enctype="multipart/form-data">
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
                    
                    <div class="form-group">
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
                        <textarea class="form-control" id="keterangan" name="keterangan" disabled></textarea>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="bukti_transaksi" class="col-sm-3 control-label">Bukti Transaksi</label>
                        <div class="col-sm-9">
                            <a id="pop" target=new><img name="bukti_transaksi" id="bukti_transaksi" alt="bukti transaksi" style="width: 100%;" /></a>
                        </div>
                    </div>

                    <div class="form-group" id="tampil_komentar">
                        <label for="komentar" class="col-sm-3 control-label">Komentar</label>
                        <div class="col-sm-9">
                        <textarea class="form-control" id="komentar_manajer" name="komentar_manajer" disabled></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger mr-5" id="btnNonValid" onclick="NonValidFunction()">Tidak Valid</button>
                        <button type="button" class="btn btn-primary mr-5" id="btnValid" onclick="ValidFunction()">Valid</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Form Modal End Edit Manajer -->

@push('scripts')
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
<script>

    var id_trx;

    function ValidFunction()
    {
        $('#btnNonValid').removeAttr("data-target", "#modalNonValid");
        $('#btnNonValid').removeAttr("data-toggle", "modal");
        $('#btnValid').attr("data-target", "#modalValid");
        $('#btnValid').attr("data-toggle", "modal");
        $('#formDetail').modal('hide');
        $('#idTrx1').val(id_trx);
    }

    function NonValidFunction()
    {
        $('#btnNonValid').attr("data-target", "#modalNonValid");
        $('#btnNonValid').attr("data-toggle", "modal");
        $('#btnValid').removeAttr("data-target", "#modalValid");
        $('#btnValid').removeAttr("data-toggle", "modal");
        $('#formDetail').modal('hide');
        $('#idTrx2').val(id_trx);
    }

    $(document).ready(function() {

        function convertToRupiah(angka)
        {
            var rupiah = '';		
            var angkarev = angka.toString().split('').reverse().join('');
            for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
            return 'Rp. '+rupiah.split('',rupiah.length-1).reverse().join('');
        }

        var table = $('#tabel-transaksi').DataTable({
            dom: 'lfrtip',
            "language": {
                "sEmptyTable": "DATA KOSONG ATAU TIDAK DITEMUKAN !",
                "sLengthMenu": "Tampilkan _MENU_ records",
                "sSearch": "Cari Data/Filter:",
            },
            ajax: {
                url: "{{ url('/manajer/transaksi/getdata') }}",
            },
            "columnDefs": [
                {"className": "dt-center", "targets": [7, 8]}
            ],
            "order": [[ 8, "asc" ], [ 0, "asc" ]],
            columns: [{
                data: "transaks.id",
                orderable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'tanggal_transfer',
                name: 'tanggal_transfer',
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
                data: 'jenis_transaksi',
                name: 'jenis_transaksi',
            },
            {
                data: 'jumlah',
                name: 'jumlah',
            },
            {
                data: 'status',
                name: 'status',
            },
            {
                data: 'aksi',
                name: 'aksi',
            }],
            footerCallback: function( tfoot, data, start, end, display ) {
                var api = this.api();
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

        //Submit
        $('#formValid').submit(function (e) {
            e.preventDefault();
            let formDataValid = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{route('manajer.updateStatus')}}",
                data: formDataValid,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#formValid')[0].reset();
                    $('#tabel-transaksi').DataTable().ajax.reload();
                    $('#modalValid').modal('hide');
                },
                error: function (data) {
                    var html = '';
                    alert("Data gagal disimpan, silahkan di cek kembali dan jangan ada data kosong!")
                    html = '<div class="alert alert-danger">' + data + '</div>';
                }
            })
        });

        $('#formNonValid').submit(function (e) {
            e.preventDefault();
            let formDataNonValid = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{route('manajer.updateStatus')}}",
                data: formDataNonValid,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#formNonValid')[0].reset();
                    $('#tabel-transaksi').DataTable().ajax.reload();
                    $('#modalNonValid').modal('hide');
                },
                error: function (data) {
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
                url: "/manajer/transaksi/detail/" + id,
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
                    $('#tanggal_transfer').val(data.tanggal_transfer);
                    if(data.jenis_transaksi == 'barang' || data.jenis_transaksi == 'BARANG') {
                        $('#output_barang').show();
                        $('#nama_barang').val(data.nama_barang);
                        $('#output_bank').hide();
                    } else if(data.jenis_transaksi == 'transfer' || data.jenis_transaksi == 'TRANSFER') {
                        $('#output_barang').hide();
                        $('#output_bank').show();
                        $('#rek_bank').val(data.rek_bank);
                    } else {
                        $('#output_barang').hide();
                        $('#output_bank').hide();
                    }
                    $('#jumlah').val(konversi(data.jumlah));
                    $('#keterangan').val(data.keterangan);
                    $('#bukti_transaksi').attr('src','/bukti/'+ data.bukti_transaksi);
                    $('#pop').attr('href','/bukti/'+ data.bukti_transaksi);
                    $('#formDetail').modal('show');

                    if (data.komentar == null) {
                        $('#tampil_komentar').hide();
                    } else {
                        $('#tampil_komentar').show();
                        $('#komentar_manajer').val(data.komentar);
                    }

                    if (data.manajer_status == null) {
                        $('#btnValid').show();
                        $('#btnNonValid').show();
                    } else {
                        $('#btnValid').hide();
                        $('#btnNonValid').hide();
                    }

                    id_trx = id;
                },
                error: function() {
                    alert('Error : Cannot get data!');
                }
            });
        });

    });

</script>
@endpush
@endsection