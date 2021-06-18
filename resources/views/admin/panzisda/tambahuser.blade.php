@extends('template.app')

@section('title')
- Tambah User
@endsection

@section('content')
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Input Data User</h3>
    </div>
    <form id="tambah_user" class="form-horizontal" method="post" action="javascript:void(0)" enctype="multipart/form-data">
        @csrf
        <div class="box-body">

            <div class="form-group ">
                <label for="nama" class="col-sm-3 control-label">Nama Lengkap</label>
                <div class="col-sm-5">
                    <input class="form-control" id="nama" name="nama" placeholder="Masukkan Nama"  oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                </div>
            </div>

            <div class="form-group ">
                <label for="alamat" class="col-sm-3 control-label">Alamat</label>
                <div class="col-sm-5">
                    <textarea class="form-control" id="alamat" name="alamat" placeholder="Masukkan Alamat"></textarea>
                </div>
            </div>

            <div class="form-group ">
                <label for="npwp" class="col-sm-3 control-label">NPWP</label>
                <div class="col-sm-5">
                    <input type="number" maxlength="15" class="form-control" id="npwp" name="npwp" placeholder="Contoh: 999888777666555">
                </div>
            </div>

            <div class="form-group ">
                <label for="no_hp" class="col-sm-3 control-label">Nomor HP</label>
                <div class="col-sm-5">
                    <input type="number" maxlength="13" class="form-control" id="no_hp" name="no_hp" placeholder="Contoh: 081122223333" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                </div>
            </div>

            <div class="form-group ">
                <label for="email" class="col-sm-3 control-label">email</label>
                <div class="col-sm-5">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan Email" oninvalid="this.setCustomValidity('alamat email salah!')" onchange="setCustomValidity('')" required>
                </div>
            </div>

            <div class="form-group">
                <label for="id_jabatan" class="col-sm-3 control-label">Jabatan</label>
                <div class="col-sm-5">
                    <div class="checkbox">
                        @foreach($data['jabatan'] as $key => $jabatan)
                        <label><input id="id_jabatan_<?= $key?>" name="id_jabatan[]" type="checkbox" class="minimal" value="{{ $jabatan->id }}" oninvalid="this.setCustomValidity('alamat email salah!')" onchange="setCustomValidity('')">{{ $jabatan->nama_jabatan }}</label><br>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="form-group " id="atasan_manajerarea">
                <label for="manajerarea_id" class="col-sm-3 control-label">Koordinator Manajer Area</label>
                <div class="col-sm-5">
                    <select data-size="5" id="manajerarea_id" name="manajerarea_id" class="selectpicker" data-live-search="true" title="Pilih Manajer.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')">
                        @foreach($data['manajerarea'] as $key => $atasan)
                        <option value="{{ $atasan->id }}">{{ $atasan->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group " id="atasan_manajer">
                <label for="manajer_id" class="col-sm-3 control-label">Koordinator Manajer Group</label>
                <div class="col-sm-5">
                    <select data-size="5" id="manajer_id" name="manajer_id" class="selectpicker" data-live-search="true" title="Pilih Manajer.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')">
                        @foreach($data['manajer'] as $key => $atasan)
                        <option value="{{ $atasan->id }}">{{ $atasan->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group " id="group">
                <label for="group_id" class="col-sm-3 control-label">Group</label>
                <div class="col-sm-5">
                    <select data-size="5" id="group_id" name="group_id" class="selectpicker" data-live-search="true" title="Pilih Manajer.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')">
                        @foreach($data['group'] as $key => $group)
                        <option value="{{ $group->id }}">{{ $group->id }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-jet-label for="terms">
                        <div class="flex items-center">
                            <x-jet-checkbox name="terms" id="terms"/>

                            <div class="ml-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-jet-label>
                </div>
            @endif

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </form>
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

        $( "#password" ).on('change', function() {
            if ($(this).val().length<8) {
                alert('Password harus terdiri dari 8 kata !');       
            }
        });

        $('#atasan_manajer').hide();
        $('#group').hide();
        $('#atasan_manajerarea').hide();

        $('input:checkbox[id="id_jabatan_1"]').change(function(){
            if(this.checked) {
                $('#atasan_manajerarea').show();
                $('#manajerarea_id').attr('required', '');
            }else{
                $('#atasan_manajerarea').hide();
                $('#manajerarea_id').removeAttr('required', '');
            }
        });

        $('input:checkbox[id="id_jabatan_2"]').change(function(){
            if(this.checked) {
                $('#atasan_manajer').show();
                $('#group').show();
                $('#manajer_id').attr('required', '');
                $('#group_id').attr('required', '');
            }else{
                $('#atasan_manajer').hide();
                $('#group').hide();
                $('#manajer_id').removeAttr('required', '');
                $('#group_id').removeAttr('required', '');
            }
        });

        //select picker
        $('select').selectpicker();

        //Submit
        $('#tambah_user').submit(function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{route('panzisda.simpanUser')}}",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    var html = '';
                    alert("Data berhasil ditambahkan !");
                    html = '<div class="alert alert-success">' + data + '</div>';
                    $('#tambah_user')[0].reset();
                    window.location.replace("{{url('/panzisda/user')}}");
                },
                error: function (data) {
                    var html = '';
                    alert("Data gagal disimpan, silahkan cek kembali !")
                    html = '<div class="alert alert-danger">' + data + '</div>';
                }
            })
        })

    });
</script>
@endpush
@endsection
