@extends('template.app')

@section('title')
- Data Muzakki
@endsection

@section('content')

<div class="box box-info">

    <div class="box-header with-border">
        <div class="col-md-6">
            <h3 class="box-title"><strong>Data Muzakki</strong></h3>
        </div>
    </div>

    <div class="box-body">
        <div class="col-md-6" style="margin-bottom: 20px;">
            <button class="btn btn-primary btn-md" data-target="#modalTambah" data-toggle="modal">
                <i class="fa fa-plus"> Tambah Muzakki </i>
            </button>
        </div>
        <div class="dataTables_scrollBody">
            <div class="col-md-12">
                <table id="tabel-donatur" class="display" style="width: 100%">
                    <thead>
                        <tr class="bg-success">
                            <th width="3%"> No </th>
                            <th> ID Muzakki </th>
                            <th> Nama </th>
                            <th> Alamat </th>
                            <th> NPWP </th>
                            <th> No HP </th>
                            <th> Email </th>
                            <th width="15%"> Aksi </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Form Modal Start Detail Donatur -->
<div id="formModal-detail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLongTitle">Detail Data Muzakki</h5>
            </div>
            <div class="modal-body">
                <span id="form-result"></span>
                <form class="form-horizontal" role="form" action="javascript:void(0)" id="formDetail" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group ">
                        <label for="nama" class="col-sm-3 control-label">Nama Lengkap</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" name="nama" id="nama" readonly>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="alamat" class="col-sm-3 control-label">Alamat Lengkap</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" type="text" name="alamat" id="alamat" readonly></textarea>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="npwp" class="col-sm-3 control-label">NPWP</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" name="npwp" id="npwp" readonly>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="no_hp" class="col-sm-3 control-label">Nomor HP</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" name="no_hp" id="no_hp" readonly>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="email" class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" name="email" id="email" readonly>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="penghasilan" class="col-sm-3 control-label">Penghasilan Rata-rata PerBulan</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" name="penghasilan" id="penghasilan" readonly>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="tanggungan" class="col-sm-3 control-label">Jumlah Tanggungan (anak & lainnya)</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" name="tanggungan" id="tanggungan" readonly>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="status_rumah" class="col-sm-3 control-label">Status Rumah</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" name="status_rumah" id="status_rumah" readonly>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Kembali</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Form Modal End Detail Donatur -->

<!-- Form Modal Start Detail Donatur -->
<div id="modalTambah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLongTitle">Tambah Muzakki</h5>
            </div>
            <div class="modal-body">
                <span id="form-result"></span>
                <form class="form-horizontal" role="form" action="javascript:void(0)" id="formTambah" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group ">
                        <label for="nama" class="col-sm-3 control-label">Nama Lengkap<i style="color: red;">*</i></label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" name="nama" id="nama" placeholder="Masukkan Nama Lengkap..." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="alamat" class="col-sm-3 control-label">Alamat Lengkap<i style="color: red;">*</i></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="alamat" id="alamat" placeholder="Masukkan Alamat Lengkap..." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required></textarea>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="npwp" class="col-sm-3 control-label">NPWP</label>
                        <div class="col-sm-9">
                            <input type="number" maxlength="15" class="form-control" id="npwp" name="npwp" placeholder="Contoh: 999888777666555">
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="no_hp" class="col-sm-3 control-label">Nomor HP</label>
                        <div class="col-sm-9">
                            <input type="number" maxlength="13" class="form-control" id="no_hp" name="no_hp" placeholder="Contoh: 081122223333">
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="email" class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="email" name="email" id="email" placeholder="Masukkan Email..." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Penghasilan Rata-rata PerBulan</label>
                        <div class="col-sm-5">
                            <select id="penghasilan" name="penghasilan" class="selectpicker" data-live-search="true" title="Pilih..">
                                <option value="< 1.000.000"> < 1.000.000 </option>
                                <option value="1.000.000 s.d. 3.000.000"> 1.000.000 s.d. 3.000.000 </option>
                                <option value="3.000.001 s.d. 7.000.000"> 3.000.001 s.d. 7.000.000 </option>
                                <option value="7.000.001 s.d. 10.000.000"> 7.000.001 s.d. 10.000.000 </option>
                                <option value="10.000.001 s.d. 15.000.000"> 10.000.001 s.d. 15.000.000 </option>
                                <option value="15.000.001 s.d. 20.000.000"> 15.000.001 s.d. 20.000.000 </option>
                                <option value="> 20.000.001"> > 20.000.001 </option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="tanggungan" class="col-sm-3 control-label">Jumlah Tanggungan (anak & lainnya)</label>
                        <div class="col-sm-9">
                            <input type="number" maxlength="3" class="form-control" id="tanggungan" name="tanggungan" placeholder="Masukkan Jumlah Tanggungan...">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status Rumah</label>
                        <div class="col-sm-5">
                            <select id="status_rumah" name="status_rumah" class="selectpicker" data-live-search="true" title="Pilih..">
                                <option value="rumah sendiri"> Rumah Sendiri </option>
                                <option value="tinggal bersama orangtua"> Tinggal Bersama Orangtua </option>
                                <option value="kontrak"> Kontrak </option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                        <a id="ok-button" name="ok-button" class="btn btn-success btn-ok">Simpan</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Form Modal End Detail Donatur -->

<div id="modalEdit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Muzakki</h5>
            </div>
            <div class="modal-body">
                <span id="form-result"></span>
                <form class="form-horizontal" role="form" action="javascript:void(0)" id="formEdit" method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" id="id" name="id">

                    <div class="form-group ">
                        <label for="edit_nama" class="col-sm-3 control-label">Nama Lengkap<i style="color: red;">*</i></label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" name="edit_nama" id="edit_nama" placeholder="Masukkan Nama Lengkap..." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="edit_alamat" class="col-sm-3 control-label">Alamat Lengkap<i style="color: red;">*</i></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="edit_alamat" id="edit_alamat" placeholder="Masukkan Alamat Lengkap..." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required></textarea>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="edit_npwp" class="col-sm-3 control-label">NPWP</label>
                        <div class="col-sm-9">
                            <input type="number" maxlength="15" class="form-control" id="edit_npwp" name="edit_npwp" placeholder="Contoh: 999888777666555">
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="edit_no_hp" class="col-sm-3 control-label">Nomor HP</label>
                        <div class="col-sm-9">
                            <input type="number" maxlength="13" class="form-control" id="edit_no_hp" name="edit_no_hp" placeholder="Contoh: 081122223333">
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="edit_email" class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="email" name="edit_email" id="edit_email" placeholder="Masukkan Email...">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Penghasilan Rata-rata PerBulan</label>
                        <div class="col-sm-5">
                            <select id="edit_penghasilan" name="edit_penghasilan" class="selectpicker" data-live-search="true" title="Pilih..">
                                <option value="< 1.000.000"> < 1.000.000 </option>
                                <option value="1.000.000 s.d. 3.000.000"> 1.000.000 s.d. 3.000.000 </option>
                                <option value="3.000.001 s.d. 7.000.000"> 3.000.001 s.d. 7.000.000 </option>
                                <option value="7.000.001 s.d. 10.000.000"> 7.000.001 s.d. 10.000.000 </option>
                                <option value="10.000.001 s.d. 15.000.000"> 10.000.001 s.d. 15.000.000 </option>
                                <option value="15.000.001 s.d. 20.000.000"> 15.000.001 s.d. 20.000.000 </option>
                                <option value="> 20.000.001"> > 20.000.001 </option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="edit_tanggungan" class="col-sm-3 control-label">Jumlah Tanggungan (anak & lainnya)</label>
                        <div class="col-sm-9">
                            <input type="number" maxlength="3" class="form-control" id="edit_tanggungan" name="edit_tanggungan" placeholder="Masukkan Jumlah Tanggungan...">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status Rumah</label>
                        <div class="col-sm-5">
                            <select id="edit_status_rumah" name="edit_status_rumah" class="selectpicker" data-live-search="true" title="Pilih..">
                                <option value="rumah sendiri"> Rumah Sendiri </option>
                                <option value="tinggal bersama orangtua"> Tinggal Bersama Orangtua </option>
                                <option value="kontrak"> Kontrak </option>
                            </select>
                        </div>
                    </div>

                    <div class="box-footer">
                        <a id="edit-button" name="edit-button" class="btn btn-success btn-ok">Simpan</a>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
<script>
    $(document).ready(function() {

        var table = $('#tabel-donatur').DataTable({
            dom: 'lfrtip',
            "language": {
                "sEmptyTable": "DATA KOSONG ATAU TIDAK DITEMUKAN !",
                "sLengthMenu": "Tampilkan _MENU_ records",
                "sSearch": "Cari Data/Filter:",
            },
            ajax: {
                url: "{{ url('/duta/donatur/getdata') }}",
            },
            "columnDefs": [
                {"className": "dt-center", "targets": [0, 1, 4, 5, 7]}
            ],
            columns: [{
                data: "id",
                orderable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'id_donatur',
                name: 'id_donatur',
            },
            {
                data: 'nama',
                name: 'nama',
            },
            {
                data: 'alamat',
                name: 'alamat',
            },
            {
                data: 'npwp',
                name: 'npwp',
            },
            {
                data: 'no_hp',
                name: 'no_hp',
            },
            {
                data: 'email',
                name: 'email',
            },
            {
                data: 'aksi',
                name: 'aksi',
            }]
        });

        $(document).on('click', '.detail', function() {
            var id = $(this).attr('id');
            $.ajax({
                method: "GET",
                url: "/duta/donatur/detail/" + id,
                dataType: "json",
                success: function(data) {
                    $('#nama').val(data.nama);
                    $('#alamat').val(data.alamat);
                    $('#npwp').val(data.npwp);
                    $('#no_hp').val(data.no_hp);
                    $('#email').val(data.email);
                    $('#penghasilan').val(data.penghasilan);
                    $('#tanggungan').val(data.tanggungan);
                    $('#status_rumah').val(data.status_rumah);
                    $('#formModal-detail').modal('show');
                },
                error: function() {
                    alert('Error : Cannot get data!');
                }
            });
        });

        //Submit
        $('#ok-button').on('click', function(e) {
            e.preventDefault();
            let formData = new FormData(document.getElementById("formTambah"));
            $.ajax({
                type: "POST",
                url: "{{route('duta.simpanDonatur')}}",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#ok-button').text('Menyimpan...');
                },
                success: function (data) {
                    $('#formTambah')[0].reset();
                    var html = '';
                    alert("Data berhasil disimpan!")
                    html = '<div class="alert alert-default">' + data + '</div>';
                    $('#modalTambah').modal('hide');
                    location.reload();
                    $('#ok-button').text('Simpan');
                },
                error: function (data) {
                    $('#ok-button').text('Simpan');
                    var html = '';
                    alert("Data gagal disimpan, silahkan di cek kembali dan jangan ada data kosong!")
                    html = '<div class="alert alert-danger">' + data + '</div>';
                }
            })
        });

        $(document).on('click', '.edit', function() {
            var id = $(this).attr('id');
            $.ajax({
                method: "GET",
                url: "/duta/donatur/edit/" + id,
                dataType: "json",
                success: function(data) {
                    $('.selectpicker').selectpicker('refresh');
                    $('#id').val(id);
                    $('#edit_nama').val(data.nama);
                    $('#edit_alamat').val(data.alamat);
                    $('#edit_npwp').val(data.npwp);
                    $('#edit_no_hp').val(data.no_hp);
                    $('#edit_email').val(data.email);
                    $('#edit_tanggungan').val(data.tanggungan);
                    $('select[id=edit_penghasilan]').selectpicker('val', data.penghasilan);
                    $('select[id=edit_status_rumah]').selectpicker('val', data.status_rumah);
                    $('#modalEdit').modal('show');
                },
                error: function() {
                    alert('Error : Cannot get data!');
                }
            });
        });

        //Submit
        $('#edit-button').on('click', function(e) {
            e.preventDefault();
            let formData = new FormData(document.getElementById("formEdit"));
            $.ajax({
                type: "POST",
                url: "{{route('duta.updateDonatur')}}",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#edit-button').text('Menyimpan...');
                },
                success: function (data) {
                    $('#formEdit')[0].reset();
                    var html = '';
                    alert("Data berhasil disimpan!")
                    html = '<div class="alert alert-default">' + data + '</div>';
                    $('#modalEdit').modal('hide');
                    $('#edit-button').text('Simpan');
                    location.reload();
                },
                error: function (data) {
                    $('#edit-button').text('Simpan');
                    var html = '';
                    alert("Data gagal disimpan, silahkan di cek kembali dan jangan ada data kosong!")
                    html = '<div class="alert alert-danger">' + data + '</div>';
                }
            })
        });

    });
</script>
@endpush

@endsection