@extends('template.app')

@section('title')
- Edit Pengguna
@endsection

@section('content')
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Input Data User</h3>
    </div>
    <form id="editUser" class="form-horizontal" method="post" action="javascript:void(0)" enctype="multipart/form-data">
        @csrf
        <div class="box-body">

            <input type="hidden" name="id" id="id" value="{{ $data['editUser']->id }}">
            <input type="hidden" name="id_jabatan" id="id_jabatan" value="{{ $data['editUser']->id_jabatan }}">
            <input type="hidden" name="id_wilayah" id="id_wilayah" value="{{ $data['editUser']->id_wilayah }}">
            <input type="hidden" name="id_atasan" id="id_atasan" value="{{ $data['editUser']->id_atasan }}">
            <input type="hidden" name="id_group" id="id_group" value="{{ $data['editUser']->id_group }}">
            <input type="hidden" name="password" id="password" value="{{ $data['editUser']->password }}">


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
                    <textarea class="form-control" id="alamat" name="alamat" placeholder="Masukkan Alamat" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>{{ $data['editUser']->alamat }}</textarea>
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

            <div class="form-group ">
                <label for="new_password" class="col-sm-3 control-label">Password Baru</label>
                <div class="col-sm-5">
                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Masukkan Password Baru" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')">
                </div>
            </div>

            <div class="modal-footer">
                <a type="button" id="batal" name="batal" class="btn btn-secondary" href="{{url('/')}}">Batal</a>
                <button type="submit" class="submit btn btn-primary">Simpan</button>
            </div>
        </div>
    </form>
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

        $( "#npwp" ).on('input', function() {
            if ($(this).val().length>15) {
                alert('Nomor NPWP tidak lebih dari 15 !');       
            }
        });

        $( "#no_hp" ).on('input', function() {
            if ($(this).val().length>13) {
                alert('Nomor HP tidak lebih dari 13 !');       
            }
        });

        $( "#new_password" ).on('change', function() {
            if ($(this).val().length<8) {
                alert('Password harus terdiri dari 8 kata !');       
            }
        });

        $(document).on('click', '.batal', function() {
            window.location = "/duta";
        });

        //Submit
        $('#editUser').submit(function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{route('lazis.updateProfil')}}",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    var html = '';
                    alert("Data berhasil di update!");
                    html = '<div class="alert alert-success">' + data + '</div>';
                    $('#editUser')[0].reset();
                    window.location.replace("{{url('/')}}");
                },
                error: function (data) {
                    var html = '';
                    alert("Data gagal diupdate, silahkan dicek kembali!")
                    html = '<div class="alert alert-danger">' + data + '</div>';
                }
            })
        })

    });
</script>
@endpush
@endsection