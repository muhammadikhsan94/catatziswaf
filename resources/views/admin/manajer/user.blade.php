@extends('template.app')

@section('title')
- Data Duta Zakat
@endsection

@section('content')

<div class="box box-info">

    <div class="box-header with-border">
        <div class="col-md-6">
            <h3 class="box-title"><strong>Data Duta Zakat</strong></h3>
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
                            <th> No HP </th>
                            <th> Email </th>
                            <th width="10%"> <center>Aksi</center> </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
</div>

<div id="formDetail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLongTitle">Detail User</h5>
            </div>
            <div class="modal-body">
                <span id="form-result"></span>
                <form class="form-horizontal" role="form" action="javascript:void(0)" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="id" id="id">

                    <div class="form-group ">
                        <label for="no_punggung" class="col-sm-3 control-label">Nomor Punggung</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="no_punggung" name="no_punggung" disabled>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="nama" class="col-sm-3 control-label">Nama Lengkap</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="nama" name="nama" disabled>
                        </div>
                    </div>


                    <div class="form-group ">
                        <label for="alamat" class="col-sm-3 control-label">Alamat Lengkap</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="alamat" name="alamat" disabled>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="npwp" class="col-sm-3 control-label">NPWP</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="npwp" name="npwp" disabled>
                        </div>
                    </div>

                    <div class="form-group " id="output_barang">
                        <label for="no_hp" class="col-sm-3 control-label">Nomor HP</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="no_hp" name="no_hp" disabled>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="email" class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-5">
                            <input type="email" class="form-control" id="email" name="email" disabled>
                        </div>
                    </div>

                    <div class="form-group " id="output_bank">
                        <label for="jabatan" class="col-sm-3 control-label">Jabatan</label>
                        <div class="col-sm-5">
                            <input class="form-control" id="jabatan" name="jabatan" disabled>
                        </div>
                    </div>

                    <div class="form-group " id="output_bank">
                        <label for="wilayah" class="col-sm-3 control-label">Wilayah</label>
                        <div class="col-sm-5">
                            <input class="form-control" id="wilayah" name="wilayah" disabled>
                        </div>
                    </div>

                    <div class="form-group " id="output_bank">
                        <label for="group" class="col-sm-3 control-label">Group</label>
                        <div class="col-sm-5">
                            <input class="form-control" id="group" name="group" disabled>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
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

        var table = $('#tabel-user').DataTable({
            dom: 'lfrtip',
            "language": {
                "sEmptyTable": "DATA KOSONG ATAU TIDAK DITEMUKAN !",
                "sLengthMenu": "Tampilkan _MENU_ records",
                "sSearch": "Cari Data/Filter:",
            },
            ajax: {
                url: "{{ url('/manajer/user/getdata') }}",
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
                orderable: false,
            }]
        });

        $(document).on('click', '.detail', function() {
            var id = $(this).attr('id');
            $.ajax({
                method: "GET",
                url: "/manajer/user/detail/" + id,
                dataType: "json",
                success: function(data) {
                    $('#id').val(id);
                    $('#no_punggung').val(data.no_punggung);
                    $('#nama').val(data.nama);
                    $('#alamat').val(data.alamat);
                    $('#npwp').val(data.npwp);
                    $('#no_hp').val(data.no_hp);
                    $('#email').val(data.email);
                    $('#jabatan').val(data.nama_jabatan);
                    $('#wilayah').val(data.nama_wilayah);
                    $('#group').val(data.id_group);
                    $('#button-edit').val('Edit');
                    $('#formDetail').modal('show');
                },
                error: function() {
                    alert('Error : Cannot get data!');
                }
            });
        });

    });
</script>
@endpush
@endsection