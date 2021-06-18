@extends('template.app')

@section('title')
- Transaksi Baru
@endsection

@section('content')
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Input Data Transaksi</h3>
    </div>
    <form id="formTambah" class="form-horizontal" method="post" action="javascript:void(0)" enctype="multipart/form-data">
        @csrf
        <div class="box-body" id="form-body">

            <div class="form-group ">
                <label for="id_lembaga" class="col-sm-3 control-label">Lembaga<i style="color: red;">*</i></label>
                <div class="col-sm-5">
                    <select data-size="5" id="id_lembaga" name="id_lembaga" class="selectpicker" data-live-search="true" title="Pilih Lembaga.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                        @foreach($data['lembaga'] as $key => $lembaga)
                        <option value="{{ $lembaga->id }}">{{ strtoupper($lembaga->nama_lembaga) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group ">
                <label for="id_donatur" class="col-sm-3 control-label">Nama Muzakki<i style="color: red;">*</i></label>
                <div class="col-sm-5">
                    <select data-size="5" id="id_donatur" name="id_donatur" class="selectpicker" data-live-search="true" title="Pilih Donatur.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                        <option value="tambah">+ Tambah Muzakki</option>
                        @foreach($data['donatur'] as $key => $donatur)
                        <option value="{{ $donatur->id }}">{{ $donatur->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- TAMBAH DONATUR -->
            <div class="form-group " id="tambah_nama_donatur">
                <label for="nama" class="col-sm-3 control-label">Nama<i style="color: red;">*</i></label>
                <div class="col-sm-5">
                    <input class="form-control" id="nama" name="nama" placeholder="Masukkan Nama" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')">
                </div>
            </div>

            <div class="form-group " id="tambah_alamat_donatur">
                <label for="alamat" class="col-sm-3 control-label">Alamat<i style="color: red;">*</i></label>
                <div class="col-sm-5">
                    <textarea class="form-control" id="alamat" name="alamat" placeholder="Masukkan Alamat"></textarea>
                </div>
            </div>

            <div class="form-group " id="tambah_npwp_donatur">
                <label for="npwp" class="col-sm-3 control-label">NPWP</label>
                <div class="col-sm-5">
                    <input type="number" maxlength="15" class="form-control" id="npwp" name="npwp" placeholder="Contoh: 999888777666555">
                </div>
            </div>

            <div class="form-group " id="tambah_hp_donatur">
                <label for="no_hp" class="col-sm-3 control-label">Nomor HP</label>
                <div class="col-sm-5">
                    <input type="number" maxlength="13" class="form-control" id="no_hp" name="no_hp" placeholder="Contoh: 081122223333">
                </div>
            </div>

            <div class="form-group " id="tambah_email_donatur">
                <label for="email" class="col-sm-3 control-label">email</label>
                <div class="col-sm-5">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan Email">
                </div>
            </div>
            <!-- END TAMBAH DONATUR -->

            <!-- Item -->
            <div class="form-group">
                <label for="jenis_transaksi" class="col-sm-3 control-label">Jenis Transaksi<i style="color: red;">*</i></label>
                <div class="col-sm-5">
                    <select data-size="5" id="jenis_transaksi" name="jenis_transaksi" class="selectpicker" data-live-search="true" title="Pilih Jenis Transaksi.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                        @foreach($data['jenis'] as $jenis)
                        <option value="{{ $jenis->id }}">{{ ucwords($jenis->jenis_transaksi) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="jumlah" class="col-sm-3 control-label">Total Jumlah<i style="color: red;">*</i></label>
                <div class="col-sm-3">
                    <div class="input-group">
                        <span class="input-group-addon">Rp</span>
                        <input type="text" pattern="[0-9]" id="total" name="total" class="form-control">
                    </div>
                </div>
            </div>

            <div class="form-group ">
                <label for="jumlah_transaksi" class="col-sm-3 control-label">Jumlah Paket per Transaksi<i style="color: red;">*</i></label>
                <div class="col-sm-5">
                    <select id="jumlah_transaksi" name="jumlah_transaksi" class="selectpicker">
                        <option value="">--Pilih--</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </div>
            </div>

            <div class="form-group" id="paket1">
                <label class="col-sm-3 control-label">Paket Zakat<i style="color: red;">*</i></i></label>
                <div class="col-sm-5">
                    <select data-size="5" id="id_paket_zakat_1" name="id_paket_zakat[]" class="selectpicker" data-live-search="true" title="Pilih Item Zakat.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')">
                        @foreach($data['paket'] as $key => $paket)
                        <option value="{{ $paket->id }}">{{ $paket->nama_paket_zakat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!-- End Item -->

            <div class="form-group" id="jumlah1">
                <label for="jumlah" class="col-sm-3 control-label">Jumlah<i style="color: red;">*</i></label>
                <div class="col-sm-3">
                    <div class="input-group">
                        <span class="input-group-addon">Rp</span>
                        <input type="text" pattern="[0-9]" id="jumlah_1" name="jumlah[]" class="form-control">
                    </div>
                </div>
            </div>

            <div class="form-group" id="paket2">
                <label class="col-sm-3 control-label">Paket Zakat<i style="color: red;">*</i></label>
                <div class="col-sm-5">
                    <select data-size="5" id="id_paket_zakat_2" name="id_paket_zakat[]" class="selectpicker" data-live-search="true" title="Pilih Item Zakat.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')">
                        @foreach($data['paket'] as $key => $paket)
                        <option value="{{ $paket->id }}">{{ $paket->nama_paket_zakat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!-- End Item -->

            <div class="form-group" id="jumlah2">
                <label for="jumlah" class="col-sm-3 control-label">Jumlah<i style="color: red;">*</i></label>
                <div class="col-sm-3">
                    <div class="input-group">
                        <span class="input-group-addon">Rp</span>
                        <input type="text" pattern="[0-9]" id="jumlah_2" name="jumlah[]" class="form-control">
                    </div>
                </div>
            </div>

            <div class="form-group" id="paket3">
                <label class="col-sm-3 control-label">Paket Zakat<i style="color: red;">*</i></label>
                <div class="col-sm-5">
                    <select data-size="5" id="id_paket_zakat_3" name="id_paket_zakat[]" class="selectpicker" data-live-search="true" title="Pilih Item Zakat.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')">
                        @foreach($data['paket'] as $key => $paket)
                        <option value="{{ $paket->id }}">{{ $paket->nama_paket_zakat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!-- End Item -->

            <div class="form-group" id="jumlah3">
                <label for="jumlah" class="col-sm-3 control-label">Jumlah<i style="color: red;">*</i></label>
                <div class="col-sm-3">
                    <div class="input-group">
                        <span class="input-group-addon">Rp</span>
                        <input type="text" pattern="[0-9]" id="jumlah_3" name="jumlah[]" class="form-control">
                    </div>
                </div>
            </div>

            <div class="form-group" id="paket4">
                <label class="col-sm-3 control-label">Paket Zakat<i style="color: red;">*</i></label>
                <div class="col-sm-5">
                    <select data-size="5" id="id_paket_zakat_4" name="id_paket_zakat[]" class="selectpicker" data-live-search="true" title="Pilih Item Zakat.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')">
                        @foreach($data['paket'] as $key => $paket)
                        <option value="{{ $paket->id }}">{{ $paket->nama_paket_zakat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!-- End Item -->

            <div class="form-group" id="jumlah4">
                <label for="jumlah" class="col-sm-3 control-label">Jumlah<i style="color: red;">*</i></label>
                <div class="col-sm-3">
                    <div class="input-group">
                        <span class="input-group-addon">Rp</span>
                        <input type="text" pattern="[0-9]" id="jumlah_4" name="jumlah[]" class="form-control">
                    </div>
                </div>
            </div>

            <div class="form-group" id="paket5">
                <label class="col-sm-3 control-label">Paket Zakat<i style="color: red;">*</i></label>
                <div class="col-sm-5">
                    <select data-size="5" id="id_paket_zakat_5" name="id_paket_zakat[]" class="selectpicker" data-live-search="true" title="Pilih Item Zakat.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" >
                        @foreach($data['paket'] as $key => $paket)
                        <option value="{{ $paket->id }}">{{ $paket->nama_paket_zakat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!-- End Item -->

            <div class="form-group" id="jumlah5">
                <label for="jumlah" class="col-sm-3 control-label">Jumlah<i style="color: red;">*</i></label>
                <div class="col-sm-3">
                    <div class="input-group">
                        <span class="input-group-addon">Rp</span>
                        <input type="text" pattern="[0-9]" id="jumlah_5" name="jumlah[]" class="form-control">
                    </div>
                </div>
            </div>

            <div id="clone_paket_zakat"></div>

            <div class="form-group " id="tambah_barang">
                <label for="nama_barang" class="col-sm-3 control-label">Nama Barang<i style="color: red;">*</i></label>
                <div class="col-sm-5">
                    <input class="form-control" id="nama_barang" name="nama_barang" placeholder="Nama Item Barang" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')">
                </div>
            </div>

            <div class="form-group " id="tambah_bank">
                <label class="col-sm-3 control-label">Nomor Rekening<i style="color: red;">*</i></label>
                <div class="col-sm-5">
                    <select data-size="5" id="rek_bank" name="rek_bank" class="selectpicker" data-live-search="true" title="Pilih Nomor Rekening.." oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')">
                        <option></option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="tanggal_transfer" class="col-sm-3 control-label">Tanggal Transfer/Kirim<i style="color: red;">*</i></label>
                <div class="col-sm-5">
                    <div class="input-group">
                        <span class="input-group-addon glyphicon glyphicon-th"></span>
                        <input class="form-control" id="tanggal_transfer" name="tanggal_transfer" placeholder="Masukkan Tanggal Transfer/Kirim"  oninvalid="this.setCustomValidity('data tidak boleh kosong!')" onchange="setCustomValidity('')" required>
                    </div>
                </div>
            </div>

            <div class="form-group ">
                <label for="keterangan" class="col-sm-3 control-label">Keterangan</label>
                <div class="col-sm-5">
                    <input class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan">
                </div>
            </div>

            <div class="form-group ">
                <label for="bukti_transaksi" class="col-sm-3 control-label">Bukti Transaksi<i style="color: red;">*</i></label>
                <div class="col-sm-5">
                    <input class="form-control" id="bukti_transaksi" name="bukti_transaksi" type="file" oninvalid="this.setCustomValidity('data tidak boleh kosong!')" required>
                    <p style="padding: 10px 0">
                        <a class="pop"><img id="imagePreview" alt="bukti transaksi" style="width: 100%;cursor:zoom-in;display: none;" /></a>
                    </p>
                </div>
            </div>

            <div class="box-footer">
                <input type="submit" name="btn" value="Simpan" id="submitBtn" class="btn btn-primary" onclick="MyFunction()" />
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
                <a id="ok-button" name="ok-button" class="btn btn-success btn-ok">Kirim</a>
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

@push('scripts')
<script type="text/javascript">

    function MyFunction()
    {
        var total = $('#total').val().replace(".", "").replace(".", "").replace(".", "");
        var subtotal = 0;
        for (var x=1;x<=5;x++) {
            subtotal += Number($('#jumlah_'+x).val().replace(".", "").replace(".", ""));
        }
        
        if (total != subtotal) {
            $('#submitBtn').removeAttr("data-target", "#confirmModal");
            $('#submitBtn').removeAttr("data-toggle", "modal");
            alert('jumlah total tidak sesuai dengan akumulasi dari jumlah!');
        } else {
            $('#submitBtn').attr("data-target", "#confirmModal");
            $('#submitBtn').attr("data-toggle", "modal");
        }
    }

    $(document).ready(function() {

        $('#bukti_transaksi').bind('change', function() {
            var images = this.files[0];
            var fileType = images["type"];
            var validImageTypes = ["image/jpg", "image/jpeg", "image/png"];
            var filesize = images.size / 1024 / 1024; //in MiN
            if ($.inArray(fileType, validImageTypes) < 0) {
                alert('File yang disarankan adalah Gambar dengan format JPG/JPEG/PNG');
                $('#bukti_transaksi').val('');
                document.getElementById('imagePreview').style.display = 'none';
            } else if (filesize > 2) {
                alert('Ukuran gambar yang disarankan adalah 2 MB');
                $('#bukti_transaksi').val('');
                document.getElementById('imagePreview').style.display = 'none';
            } else {
                document.getElementById('imagePreview').style.display = 'block';
                document.getElementById('imagePreview').src = window.URL.createObjectURL(images); 
                this.setCustomValidity('');
            }
        });

        var barang = <?php echo json_encode($data['jenis']->where('jenis_transaksi', 'barang')->first()); ?>;
        var non_tunai = <?php echo json_encode($data['jenis']->where('jenis_transaksi', 'transfer')->first()); ?>;
        var lapor = <?php echo json_encode($data['jenis']->where('jenis_transaksi', 'lapor')->first()); ?>;
        
        $( function() {
            $( "#tanggal_transfer" ).datepicker({
                autoclose:true,
                todayHighlight:true,
                format:'dd-mm-yyyy',
                language: 'id'
            });
        } );

        $( "#npwp" ).on('input', function() {
            if ($(this).val().length>15) {
                $('#npwp').val('');
                alert('Nomor NPWP tidak lebih dari 15 !');       
            }
        });

        $( "#no_hp" ).on('input', function() {
            if ($(this).val().length>13) {
                $('#no_hp').val('');
                alert('Nomor HP tidak lebih dari 13 !');       
            }
        });

        $(function() {
            $('.pop').on('click', function() {
                $('.imagePreviewFull').attr('src', $(this).find('img').attr('src'));
                $('#imagemodal').modal('show');   
            });     
        });

        //select picker
        $('select').selectpicker();

        //Lembaga
        $("#id_lembaga").change(function() {
            var lembaga = $(this).val();

            var lembaga_dm = <?php echo json_encode($data['lembaga']->whereIn('nama_lembaga', ['dana mandiri', 'DANA MANDIRI'])->first()); ?>;

            if (lembaga == lembaga_dm.id) {
                $('select[name=jenis_transaksi]').val(lapor.id);
                $('#jenis_transaksi').attr('required', '');
                $('#tambah_barang').hide();
                $('#tambah_bank').hide();
                $('#norek').removeAttr('required', '');
            } else {
                $('select[name=jenis_transaksi]').selectpicker('val', '');
                $('#jenis_transaksi').removeAttr('required', '');
            }
            $('.selectpicker').selectpicker('refresh');

            $.ajax({
                url: '/duta/rekening/lembaga/'+lembaga,
                type: "GET",
                dataType: "JSON",
                success:function(data){

                    var target = $('#rek_bank');

                    target.empty();
                    $.each(data, function(key, value) {
                        target.append('<option value="'+ value.norek +'">'+ value.norek +'</option>');   
                    });
                    $('#rek_bank').selectpicker('refresh');
                }
            });
        });
        $("#id_lembaga").trigger("change");

        //Paket per Transaksi
        $("#jumlah_transaksi").change(function() {
            if ($(this).val() == 1) {
                $('#paket1').show();
                $('#jumlah1').show();
                $('#id_paket_zakat_1').attr('required','');
                $('#jumlah_1').attr('required','');
                $('#paket2').hide();
                $('#jumlah2').hide();
                $('#id_paket_zakat_2').removeAttr('required','');
                $('#id_paket_zakat_2').selectpicker('val', '');
                $('#jumlah_2').removeAttr('required', '');
                $('#jumlah_2').val('');
                $('#paket3').hide();
                $('#jumlah3').hide();
                $('#id_paket_zakat_3').removeAttr('required','');
                $('#id_paket_zakat_3').selectpicker('val', '');
                $('#jumlah_3').removeAttr('required', '');
                $('#jumlah_3').val('');
                $('#paket4').hide();
                $('#jumlah4').hide();
                $('#id_paket_zakat_4').removeAttr('required','');
                $('#id_paket_zakat_4').selectpicker('val', '');
                $('#jumlah_4').removeAttr('required', '');
                $('#jumlah_4').val('');
                $('#paket5').hide();
                $('#jumlah5').hide();
                $('#id_paket_zakat_5').removeAttr('required','');
                $('#id_paket_zakat_5').selectpicker('val', '');
                $('#jumlah_5').removeAttr('required', '');
                $('#jumlah_5').val('');
            } else if ($(this).val() == 2) {
                $('#paket1').show();
                $('#jumlah1').show();
                $('#id_paket_zakat_1').attr('required','');
                $('#jumlah_1').attr('required','');
                $('#paket2').show();
                $('#jumlah2').show();
                $('#id_paket_zakat_2').attr('required','');
                $('#jumlah_2').attr('required','');
                $('#paket3').hide();
                $('#jumlah3').hide();
                $('#id_paket_zakat_3').removeAttr('required','');
                $('#id_paket_zakat_3').selectpicker('val', '');
                $('#jumlah_3').removeAttr('required', '');
                $('#jumlah_3').val('');
                $('#paket4').hide();
                $('#jumlah4').hide();
                $('#id_paket_zakat_4').removeAttr('required','');
                $('#id_paket_zakat_4').selectpicker('val', '');
                $('#jumlah_4').removeAttr('required', '');
                $('#jumlah_4').val('');
                $('#paket5').hide();
                $('#jumlah5').hide();
                $('#id_paket_zakat_5').removeAttr('required','');
                $('#id_paket_zakat_5').selectpicker('val', '');
                $('#jumlah_5').removeAttr('required', '');
                $('#jumlah_5').val('');
            } else if ($(this).val() == 3) {
                $('#paket1').show();
                $('#jumlah1').show();
                $('#id_paket_zakat_1').attr('required','');
                $('#jumlah_1').attr('required','');
                $('#paket2').show();
                $('#jumlah2').show();
                $('#id_paket_zakat_2').attr('required','');
                $('#jumlah_2').attr('required','');
                $('#paket3').show();
                $('#jumlah3').show();
                $('#id_paket_zakat_3').attr('required','');
                $('#jumlah_3').attr('required','');
                $('#paket4').hide();
                $('#jumlah4').hide();
                $('#id_paket_zakat_4').removeAttr('required','');
                $('#id_paket_zakat_4').selectpicker('val', '');
                $('#jumlah_4').removeAttr('required', '');
                $('#jumlah_4').val('');
                $('#paket5').hide();
                $('#jumlah5').hide();
                $('#id_paket_zakat_5').removeAttr('required','');
                $('#id_paket_zakat_5').selectpicker('val', '');
                $('#jumlah_5').removeAttr('required', '');
                $('#jumlah_5').val('');
            } else if ($(this).val() == 4) {
                $('#paket1').show();
                $('#jumlah1').show();
                $('#id_paket_zakat_1').attr('required','');
                $('#jumlah_1').attr('required','');
                $('#paket2').show();
                $('#jumlah2').show();
                $('#id_paket_zakat_2').attr('required','');
                $('#jumlah_2').attr('required','');
                $('#paket3').show();
                $('#jumlah3').show();
                $('#id_paket_zakat_3').attr('required','');
                $('#jumlah_3').attr('required','');
                $('#paket4').show();
                $('#jumlah4').show();
                $('#id_paket_zakat_4').attr('required','');
                $('#jumlah_4').attr('required','');
                $('#paket5').hide();
                $('#jumlah5').hide();
                $('#id_paket_zakat_5').removeAttr('required','');
                $('#id_paket_zakat_5').selectpicker('val', '');
                $('#jumlah_5').removeAttr('required', '');
                $('#jumlah_5').val('');
            } else if ($(this).val() == 5) {
                $('#paket1').show();
                $('#jumlah1').show();
                $('#id_paket_zakat_1').attr('required','');
                $('#jumlah_1').attr('required','');
                $('#paket2').show();
                $('#jumlah2').show();
                $('#id_paket_zakat_2').attr('required','');
                $('#jumlah_2').attr('required','');
                $('#paket3').show();
                $('#jumlah3').show();
                $('#id_paket_zakat_3').attr('required','');
                $('#jumlah_3').attr('required','');
                $('#paket4').show();
                $('#jumlah4').show();
                $('#id_paket_zakat_4').attr('required','');
                $('#jumlah_4').attr('required','');
                $('#paket5').show();
                $('#jumlah5').show();
                $('#id_paket_zakat_5').attr('required','');
                $('#jumlah_5').attr('required','');
            } else {
                $('#paket1').hide();
                $('#jumlah1').hide();
                $('#paket2').hide();
                $('#jumlah2').hide();
                $('#paket3').hide();
                $('#jumlah3').hide();
                $('#paket4').hide();
                $('#jumlah4').hide();
                $('#paket5').hide();
                $('#jumlah5').hide();
            }
        });
        $("#jumlah_transaksi").trigger("change");

        //button click
        $("#id_donatur").change(function() {
            if ($(this).val() == "tambah") {
                $('#tambah_nama_donatur').show();
                $('#tambah_alamat_donatur').show();
                $('#tambah_npwp_donatur').show();
                $('#tambah_hp_donatur').show();
                $('#tambah_email_donatur').show();
                $('#nama').attr("required", "");
                $('#alamat').attr("required", "");
            } else {
                $('#tambah_nama_donatur').hide();
                $('#tambah_alamat_donatur').hide();
                $('#tambah_npwp_donatur').hide();
                $('#tambah_hp_donatur').hide();
                $('#tambah_email_donatur').hide();
                $('#nama').removeAttr("required", "");
                $('#alamat').removeAttr("required", "");
            }
        });
        $("#id_donatur").trigger("change");

        $("#jenis_transaksi").change(function() {
            if ($(this).val() == barang.id ) {
                $('#tambah_bank').hide();
                $('#tambah_barang').show();
                $('#norek').removeAttr('required', '');
                $('#nama_barang').prop('required',true);
            } else if ($(this).val() == non_tunai.id ) {
                $('#tambah_barang').hide();
                $('#tambah_bank').show();
                $('#rek_bank').prop('required',true);
            } else {
                $('#tambah_barang').hide();
                $('#tambah_bank').hide();
                $('#norek').removeAttr('required', '');
            }
        });
        $("#jenis_transaksi").trigger("change");

        var total = document.getElementById('total');
        total.addEventListener("keyup", function(e) {
            total.value = convertRupiah(this.value);
        });
        total.addEventListener('keydown', function(event) {
            return isNumberKey(event);
        });

        var inp = document.getElementById('jumlah_1');
        inp.addEventListener("keyup", function(e) {
            inp.value = convertRupiah(this.value);
        });
        inp.addEventListener('keydown', function(event) {
            return isNumberKey(event);
        });

        var inp1 = document.getElementById('jumlah_2');
        inp1.addEventListener("keyup", function(e) {
            inp1.value = convertRupiah(this.value);
        });
        inp1.addEventListener('keydown', function(event) {
            return isNumberKey(event);
        });

        var inp2 = document.getElementById('jumlah_3');
        inp2.addEventListener("keyup", function(e) {
            inp2.value = convertRupiah(this.value);
        });
        inp2.addEventListener('keydown', function(event) {
            return isNumberKey(event);
        });

        var inp3 = document.getElementById('jumlah_4');
        inp3.addEventListener("keyup", function(e) {
            inp3.value = convertRupiah(this.value);
        });
        inp3.addEventListener('keydown', function(event) {
            return isNumberKey(event);
        });

        var inp4 = document.getElementById('jumlah_5');
        inp4.addEventListener("keyup", function(e) {
            inp4.value = convertRupiah(this.value);
        });
        inp4.addEventListener('keydown', function(event) {
            return isNumberKey(event);
        });

        //form input separator thousand
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

        //Submit
        $('#ok-button').on('click', function(e) {
            e.preventDefault();
            let formData = new FormData(document.getElementById("formTambah"));
            $.ajax({
                type: "POST",
                url: "{{route('duta.simpanTransaksi')}}",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#ok-button').text('Kirim...');
                },
                success: function (data) {
                    $('#formTambah')[0].reset();
                    var html = '';
                    alert("Data berhasil disimpan!")
                    html = '<div class="alert alert-default">' + data + '</div>';
                    window.location.replace("{{url('/duta/transaksi')}}");
                },
                error: function (data) {
                    $('#ok-button').text('Kirim');
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
