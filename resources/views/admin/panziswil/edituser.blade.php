@extends('template.app')

@section('title')
- Edit User
@endsection

@section('content')
<div class="box box-info">
    <div class="box-header with-border">
            <h3 class="box-title">Input Data User</h3>
            <button type="button" class="btn btn-danger btn-xs mb-4" data-toggle="modal" data-target="#resetPassword" style="float: right;">
                Reset Password
            </button>
    </div>
    <form id="editUser" class="form-horizontal" method="post" action="javascript:void(0)" enctype="multipart/form-data">
        @csrf
        <div class="box-body">

            <input type="hidden" name="id" id="id" value="{{ $data['editUser']->id }}">
            <input type="hidden" name="id_wilayah" id="id_wilayah" value="{{ $data['editUser']->id_wilayah }}">

            <div class="form-group " id="tambah_nama_donatur">
                <label for="no_punggung" class="col-sm-3 control-label">Nomor Punggung</label>
                <div class="col-sm-5">
                    <input class="form-control" id="no_punggung" name="no_punggung" value="{{ $data['editUser']->no_punggung }}" readonly="">
                </div>
            </div>

            <div class="form-group ">
                <label for="nama" class="col-sm-3 control-label">Nama Lengkap</label>
                <div class="col-sm-5">
                    <input class="form-control" id="nama" name="nama" value="{{ $data['editUser']->nama }}" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                </div>
            </div>

            <div class="form-group ">
                <label for="alamat" class="col-sm-3 control-label">Alamat Lengkap</label>
                <div class="col-sm-5">
                    <input class="form-control" id="alamat" name="alamat" value="{{ $data['editUser']->alamat }}" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                </div>
            </div>

            <div class="form-group ">
                <label for="npwp" class="col-sm-3 control-label">NPWP</label>
                <div class="col-sm-5">
                    <input type="number" maxlength="15" class="form-control" id="npwp" name="npwp" value="{{ $data['editUser']->npwp }}" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')">
                </div>
            </div>

            <div class="form-group ">
                <label for="no_hp" class="col-sm-3 control-label">Nomor HP</label>
                <div class="col-sm-5">
                    <input type="number" maxlength="13" class="form-control" id="no_hp" name="no_hp" value="{{ $data['editUser']->no_hp }}" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                </div>
            </div>

            <div class="form-group ">
                <label for="email" class="col-sm-3 control-label">Email</label>
                <div class="col-sm-5">
                    <input type="email" class="form-control" id="email" name="email" value="{{ $data['editUser']->email }}" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                </div>
            </div>

            <div class="form-group " id="edit-jabatan">
                <label for="id_jabatan" class="col-sm-3 control-label">Jabatan</label>
                <div class="col-sm-5">
                    <select data-size="5" id="jabatan_id" name="jabatan_id[]" class="selectpicker" data-live-search="true" title="Pilih Jabatan.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" multiple>
                        @foreach($data['jabatan'] as $key => $jabatan)
                            <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group " id="edit-manajer">
                <label for="manajer_id" class="col-sm-3 control-label">Koordinator Manajer Group</label>
                <div class="col-sm-9">
                    <select data-size="5" id="manajer_id" name="manajer_id" class="selectpicker" data-live-search="true" title="Pilih Manajer.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')">
                        @foreach($data['manajer'] as $key => $manajer)
                            <option value="{{ $manajer->id }}">{{ $manajer->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group " id="edit-group">
                <label for="group_id" class="col-sm-3 control-label">Kode Grup</label>
                <div class="col-sm-5">
                    <select data-size="5" id="group_id" name="group_id" class="selectpicker" data-live-search="true" title="Pilih Group.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')">
                        @foreach($data['group'] as $key => $group)
                            <option value="{{ $group->id }}">{{ $group->id }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group " id="edit-spv">
                <label for="manajer_id" class="col-sm-3 control-label">Koordinator Manajer Area</label>
                <div class="col-sm-5">
                    <select data-size="5" id="spv_id" name="spv_id" class="selectpicker" data-live-search="true" title="Pilih Koordinator Manajer Area.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')">
                        @foreach($data['manajerarea'] as $key => $spv)
                            <option value="{{ $spv->id }}">{{ $spv->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group " id="edit-panzisda">
                <label for="panzisda_id" class="col-sm-3 control-label">Koordinator Panzisda</label>
                <div class="col-sm-5">
                    <select data-size="5" id="panzisda_id" name="panzisda_id" class="selectpicker" data-live-search="true" title="Pilih Panzisda.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')">
                        @foreach($data['panzisda'] as $key => $panzisda)
                            <option value="{{ $panzisda->id }}">{{ $panzisda->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="form-group " id="lembaga">
                <label for="edit_lembaga" class="col-sm-3 control-label">Lembaga</label>
                <div class="col-sm-5">
                    <select data-size="5" id="edit_lembaga" name="edit_lembaga" class="selectpicker" data-live-search="true" title="Pilih Manajer.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')">
                        @foreach($data['lembaga'] as $key => $lembaga)
                        <option value="{{ $lembaga->id }}">{{ $lembaga->nama_lembaga }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- <div class="form-group ">
                <label for="surat_tugas" class="col-sm-3 control-label">Surat Tugas</label>
                <div class="col-sm-5">
                    <input class="form-control" id="surat_tugas" name="surat_tugas" type="file" oninvalid="this.setCustomValidity('data tidak boleh kosong!')">
                    <div id="file_surat_tugas" style="padding: 10px 0;"><a href="{{ asset('/surat_tugas/'.$data['editUser']->surat_tugas) }}" target="_blank" type="button" class="btn btn-default">LIHAT</a></div>
                </div>
            </div> -->

            <div class="modal-footer">
                <a type="button" id="batal" name="batal" class="btn btn-secondary" href="{{route('panziswil.user')}}">Batal</a>
                <button type="submit" class="submit btn btn-primary">Simpan</button>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="resetPassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="post" action="{{ route('panziswil.resetPassword') }}" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    KONFIRMASI
                </div>
                <div class="modal-body">

                    {{ csrf_field() }}

                    <input type="hidden" id="id" name="id" value="{{ $data['editUser']->id }}">

                    Anda akan mereset password pengguna <b>"{{ $data['editUser']->no_punggung }}"</b>. Klik <b>Reset Password</b> untuk melanjutkannya.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-success">Reset Password</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                KONFIRMASI
            </div>
            <div class="modal-body">
                Yakin ingin menyimpan? jika belum yakin, silahkan di cek kembali datanya..
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                <a id="ok-button" name="ok-button" class="btn btn-success btn-ok">Simpan</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript">

    $(document).ready(function() {

        var role = <?php 
            echo json_encode($data['role']->toArray());
        ?>;

        $(document).on('click', '.batal', function() {
            window.location = "/panziswil/user/";
        });

        var tmp = <?php echo json_encode($data['tmp']); ?>;
        $('select[id=jabatan_id').selectpicker('val', tmp);

        //select picker
        $('select').selectpicker();
        $('#lembaga').hide();
        $('#edit-manajer').hide();
        $('#edit-group').hide();
        $('#edit-panzisda').hide();
        $('#edit-spv').hide();

        $('#jabatan_id').change(function() {
            var data = $('#jabatan_id').val();

            //Initial State
            $('#edit-manajer').hide();
            $('#edit-group').hide();
            $('#edit-spv').hide();
            $('#edit-panzisda').hide();
            $('#lembaga').hide();
            $('#panzisda_id').removeAttr('required','');
            $('#spv_id').removeAttr('required','');
            $('#manajer_id').removeAttr('required','');
            $('#group_id').removeAttr('required','');
            $('#edit_lembaga').removeAttr('required','');

            for (var i in data) {

                if (data[i] == 3) {
                    $('#edit-panzisda').show();
                    $('#panzisda_id').attr('required','');
                } else if (data[i] == 4) {
                    $('#edit-spv').show();
                    $('#spv_id').attr('required','');
                } else if (data[i] == 5) {
                    $('#edit-manajer').show();
                    $('#edit-group').show();
                    $('#manajer_id').attr('required','');
                    $('#group_id').attr('required','');
                } else if (data[i] == 6) {
                    $('#lembaga').show();
                    $('#edit_lembaga').attr('required','');
                }
            }
        });
        $("#jabatan_id").trigger("change");

        //button click
        for (var i = 0; i <= tmp.length; i++) {

            if (tmp[i] == 6) {
                $('#lembaga').show();
                $('select[id=edit_lembaga]').val(role[i].id_lembaga);
            }
            if (tmp[i] == 5) {
                $('#edit-manajer').show();
                $('#edit-group').show();
                $('select[id=manajer_id]').val(role[i].id_atasan);
                $('select[id=group_id]').val(role[i].id_group);
            }
            if (tmp[i] == 4) {
                $('#edit-spv').show();
                $('select[id=spv_id]').val(role[i].id_atasan);
            } 
            if (tmp[i] == 3) {
                $('#edit-panzisda').show();
                $('select[id=panzisda_id]').val(role[i].id_atasan);
            }
            $('.selectpicker').selectpicker('refresh');
        }

        //Submit
        $('#editUser').submit(function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{route('panziswil.updateUser')}}",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    var html = '';
                    alert("Data berhasil diupdate!");
                    html = '<div class="alert alert-success">' + data + '</div>';
                    $('#editUser')[0].reset();
                    window.location.replace("{{url('/panziswil/user')}}");
                },
                error: function (data) {
                    var html = '';
                    alert("Data gagal diupdate, silahkan dicek kembali!")
                    html = '<div class="alert alert-danger">' + data + '</div>';
                }
            })
        });

    });
</script>
@endpush
@endsection