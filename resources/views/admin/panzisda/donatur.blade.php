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

        <div class="dataTables_scrollBody">
            <div class="col-md-12">
                <table id="tabel-donatur" class="display" style="width: 100%">
                    <thead>
                        <tr class="bg-success">
                            <th> ID </th>
                            <th width="20%"> Nama </th>
                            <th width="10%"> No HP </th>
                            <th width="30%"> Alamat </th>
                            <th width="20%"> Email </th>
                            <th width="16%"> <center>Aksi</center> </th>
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
                <form class="form-horizontal" role="form" action="javascript:void(0)" id="formEdit" method="post" enctype="multipart/form-data">
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
                            <input class="form-control" type="text" name="alamat" id="alamat" readonly>
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

                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-secondary">Kembali</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Form Modal End Detail Donatur -->

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                <a class="btn btn-danger btn-ok">Delete</a>
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
                url: "{{ url('/panzisda/donatur/getdata') }}",
            },
            columns: [{
                data: "id",
                orderable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
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
                data: 'alamat',
                name: 'alamat',
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
    });

    $(document).on('click', '.detail', function() {
        var id = $(this).attr('id');
        $.ajax({
            method: "GET",
            url: "/panzisda/donatur/detail/" + id,
            dataType: "json",
            success: function(data) {
                $('#nama').val(data.nama);
                $('#alamat').val(data.alamat);
                $('#npwp').val(data.npwp);
                $('#no_hp').val(data.no_hp);
                $('#email').val(data.email);
                $('#formModal-detail').modal('show');
            },
            error: function() {
                alert('Error : Cannot get data!');
            }
        });
    });

    jQuery(document).ready(function(){
        $('#confirm-delete').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
        });
    });
</script>
@endpush

@endsection