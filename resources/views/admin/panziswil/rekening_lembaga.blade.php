@extends('template.app')

@section('title')
- Master Rekening Lembaga
@endsection

@section('content')

<div class="box box-info">

    <div class="box-header with-border">
        <div class="col-md-6">
            <h3 class="box-title"><strong>Data Rekening Lembaga</strong></h3>
        </div>
        <div class="col-md-6">
            <a type="button" id="tambah_rekening_lembaga" class="btn btn-success btn-sm pull-right">
                <i class="fa fa-plus"> Tambah Rekening Lembaga </i>
            </a>
        </div>
    </div>

    <div class="box-body">

        <div class="dataTables_scrollBody">
            <div class="col-md-12">
                <table id="tabel_rekening_lembaga" class="display" style="width: 100%">
                    <thead>
                        <tr class="bg-success">
                            <th> No </th>
                            <th> Lembaga </th>
                            <th> No Rekening </th>
                            <th width="13%"> <center>Aksi</center> </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Form Modal Tambah distribusi -->
<div id="formModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLongTitle">Tambah Distribusi</h5>
            </div>
            <div class="modal-body">
                <span id="form-result"></span>
                <form class="form-horizontal" role="form" action="javascript:void(0)" id="formSimpan" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group ">
                        <label for="id_lembaga" class="col-sm-3 control-label">Lembaga</label>
                        <div class="col-sm-5">
                            <select data-size="5" id="id_lembaga" name="id_lembaga" class="selectpicker" data-live-search="true" title="Pilih Nama Lembaga.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                                @foreach($data['lembaga'] as $key => $lembaga)
                                <option value="{{ $lembaga->id }}">{{ $lembaga->nama_lembaga }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="norek" class="col-sm-3 control-label">Nomor Rekening</label>
                        <div class="col-sm-5">
                            <input class="form-control" id="norek" name="norek" placeholder="Masukkan Nomor Rekening">
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
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Rekening Lembaga</h5>
            </div>
            <div class="modal-body">
                <span id="form-result"></span>
                <form class="form-horizontal" role="form" action="javascript:void(0)" id="formEdit" method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="edit_lembaga" name="edit_lembaga">

                    <div class="form-group ">
                        <label for="lembaga" class="col-sm-3 control-label">Nama Lembaga</label>
                        <div class="col-sm-5">
                            <input class="form-control" id="lembaga" name="lembaga" readonly>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="edit_norek" class="col-sm-3 control-label">No Rekening</label>
                        <div class="col-sm-5">
                            <input class="form-control" id="edit_norek" name="edit_norek" placeholder="Masukkan Nomor Rekening">
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

        //select picker
        $('select').selectpicker();

        var table = $('#tabel_rekening_lembaga').DataTable({
            dom: 'lfrtip',
            "language": {
                "sEmptyTable": "DATA KOSONG ATAU TIDAK DITEMUKAN !",
                "sLengthMenu": "Tampilkan _MENU_ records",
                "sSearch": "Cari Data:",
            },
            ajax: {
                url: "{{ url('/panziswil/rekening-lembaga/getdata') }}",
            },
            columns: [{
                data: "id",
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'nama_lembaga',
                name: 'nama_lembaga',
            },
            {
                data: 'norek',
                name: 'norek',
            },
            {
                data: 'aksi',
                name: 'aksi',
                orderable: false,
            }]
        });

        //Modal Add
        $('#tambah_rekening_lembaga').click(function () {
            $('#form-result').html('');
            $('#formModal').modal('show');
        })

        //Simpan Data Jenis Transaksi
        $('#formSimpan').submit(function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{route('panziswil.simpanRekeningLembaga')}}",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    var html = '';
                    alert("Data berhasil disimpan !");
                    html = '<div class="alert alert-success">' + data + '</div>';
                    $('#formSimpan')[0].reset();
                    $('#formModal').modal('hide');
                    // table.draw();
                    window.location.reload();
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
                url: "/panziswil/rekening-lembaga/delete/" + user_id,
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
                            $('#tabel_rekening_lembaga').DataTable().ajax.reload();
                            alert(errorMessage);
                        } else {
                            $('#confirmModal').modal('hide');
                            window.location.reload();
                            // $('#tabel_rekening_lembaga').DataTable().ajax.reload();
                            alert('Distribusi berhasil dihapus...');
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
                url: "/panziswil/rekening-lembaga/edit/" + id,
                dataType: "json",
                success: function(data) {
                    $('#id').val(id);
                    $('#edit_lembaga').val(data.id_lembaga);
                    $('#lembaga').val(data.nama_lembaga);
                    $('#edit_norek').val(data.norek);
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
                url: "{{route('panziswil.updateRekeningLembaga')}}",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    var html = '';
                    alert("Data berhasil diubah !");
                    html = '<div class="alert alert-success">' + data + '</div>';
                    $('#formEdit')[0].reset();
                    $('#tabel_rekening_lembaga').DataTable().ajax.reload();
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