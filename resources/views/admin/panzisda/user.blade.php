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
        <div class="col-md-4">
            <h3 class="box-title"><strong>Data User</strong></h3>
        </div>
        <div class="col-md-8" style="text-align: right;">
            <a type="button" id="tambah-user" class="btn btn-success btn-sm" href="{{ route('panzisda.tambahUser' )}}">
                <i class="fa fa-plus"> Tambah User </i>
            </a>
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
                            <th> Jabatan </th>
                            <th> Group </th>
                            <th width="13%"> <center>Aksi</center> </th>
                        </tr>
                    </thead>
                </table>
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
        var asal = <?php echo json_encode($data['user']) ?>;

        var table = $('#tabel-user').DataTable({
            dom: 'lfrtip',
            "language": {
                "sEmptyTable": "DATA KOSONG ATAU TIDAK DITEMUKAN !",
                "sLengthMenu": "Tampilkan _MENU_ records",
                "sSearch": "Cari Data/Filter:",
            },
            ajax: {
                url: "{{ url('/panzisda/user/getdata') }}",
            },
            columnDefs: [{
                targets: 3,
                className: 'dt-body-center'
            }],
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
                data: 'jabatan',
                name: 'jabatan',
            },
            {
                data: 'id_group',
                name: 'id_group',
            },
            {
                data: 'aksi',
                name: 'aksi',
            }]
        });

        $(document).on('click', '.edit', function() {
            var id = $(this).attr('id');
            window.location = "/panzisda/user/edit/"+id;
        });

        $(document).on('click', '.delete', function() {
            user_id = $(this).attr('id');
            $('#confirmModal').modal('show');
        });

        $('#ok-button').click(function() {
            $.ajax({
                url: "/panzisda/user/delete/" + user_id,
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
                            $('#tabel-user').DataTable().ajax.reload();
                            alert(errorMessage);
                        } else {
                            $('#confirmModal').modal('hide');
                            $('#tabel-user').DataTable().ajax.reload();
                            alert('User berhasil dihapus...');
                            $('#ok-button').text('Hapus');
                        }
                    }, 2000);
                }
            })
        });

    });
</script>
@endpush
@endsection