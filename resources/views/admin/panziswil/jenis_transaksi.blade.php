@extends('template.app')

@section('title')
- Master Jenis Transaksi
@endsection

@section('content')

<div class="box box-info">

    <div class="box-header with-border">
        <div class="col-md-6">
            <h3 class="box-title"><strong>Data Jenis Transaksi</strong></h3>
        </div>
        <div class="col-md-6">
            <a type="button" id="tambah_jenis_transaksi" class="btn btn-success btn-sm pull-right">
                <i class="fa fa-plus"> Tambah Jenis Transaksi </i>
            </a>
        </div>
    </div>

    <div class="box-body">

        <div class="dataTables_scrollBody">
            <div class="col-md-12">
                <table id="tabel_jenis_transaksi" class="display" style="width: 100%">
                    <thead>
                        <tr class="bg-success">
                            <th> No </th>
                            <th> Jenis Transaksi </th>
                            <th width="13%"> <center>Aksi</center> </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Form Modal Tambah jenis-transaksi -->
<div id="formModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLongTitle">Tambah Jenis Transaksi</h5>
            </div>
            <div class="modal-body">
                <span id="form-result"></span>
                <form class="form-horizontal" role="form" action="javascript:void(0)" id="formSimpan" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group ">
                        <label for="jenis_transaksi" class="col-sm-3 control-label">Nama Jenis Transaksi</label>
                        <div class="col-sm-5">
                            <input class="form-control" id="jenis_transaksi" name="jenis_transaksi" placeholder="Masukkan Nama Jenis Transaksi">
                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="formModal1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Jenis Transaksi</h5>
            </div>
            <div class="modal-body">
                <span id="form-result"></span>
                <form class="form-horizontal" role="form" action="javascript:void(0)" id="formEdit" method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" id="id" name="id">

                    <div class="form-group ">
                        <label for="edit_jenis_transaksi" class="col-sm-3 control-label">Nama Jenis Transaksi</label>
                        <div class="col-sm-5">
                            <input class="form-control" id="edit_jenis_transaksi" name="edit_jenis_transaksi">
                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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

        var table = $('#tabel_jenis_transaksi').DataTable({
            dom: 'lfrtip',
            "language": {
                "sEmptyTable": "DATA KOSONG ATAU TIDAK DITEMUKAN !",
                "sLengthMenu": "Tampilkan _MENU_ records",
                "sSearch": "Cari Data:",
            },
            ajax: {
                url: "{{ url('/panziswil/jenis-transaksi/getdata') }}",
            },
            columns: [{
                data: "id",
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'jenis_transaksi',
                name: 'jenis_transaksi',
            },
            {
                data: 'aksi',
                name: 'aksi',
                orderable: false,
            }]
        });

        //Modal Add
        $('#tambah_jenis_transaksi').click(function () {
            $('#form-result').html('');
            $('#formModal').modal('show');
        })

        //Simpan Data Jenis Transaksi
        $('#formSimpan').submit(function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{route('panziswil.simpanJenisTransaksi')}}",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    var html = '';
                    alert("Data berhasil disimpan !");
                    html = '<div class="alert alert-success">' + data + '</div>';
                    $('#formSimpan')[0].reset();
                    $('#formModal').modal('hide');
                    table.draw();
                },
                error: function (data) {
                    var html = '';
                    alert("Data gagal disimpan, silahkan dicek kembali!")
                    html = '<div class="alert alert-danger">' + data + '</div>';
                }
            })
        });

        $(document).on('click', '.delete', function() {
            user_id = $(this).attr('id');
            $('#confirmModal').modal('show');
        });

        $('#ok-button').click(function() {
            $.ajax({
                url: "/panziswil/jenis-transaksi/delete/" + user_id,
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
                            $('#tabel_jenis_transaksi').DataTable().ajax.reload();
                            alert(errorMessage);
                        } else {
                            $('#confirmModal').modal('hide');
                            $('#tabel_jenis_transaksi').DataTable().ajax.reload();
                            alert('Jenis Transaksi berhasil dihapus...');
                            $('#ok-button').text('Hapus');
                        }
                    }, 2000);
                }
            })
        });

        $(document).on('click', '.edit', function() {
            var id = $(this).attr('id');
            $.ajax({
                method: "GET",
                url: "/panziswil/jenis-transaksi/edit/" + id,
                dataType: "json",
                success: function(data) {
                    $('#id').val(id);
                    $('#edit_jenis_transaksi').val(data.jenis_transaksi);
                    $('#button-edit').val('Edit');
                    $('#formModal1').modal('show');
                },
                error: function() {
                    alert('Error : Cannot get data!');
                }
            });
        });

        //Submit
        $('#formEdit').submit(function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{route('panziswil.updateJenisTransaksi')}}",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    var html = '';
                    alert("Data berhasil diubah !");
                    html = '<div class="alert alert-success">' + data + '</div>';
                    $('#formEdit')[0].reset();
                    $('#tabel_jenis_transaksi').DataTable().ajax.reload();
                    $('#formModal1').modal('hide');
                },
                error: function (data) {
                    var html = '';
                    alert("Data gagal diubah, silahkan dicek kembali!")
                    html = '<div class="alert alert-danger">' + data + '</div>';
                }
            })
        });
    });
</script>
@endpush
@endsection