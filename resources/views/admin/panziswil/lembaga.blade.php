@extends('template.app')
@section('title')
- Data Lembaga
@endsection

@section('content')

<div class="box box-info">

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button> 
            <strong>{{ $message }}</strong>
        </div>
    @endif

    <div class="box-header with-border">
        <div class="col-md-6">
            <h3 class="box-title"><strong>Data Lembaga</strong></h3>
        </div>

        <div class="col-md-6">
            <a type="button" id="tambah-lembaga" class="btn btn-success btn-sm pull-right">
                <i class="fa fa-plus"> Tambah Lembaga </i>
            </a>
        </div>
    </div>

    <div class="box-body">
        <div class="dataTables_scrollBody">
            <div class="col-md-12">
                <table id="tabel-lembaga" class="display" style="width: 100%">
                    <thead>
                        <tr class="bg-success">
                            <th> No </th>
                            <th> Nama Lembaga </th>
                            <th> Jenis </th>
                            <th> Status </th>
                            <th> Wilayah </th>
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
                <h5 class="modal-title" id="exampleModalLongTitle">Tambah Lembaga</h5>
            </div>
            <div class="modal-body">
                <span id="form-result"></span>
                <form class="form-horizontal" role="form" action="javascript:void(0)" id="formSimpan" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group ">
                        <label for="nama_lembaga" class="col-sm-3 control-label">Nama Lembaga</label>
                        <div class="col-sm-5">
                            <input class="form-control" id="nama_lembaga" name="nama_lembaga" placeholder="Masukkan Nama Lembaga" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="jenis" class="col-sm-3 control-label">Jenis</label>
                        <div class="col-sm-5">
                            <select data-size="5" id="jenis" name="jenis" class="selectpicker" data-live-search="true" title="Pilih Jenis.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                                <option value="cabang">Cabang</option>
                                <option value="mandiri">Mandiri</option>
                                <option value="mitra">Mitra</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="status" class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-5">
                            <select data-size="5" id="status" name="status" class="selectpicker" data-live-search="true" title="Pilih Status.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                                <option value="khusus">Khusus</option>
                                <option value="umum">Umum</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="pilih_wilayah">
                        <label for="id_wilayah" class="col-sm-3 control-label">Pilih Wilayah</label>
                        <div class="col-sm-5">
                            <select data-size="5" id="id_wilayah" name="id_wilayah[]" class="selectpicker" data-live-search="true" title="Pilih Wilayah.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" multiple>
                                @foreach($data['wilayah'] as $ket => $wilayah)
                                <option value="{{ $wilayah->id }}">{{ $wilayah->nama_wilayah }}</option>
                                @endforeach
                            </select>
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
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Lembaga</h5>
            </div>
            <div class="modal-body">
                <span id="form-result"></span>
                <form class="form-horizontal" role="form" action="javascript:void(0)" id="formEdit" method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" id="id" name="id">

                    <div class="form-group ">
                        <label for="edit_lembaga" class="col-sm-3 control-label">Nama Lembaga</label>
                        <div class="col-sm-5">
                            <input class="form-control" id="edit_lembaga" name="edit_lembaga" placeholder="Masukkan Nama Lembaga" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="edit_jenis" class="col-sm-3 control-label">Jenis</label>
                        <div class="col-sm-5">
                            <select data-size="5" id="edit_jenis" name="edit_jenis" class="selectpicker" data-live-search="true" title="Pilih Jenis.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                                <option value="cabang">Cabang</option>
                                <option value="mandiri">Mandiri</option>
                                <option value="mitra">Mitra</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="edit_status" class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-5">
                            <select data-size="5" id="edit_status" name="edit_status" class="selectpicker" data-live-search="true" title="Pilih Status.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                                <option value="khusus">Khusus</option>
                                <option value="umum">Umum</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="ubah_wilayah">
                        <label for="edit_wilayah" class="col-sm-3 control-label">Pilih Wilayah</label>
                        <div class="col-sm-5">
                            <select data-size="5" id="edit_wilayah" name="edit_wilayah[]" class="selectpicker" data-live-search="true" title="Pilih Wilayah.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" multiple>
                                @foreach($data['wilayah'] as $ket => $wilayah)
                                <option value="{{ $wilayah->id }}">{{ $wilayah->nama_wilayah }}</option>
                                @endforeach
                            </select>
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

        var table = $('#tabel-lembaga').DataTable({
            dom: 'lfrtip',
            "language": {
                "sEmptyTable": "DATA KOSONG ATAU TIDAK DITEMUKAN !",
                "sLengthMenu": "Tampilkan _MENU_ records",
                "sSearch": "Cari Data:",
            },
            ajax: {
                url: "{{ url('/panziswil/lembaga/getdata') }}",
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
                data: 'jenis',
                name: 'jenis',
            },
            {
                data: 'status',
                name: 'status',
            },
            {
                data: 'wilayah',
                name: 'wilayah',
            },
            {
                data: 'aksi',
                name: 'aksi',
                orderable: false,
            }]
        });

        $('#pilih_wilayah').hide();
        $('#ubah_wilayah').hide();

        $('select[id=jenis]').change(function() {
            if ($(this).val() == 'cabang') {
                $('#pilih_wilayah').show();
                $('select[id=status]').selectpicker('val', 'khusus');
                $('#id_wilayah').attr('required', '');
            } else if ($(this).val() == 'umum') {
                $('select[id=status]').selectpicker('val', 'umum');
                $('#id_wilayah').removeAttr('required', '');
                $('#pilih_wilayah').hide();
            } else {
                $('select[id=status]').selectpicker('val', 'umum');
                $('#id_wilayah').removeAttr('required', '');
                $('#pilih_wilayah').hide();
            }
            $('.selectpicker').selectpicker('refresh');
        });

        $('select[id=edit_jenis]').change(function() {
            if ($(this).val() == 'cabang') {
                $('#ubah_wilayah').show();
                $('select[id=edit_status]').selectpicker('val', 'khusus');
                $('#edit_wilayah').attr('required', '');
            } else if ($(this).val() == 'umum') {
                $('select[id=edit_status]').selectpicker('val', 'umum');
                $('#edit_wilayah').removeAttr('required', '');
                $('#ubah_wilayah').hide();
            } else {
                $('select[id=edit_status]').selectpicker('val', 'umum');
                $('#edit_wilayah').removeAttr('required', '');
                $('#ubah_wilayah').hide();
            }
            $('.selectpicker').selectpicker('refresh');
        });

        //Modal Add
        $('#tambah-lembaga').click(function () {
            $('#form-result').html('');
            $('#formModal').modal('show');
        })

        //Simpan Data Wilayah
        $('#formSimpan').submit(function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{route('panziswil.simpanLembaga')}}",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    var html = '';
                    alert("Data lembaga berhasil ditambahkan !");
                    html = '<div class="alert alert-success">' + data + '</div>';
                    $('#formSimpan')[0].reset();
                    $('#formModal').modal('hide');
                    window.location.reload();
                    $('.selectpicker').selectpicker('refresh');
                },
                error: function (data) {
                    var html = '';
                    alert("Data lembaga gagal disimpan silahkan di cek kembali !");
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
                url: "/panziswil/lembaga/delete/" + user_id,
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
                            $('#tabel-lembaga').DataTable().ajax.reload();
                            alert(errorMessage);
                        } else {
                            $('#confirmModal').modal('hide');
                            $('#tabel-lembaga').DataTable().ajax.reload();
                            alert('Lembaga berhasil dihapus...');
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
                url: "/panziswil/lembaga/edit/" + id,
                dataType: "json",
                success: function(data) {
                    $('#ubah_wilayah').show();
                    $('#id').val(id);
                    $('#edit_lembaga').val(data.nama_lembaga);
                    $('select[id=edit_jenis]').val(data.jenis);
                    $('select[id=edit_status]').val(data.status);

                    if (data.status == 'khusus') {
                        if (data.id_wilayah == null) {
                            $('select[id=edit_wilayah]').selectpicker('val', '');
                        } else {
                            $('select[id=edit_wilayah]').selectpicker('val', data.id_wilayah.split(','));
                        }
                    } else {
                        $('#ubah_wilayah').hide();
                        $('#edit_wilayah').removeAttr('required','');
                    }
                    
                    $('#button-edit').val('Edit');
                    $('#formModal1').modal('show');
                    $('.selectpicker').selectpicker('refresh');
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
                url: "{{route('panziswil.updateLembaga')}}",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    var html = '';
                    alert("Data lembaga berhasil diubah !");
                    html = '<div class="alert alert-success">' + data + '</div>';
                    $('#formEdit')[0].reset();
                    $('#tabel-lembaga').DataTable().ajax.reload();
                    $('#formModal1').modal('hide');
                    $('.selectpicker').selectpicker('refresh');
                },
                error: function (data) {
                    var html = '';
                    alert("Data lembaga gagal disimpan, silahkan di cek kembali !");
                    html = '<div class="alert alert-danger">' + data + '</div>';
                }
            })
        });
    });
</script>
@endpush
@endsection