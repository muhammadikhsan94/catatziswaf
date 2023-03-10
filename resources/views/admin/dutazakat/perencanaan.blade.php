@extends('template.app')

@section('title')
- Buat Perencanaan
@endsection

@section('content')
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Tambah Perencanaan Muzakki</h3>
    </div>
    <form id="formUpdate" class="form-horizontal" method="post" action="javascript:void(0)" enctype="multipart/form-data">
        @csrf
        <div class="box-body">

            <div class="col-md-10">
                <div class="form-group ">
                    <label for="alamat" class="col-sm-3 control-label">Nama Muzakki</label>
                    <div class="col-sm-5">
                        <input type="text" name="muzakki[]" id="muzakki" class="form-control" placeholder="Masukkan Nama Muzakki..">
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <a class="btn btn-success" href="javascript:void(0);" id="add">Tambah</a>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" type="submit"></button>

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

<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" data-dismiss="modal">
        <div class="modal-content"  >              
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <img src="" class="imagePreviewFull" style="width: 100%;" >
            </div> 
        </div>
    </div>
</div>
@endsection
