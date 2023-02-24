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
        <div class="col-md-6" style="margin-bottom: 20px;">
            <a class="btn btn-primary btn-md" href="{{url('duta/transaksi/tambah')}}">
                <i class="fa fa-plus"> Tambah Transaksi </i>
            </a>
        </div>

        <div class="dataTables_scrollBody">
            <div class="col-md-12">
                <table id="tabel-transaksi" class="display" style="width: 100%">
                    <thead>
                        <tr class="bg-success">
                            <th> No </th>
                            <th> Tgl Trf/Kirim </th>
                            <th> Muzakki </th>
                            <th> Lembaga </th>
                            <th> Paket Zakat </th>
                            <th> Jenis Transaksi </th>
                            <th> Total Jumlah </th>
                            <th> Status </th>
                            <th style="text-align:center"> Aksi </th>
                        </tr> 
                    </thead>
                    <tfoot>
                        <tr>
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
                        <div class="col-sm-9">
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
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Form Modal End Edit Manajer -->

<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                KONFIRMASI
            </div>
            <div class="modal-body">
                Yakin ingin menghapus?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <a name="ok-button" id="ok-button" class="btn btn-danger btn-ok">Hapus</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
<script>
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
                url: "{{ url('duta/transaksi/getdata') }}",
            },
            "order": [[ 7, "asc" ]],
            "columnDefs": [
                {"className": "dt-center", "targets": [6, 7]}
            ],
            columns: [{
                data: "id",
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

        $(document).on('click', '.detail', function() {
            var id = $(this).attr('id');
            $.ajax({
                method: "GET",
                url: "{{ url('duta/transaksi/detail') }}/" + id,
                dataType: "json",
                success: function(data) {
                    $('#print').attr('href', '/kuitansi/'+data.id+'.pdf');
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
                    if(data.jenis_transaksi == 'barang') {
                        $('#output_barang').show();
                        $('#nama_barang').val(data.nama_barang);
                        $('#output_bank').hide();
                    } else if(data.jenis_transaksi == 'transfer') {
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
                    $('#button-edit').val('Edit');
                    $('#formDetail').modal('show');

                    if (data.komentar == null) {
                        $('#tampil_komentar').hide();
                    } else {
                        $('#tampil_komentar').show();
                        $('#komentar_manajer').val(data.komentar);
                    }
                },
                error: function() {
                    alert('Error : Cannot get data!');
                }
            });
        });

        $(document).on('click', '.edit', function() {
            var id = $(this).attr('id');
            window.location = "{{ url('duta/transaksi/edit') }}/"+id;
        });
        
        $(document).on('click', '.bukti', function() {
            var id = $(this).attr('id');
            $('.bukti').attr('target', 'new');
            $('.bukti').attr('href', "/duta/transaksi/bukti/"+id);
            // window.location = "/duta/transaksi/bukti/"+id;
        });

        $(document).on('click', '.delete', function() {
            user_id = $(this).attr('id');
            $('#confirmModal').modal('show');
        });

        $('#ok-button').click(function() {
            $.ajax({
                url: "{{ url('duta/transaksi/delete') }}/" + user_id,
                method: "DELETE",
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                beforeSend: function() {
                    $('#ok-button').text('Menghapus...');
                },
                success: function(data) {
                    setTimeout(function() {
                        if (data.errors) {
                            errorMessage = '';
                            for (var count = 0; count < data.errors.length; count++) {
                                errorMessage += data.errors[count];
                            }
                            $('#confirmModal').modal('hide');
                            $('#tabel-transaksi').DataTable().ajax.reload();
                            alert(errorMessage);
                        } else {
                            $('#confirmModal').modal('hide');
                            $('#tabel-transaksi').DataTable().ajax.reload();
                            alert('Data Deleted');
                            $('#ok-button').text('Hapus');
                        }
                    }, 2000);
                }
            })
        });

    });
</script>
@endpush
@endsection