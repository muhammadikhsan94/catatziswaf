@extends('template.app')

@section('title')
- Master Distribusi
@endsection

@section('content')

<div class="box box-info">

    <div class="box-header with-border">
        <div class="col-md-6">
            <h3 class="box-title"><strong>Data Distribusi</strong></h3>
        </div>
        <div class="col-md-6">
            <a type="button" id="tambah_distribusi" class="btn btn-success btn-sm pull-right">
                <i class="fa fa-plus"> Tambah Distribusi </i>
            </a>
        </div>
    </div>

    <div class="box-body">

        <div class="dataTables_scrollBody">
            <div class="col-md-12">
                <table id="tabel_distribusi" class="display" style="width: 100%">
                    <thead>
                        <tr class="bg-success">
                            <th> No </th>
                            <th> Jenis Paket Zakat </th>
                            <th> Panzisnas (%) </th>
                            <th> Panziswil (%) </th>
                            <th> Panzisda (%) </th>
                            <th> Cabang (%) </th>
                            <th> Mitra Strategis (%) </th>
                            <th> Duta (%) </th>
                            <th width="13%"> Aksi </th>
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
                        <label for="id_paket_zakat" class="col-sm-3 control-label">Paket Zakat</label>
                        <div class="col-sm-5">
                            <select data-size="5" id="id_paket_zakat" name="id_paket_zakat" class="selectpicker" data-live-search="true" title="Pilih Paket Zakat.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                                @foreach($data['paket'] as $key => $paket)
                                <option value="{{ $paket->id }}">{{ $paket->nama_paket_zakat }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="panzisnas" class="col-sm-3 control-label">Panzisnas</label>
                        <div class="col-sm-5">
                            <input class="form-control" id="panzisnas" name="panzisnas" placeholder="Masukkan Persentase Panzisnas" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="panziswil" class="col-sm-3 control-label">Panziswil</label>
                        <div class="col-sm-5">
                            <input class="form-control" id="panziswil" name="panziswil" placeholder="Masukkan Persentase Panziswil" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="panzisda" class="col-sm-3 control-label">Panzisda</label>
                        <div class="col-sm-5">
                            <input class="form-control" id="panzisda" name="panzisda" placeholder="Masukkan Persentase Panzisda" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="cabang" class="col-sm-3 control-label">Cabang</label>
                        <div class="col-sm-5">
                            <input class="form-control" id="cabang" name="cabang" placeholder="Masukkan Persentase Cabang" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="mitra_strategis" class="col-sm-3 control-label">Mitra Strategis</label>
                        <div class="col-sm-5">
                            <input class="form-control" id="mitra_strategis" name="mitra_strategis" placeholder="Masukkan Persentase Mitra Strategis" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="duta" class="col-sm-3 control-label">Duta</label>
                        <div class="col-sm-5">
                            <input class="form-control" id="duta" name="duta" placeholder="Masukkan Persentase Duta" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
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
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Distribusi</h5>
            </div>
            <div class="modal-body">
                <span id="form-result"></span>
                <form class="form-horizontal" role="form" action="javascript:void(0)" id="formEdit" method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="edit_paket_zakat" name="edit_paket_zakat">

                    <div class="form-group ">
                        <label for="paket_zakat" class="col-sm-3 control-label">Nama Paket Zakat</label>
                        <div class="col-sm-5">
                            <input class="form-control" id="paket_zakat" name="paket_zakat" readonly>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="edit_panzisnas" class="col-sm-3 control-label">Panzisnas</label>
                        <div class="col-sm-5">
                            <input class="form-control" id="edit_panzisnas" name="edit_panzisnas" placeholder="Masukkan Persentase Panzisnas" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="edit_panziswil" class="col-sm-3 control-label">Panziswil</label>
                        <div class="col-sm-5">
                            <input class="form-control" id="edit_panziswil" name="edit_panziswil" placeholder="Masukkan Persentase Panziswil" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="edit_panzisda" class="col-sm-3 control-label">Panzisda</label>
                        <div class="col-sm-5">
                            <input class="form-control" id="edit_panzisda" name="edit_panzisda" placeholder="Masukkan Persentase Panzisda" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="edit_cabang" class="col-sm-3 control-label">Cabang</label>
                        <div class="col-sm-5">
                            <input class="form-control" id="edit_cabang" name="edit_cabang" placeholder="Masukkan Persentase Cabang" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="edit_mitra_strategis" class="col-sm-3 control-label">Mitra Strategis</label>
                        <div class="col-sm-5">
                            <input class="form-control" id="edit_mitra_strategis" name="edit_mitra_strategis" placeholder="Masukkan Persentase Mitra Strategis" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="edit_duta" class="col-sm-3 control-label">Duta</label>
                        <div class="col-sm-5">
                            <input class="form-control" id="edit_duta" name="edit_duta" placeholder="Masukkan Persentase Duta" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
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

        var table = $('#tabel_distribusi').DataTable({
            dom: 'lfrtip',
            "language": {
                "sEmptyTable": "DATA KOSONG ATAU TIDAK DITEMUKAN !",
                "sLengthMenu": "Tampilkan _MENU_ records",
                "sSearch": "Cari Data:",
            },
            ajax: {
                url: "{{ url('/panziswil/distribusi/getdata') }}",
            },
            "columnDefs": [
                {"className": "dt-center", "targets": [0, 2, 3, 4, 5, 6, 7, 8]}
            ],
            columns: [{
                data: "id",
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'nama_paket_zakat',
                name: 'nama_paket_zakat',
            },
            {
                data: 'panzisnas',
                name: 'panzisnas',
            },
            {
                data: 'panziswil',
                name: 'panziswil',
            },
            {
                data: 'panzisda',
                name: 'panzisda',
            },
            {
                data: 'cabang',
                name: 'cabang',
            },
            {
                data: 'mitra_strategis',
                name: 'mitra_strategis',
            },
            {
                data: 'duta',
                name: 'duta',
            },
            {
                data: 'aksi',
                name: 'aksi',
                orderable: false,
            }]
        });

        //Modal Add
        $('#tambah_distribusi').click(function () {
            $('#form-result').html('');
            $('#formModal').modal('show');
        })

        //Simpan Data Jenis Transaksi
        $('#formSimpan').submit(function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{route('panziswil.simpanDistribusi')}}",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    var html = '';
                    alert("Data berhasil disimpan !");
                    html = '<div class="alert alert-success">' + data + '</div>';
                    $('#formSimpan')[0].reset();
                    $('#formModal').modal('hide');
                    window.location.reload();
                    // table.draw();
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
                url: "/panziswil/distribusi/delete/" + user_id,
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
                            $('#tabel_distribusi').DataTable().ajax.reload();
                            alert(errorMessage);
                        } else {
                            $('#confirmModal').modal('hide');
                            window.location.reload();
                            // $('#tabel_distribusi').DataTable().ajax.reload();
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
                url: "/panziswil/distribusi/edit/" + id,
                dataType: "json",
                success: function(data) {
                    $('#id').val(id);
                    $('#edit_paket_zakat').val(data.id_paket_zakat);
                    $('#paket_zakat').val(data.nama_paket_zakat);
                    $('#edit_panzisnas').val(data.panzisnas);
                    $('#edit_panziswil').val(data.panziswil);
                    $('#edit_panzisda').val(data.panzisda);
                    $('#edit_cabang').val(data.cabang);
                    $('#edit_mitra_strategis').val(data.mitra_strategis);
                    $('#edit_duta').val(data.duta);
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
                url: "{{route('panziswil.updateDistribusi')}}",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    var html = '';
                    alert("Data berhasil diubah !");
                    html = '<div class="alert alert-success">' + data + '</div>';
                    $('#formEdit')[0].reset();
                    window.location.reload();
                    // $('#tabel_distribusi').DataTable().ajax.reload();
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