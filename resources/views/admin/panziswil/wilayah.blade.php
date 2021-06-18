@extends('template.app')

@section('title')
- Data Wilayah
@endsection

@section('content')

<div class="box box-info">

    <div class="box-header with-border">
        <div class="col-md-6">
            <h3 class="box-title"><strong>Data Transaksi</strong></h3>
        </div>
        <div class="col-md-6">
            <a type="button" id="tambah-wilayah" class="btn btn-success btn-sm pull-right">
                <i class="fa fa-plus"> Tambah Wilayah </i>
            </a>
        </div>
    </div>

    <div class="box-body">

        <div class="dataTables_scrollBody">
            <div class="col-md-12">
                <table id="tabel-wilayah" class="display" style="width: 100%">
                    <thead>
                        <tr class="bg-success">
                            <th> No </th>
                            <th> Nama Wilayah </th>
                            <th width="13%"> <center>Aksi</center> </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Form Modal Tambah Wilayah -->
<div id="formModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLongTitle">Tambah Wilayah</h5>
            </div>
            <div class="modal-body">
                <span id="form-result"></span>
                <form class="form-horizontal" role="form" action="javascript:void(0)" id="formSimpan" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group ">
                        <label for="nama_wilayah" class="col-sm-3 control-label">Nama Wilayah</label>
                        <div class="col-sm-5">
                            <input class="form-control" id="nama_wilayah" name="nama_wilayah" placeholder="Masukkan Nama Wilayah">
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
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Wilayah</h5>
            </div>
            <div class="modal-body">
                <span id="form-result"></span>
                <form class="form-horizontal" role="form" action="javascript:void(0)" id="formEdit" method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" id="id" name="id">

                    <div class="form-group ">
                        <label for="edit_wilayah" class="col-sm-3 control-label">Nama Wilayah</label>
                        <div class="col-sm-5">
                            <input class="form-control" id="edit_wilayah" name="edit_wilayah" placeholder="Masukkan Nama Wilayah">
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

        var table = $('#tabel-wilayah').DataTable({
            dom: 'lfrtip',
            "language": {
                "sEmptyTable": "DATA KOSONG ATAU TIDAK DITEMUKAN !",
                "sLengthMenu": "Tampilkan _MENU_ records",
                "sSearch": "Cari Data:",
            },
            ajax: {
                url: "{{ url('/panziswil/wilayah/getdata') }}",
            },
            columns: [{
                data: "id",
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'nama_wilayah',
                name: 'nama_wilayah',
            },
            {
                data: 'aksi',
                name: 'aksi',
                orderable: false,
            }]
        });

        //Modal Add
        $('#tambah-wilayah').click(function () {
            $('#form-result').html('');
            $('#formModal').modal('show');
        })

        //Simpan Data Wilayah
        $('#formSimpan').submit(function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{route('panziswil.simpanWilayah')}}",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    var html = '';
                    alert("Data successfully added or edited !");
                    html = '<div class="alert alert-success">' + data + '</div>';
                    $('#formSimpan')[0].reset();
                    $('#formModal').modal('hide');
                    table.draw();
                },
                error: function (data) {
                    var html = '';
                    alert("Data not saved!, Check it again!")
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
                url: "/panziswil/wilayah/delete/" + user_id,
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
                            $('#tabel-wilayah').DataTable().ajax.reload();
                            alert(errorMessage);
                        } else {
                            $('#confirmModal').modal('hide');
                            $('#tabel-wilayah').DataTable().ajax.reload();
                            alert('Wilayah berhasil dihapus...');
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
                url: "/panziswil/wilayah/edit/" + id,
                dataType: "json",
                success: function(data) {
                    $('#id').val(id);
                    $('#edit_wilayah').val(data.nama_wilayah);
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
                url: "{{route('panziswil.updateWilayah')}}",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    var html = '';
                    alert("Data successfully added or edited !");
                    html = '<div class="alert alert-success">' + data + '</div>';
                    $('#formEdit')[0].reset();
                    $('#tabel-wilayah').DataTable().ajax.reload();
                },
                error: function (data) {
                    var html = '';
                    alert("Data not saved!, Check it again!")
                    html = '<div class="alert alert-danger">' + data + '</div>';
                }
            })
        });
    });
</script>
@endpush
@endsection