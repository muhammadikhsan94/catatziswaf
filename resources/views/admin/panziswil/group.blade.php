@extends('template.app')

@section('title')
- Data Group
@endsection

@section('content')

<div class="box box-info">

    <div class="box-header with-border">
        <div class="col-md-6">
            <h3 class="box-title"><strong>Data Group</strong></h3>
        </div>
    </div>

    <div class="box-body">

        <div class="dataTables_scrollBody">
            <div class="col-md-12">
                <table id="tabelGroup"  class="display" style="width: 100%">
                    <thead>
                        <tr class="bg-success">
                            <th> Kode Group </th>
                            <th> Target </th>
                            <th width="12%"> <center>Aksi</center> </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Form Modal Edit Target -->
<div id="modalEdit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Data Group</h5>
            </div>
            <div class="modal-body">
                <span id="form-result"></span>
                <form class="form-horizontal" role="form" action="javascript:void(0)" id="formEdit" method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" id="id" name="id">

                    <div class="form-group ">
                        <label for="target" class="col-sm-3 control-label">Target</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input class="form-control" id="target" name="target" placeholder="Masukan Jumlah.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Form Modal Edit Target -->

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

        var table = $('#tabelGroup').DataTable({
            dom: 'lfrtip',
            "language": {
                "sEmptyTable": "DATA KOSONG ATAU TIDAK DITEMUKAN !",
                "sLengthMenu": "Tampilkan _MENU_ records",
                "sSearch": "Cari Data:",
            },
            ajax: {
                url: "{{ url('/panziswil/group/getdata') }}",
            },
            columns: [{
                data: "id",
            },
            {
                data: 'target',
                name: 'target',
            },
            {
                data: 'aksi',
                name: 'aksi',
            }]
        });

        $(document).on('click', '.edit', function() {
            var id = $(this).attr('id');
            $.ajax({
                method: "GET",
                url: "/panziswil/group/edit/" + id,
                dataType: "json",
                success: function(data) {
                    $('#id').val(id);
                    $('#target').val(konversi(data.target));
                    $('#modalEdit').modal('show');
                },
                error: function() {
                    alert('Error : Cannot get data!');
                }
            });
        });

        //Simpan Data Wilayah
        $('#formEdit').submit(function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{route('panziswil.updateGroup')}}",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    var html = '';
                    alert("Data berhasil diupdate!");
                    html = '<div class="alert alert-success">' + data + '</div>';
                    $('#formEdit')[0].reset();
                    $('#modalEdit').modal('hide');
                    table.draw();
                },
                error: function (data) {
                    var html = '';
                    alert("Data gagal diupdate, silahkan dicek kembali!")
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
                url: "/panziswil/group/delete/" + user_id,
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

        //form input separator thousand
        var inp = document.getElementById('target');
        inp.addEventListener("keyup", function(e) {
            inp.value = convertRupiah(this.value);
        });
        inp.addEventListener('keydown', function(event) {
            return isNumberKey(event);
        });

        function convertRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, "").toString(),
            split  = number_string.split(","),
            sisa   = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? "." : "";
                rupiah += separator + ribuan.join(".");
            }

            rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
            return prefix == undefined ? rupiah : rupiah ? prefix + rupiah : "";
        }

    });
</script>
@endpush
@endsection