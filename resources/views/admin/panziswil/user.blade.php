@extends('template.app')

@section('title')
- Data User
@endsection

@section('content')

<div class="box box-info">

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button> 
            <strong>{{ $message }}</strong>
        </div>
    @endif

    @if ($message = Session::get('errors'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button> 
            <strong>{{ $message }}</strong>
        </div>
    @endif

    <div class="box-header with-border">
        <div class="col-md-12">
            <h3 class="box-title"><strong>Data User</strong></h3>
            <div style="float: right;">
                <a href="/assets/template.zip" class="btn btn-default btn-xs my-3" target="_blank">Template</a>
                <button type="button" class="btn btn-primary btn-xs mr-5" data-toggle="modal" data-target="#importExcel">
                    Import
                </button>
                <a type="button" id="tambah-user" class="btn btn-success btn-xs mr-5" href="{{ route('panziswil.tambahUser' )}}">
                    <i class="fa fa-plus"> Tambah User </i>
                </a>
            </div>
        </div>
    </div>

    <div class="box-body">

        <div class="dataTables_scrollBody">
            <div class="col-md-12">
                <table id="tabel-user" class="display" style="width: 100%">
                    <thead>
                        <tr class="bg-success">
                            <th> No </th>
                            <th> No Punggung </th>
                            <th> Nama </th>
                            <th> Jabatan </th>
                            <th> Wilayah </th>
                            <th width="13%"> <center>Aksi</center> </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Import Excel -->
<div class="modal fade" id="importExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="post" action="{{route('panziswil.import')}}" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Import Excel</h5>
                </div>
                <div class="modal-body">

                    {{ csrf_field() }}

                    <label>Pilih file excel</label>
                    <div class="form-group">
                        <input type="file" name="file" required="required">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Form Modal Start Edit Panzisda -->
<div id="formModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Data User</h5>
            </div>
            <div class="modal-body">
                <span id="form-result"></span>
                <form class="form-horizontal" role="form" action="javascript:void(0)" id="formEdit" method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="id_role" name="id_role">

                    <div class="form-group ">
                        <label for="edit_nama" class="col-sm-3 control-label">Nama</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="edit_nama" name="edit_nama">
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="edit_alamat" class="col-sm-3 control-label">Alamat</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="edit_alamat" name="edit_alamat">
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="edit_npwp" class="col-sm-3 control-label">NPWP</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="edit_npwp" name="edit_npwp">
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="edit_no_hp" class="col-sm-3 control-label">No HP</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="edit_no_hp" name="edit_no_hp">
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="edit_email" class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-5">
                            <input type="email" class="form-control" id="edit_email" name="edit_email">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_jabatan" class="col-sm-3 control-label">Jabatan</label>
                        <div class="col-sm-5">
                            <select data-size="5" id="edit_jabatan" name="edit_jabatan" class="selectpicker" data-live-search="true" title="Pilih Jabatan.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')">
                                @foreach($data['jabatan'] as $key => $jabatan)
                                <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group " id="atasan_panzisda">
                        <label for="panzisda_id" class="col-sm-3 control-label">Koordinator Panzisda</label>
                        <div class="col-sm-5">
                            <select data-size="5" id="panzisda_id" name="panzisda_id" class="selectpicker" data-live-search="true" title="Pilih Panzisda.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')">
                                @foreach($data['panzisda'] as $key => $atasan)
                                <option value="{{ $atasan->id }}">{{ $atasan->nama }}</option>
                                @endforeach
                            </select>
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

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Form Modal End Edit Panzisda -->

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
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a name="ok_button" id="ok-button" class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
<script>
    $(document).ready(function() {

        var table = $('#tabel-user').DataTable({
            dom: 'lfrtip',
            "language": {
                "sEmptyTable": "DATA KOSONG ATAU TIDAK DITEMUKAN !",
                "sLengthMenu": "Tampilkan _MENU_ records",
                "sSearch": "Cari Data/Filter:",
            },
            ajax: {
                url: "{{ url('/panziswil/user/getdata') }}",
            },
            columns: [{
                data: "id",
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'no_punggung',
                name: 'no_punggung',
            },
            {
                data: 'nama',
                name: 'nama',
            },
            {
                data: 'jabatan',
                name: 'jabtan',
            },
            {
                data: 'wilayah',
                name: 'wilayah',
            },
            {
                data: 'aksi',
                name: 'aksi',
            }]
        });

        //select picker
        $('select').selectpicker();
        
        $(document).on('click', '.edit', function() {
            var id = $(this).attr('id');
            window.location = "/panziswil/user/edit/"+id;
        });

        $(document).on('click', '.delete', function() {
            user_id = $(this).attr('id');
            $('#confirmModal').modal('show');
        });

        $('#ok-button').click(function() {
            $.ajax({
                url: "/panziswil/user/delete/" + user_id,
                method: "DELETE",
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                beforeSend: function() {
                    $('#ok-button').text('Deleting...');
                },
                success: function(data) {
                    setTimeout(function() {
                        if (data.errors) {
                            errorMessage = '';
                            for (var count = 0; count < data.errors.length; count++) {
                                errorMessage += data.errors[count];
                            }
                            $('#confirmModal').modal('hide');
                            $('#tabel-user').DataTable().ajax.reload();
                            alert(errorMessage);
                        } else {
                            $('#confirmModal').modal('hide');
                            $('#tabel-user').DataTable().ajax.reload();
                            alert('Data Deleted');
                        }
                    }, 2000);
                }
            })
        });
    });
</script>
@endpush
@endsection