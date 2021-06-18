@extends('template.app')

@section('content')
<div class="row">		
	<div class="col-md-9">
		<div class="alert alert-success alert-dismissible">
			<h5><i class="fa fa-exclamation-circle red"></i> Selamat Datang, <b>{{ strtoupper(Auth::user()->nama) }}</b>.</h5>
			<span style="text-align: justify;">Berikut ini adalah halaman aplikasi zakat untuk anda sebagai Duta Zakat. Jika anda bukan sebagai Duta Zakat, silahkan hubungi Panzisda wilayah anda. Terima Kasih.</span>
		</div>
	</div>
	<div class="col-md-3">
		<div class="alert alert-success">
			<h5>SURAT TUGAS:</h5>
			<a href="{{ url('/surat_tugas/izi/'.Auth::user()->id) }}" target=new type="button" class="btn btn-info btn-xs">IZI</a>&nbsp;<a href="{{ url('/surat_tugas/lazdai/'.Auth::user()->id) }}" target=new type="button" class="btn btn-info btn-xs">LAZDAI</a>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-3 col-6">
		<div class="small-box bg-green">
			<div class="inner">
				<h3>{{number_format($data['persentase'],2)}}<sup style="font-size: 20px">%</sup></h3>
				<p>Persentase Target</p>
			</div>
			<div class="icon">
				<i class="fa fa-percent"></i>
			</div>
			<a class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	</div>

	<div class="col-lg-3 col-6">
		<!-- small card -->
		<div class="small-box bg-primary">
			<div class="inner">
				<h3>
					{{ count($data['perhari']) }}
				</h3>
				<p>Transaksi Hari Ini</p>
			</div>
			<div class="icon">
				<i class="fa fa-shopping-cart"></i>
			</div>
			<a href="{{route('duta.transaksi')}}" class="small-box-footer">
				More info <i class="ion ion-android-arrow-dropright-circle"></i>
			</a>
		</div>
	</div>

	<div class="col-lg-3 col-6">
		<!-- small card -->
		<div class="small-box bg-aqua">
			<div class="inner">
				<h3>
					{{ $data['perbulan']->count() }}
				</h3>
				<p>Transaksi Bulan Ini</p>
			</div>
			<div class="icon">
				<i class="fa fa-shopping-cart"></i>
			</div>
			<a href="{{route('duta.transaksi')}}" class="small-box-footer">
				More info <i class="ion ion-android-arrow-dropright-circle"></i>
			</a>
		</div>
	</div>

	<div class="col-lg-3 col-6">
		<!-- small card -->
		<div class="small-box bg-yellow">
			<div class="inner">
				<h3>
					{{ $data['pertahun']->count() }}
				</h3>
				<p>Transaksi Tahun Ini</p>
			</div>
			<div class="icon">
				<i class="fa fa-shopping-cart"></i>
			</div>
			<a href="{{route('duta.transaksi')}}" class="small-box-footer">
				More info <i class="ion ion-android-arrow-dropright-circle"></i>
			</a>
		</div>
	</div>

</div>

<div class="row">

	<div class="col-lg-6">
		<div class="small-box bg-teal">
			<div class="inner">
				<h3><sup style="font-size: 20px">Rp. </sup>{{number_format($data['terkumpul']->sum('jumlah'),0)}}</h3>
				<p>Total Pengumpulan</p>
			</div>
			<div class="icon">
				<i class="fa fa-money"></i>
			</div>
			<a class="small-box-footer"><i class="fa fa-exclamation-circle red"></i></a>
		</div>

		<div class="small-box bg-red">
			<div class="inner">
				<h3><sup style="font-size: 20px">Rp. </sup>{{number_format($data['user']->target,0)}}</h3>
				<p>Target Pengumpulan</p>
			</div>
			<div class="icon">
				<i class="fa fa-money"></i>
			</div>
			<a class="small-box-footer"><i class="fa fa-exclamation-circle red"></i></a>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="box box-info">
			<div class="box-header with-border">
				<h3 class="box-title" style="margin:10px;"><strong>Kalkulator Zakat:</strong></h3>
				<select id="kalkulator_zakat" class="selectpicker pull-right col-md-6" data-live-search="true" title="Pilih Jenis Perhitungan Zakat..">
					<option value="1">ZAKAT FITRAH</option>
					<option value="2">ZAKAT PERDAGANGAN</option>
					<!-- <option value="3">ZAKAT PERTANIAN</option>
					<option value="4">ZAKAT HEWAN TERNAK</option> -->
					<option value="5">ZAKAT EMAS DAN PERAK</option>
					<option value="6">ZAKAT PROFESI/PENGHASILAN</option>
					<option value="7">ZAKAT INVESTASI</option>
					<option value="8">ZAKAT TABUNGAN</option>
					<option value="9">ZAKAT RIKAZ</option>
				</select>
			</div>

			<div id="fitrah">
				<div class="box-body">
					<form action="" method="post">
						<div class="form-group">
							<label for="jumlah">Jumlah Anggota Keluarga :</label>
							<div class="inpt input-group">
								<input type="number" class="form-control" id="jumlah" name="jumlah" aria-describedby="modal_">
							</div>
						</div>

						<div class="form-group">
							<label for="harga">Harga Beras :</label>
							<div class="inpt input-group">
								<span class="input-group-addon">Rp. </span>
								<input type="number" class="form-control" id="harga" name="harga" aria-describedby="modal_">
								<span class="input-group-addon">/Kg </span>
							</div>
						</div>
							
						<div class="hit form-group">
							<a type="button" class="btn_ btn btn-info" id="zakat_fitrah">Hitung</a>
						</div>
					</form>
				</div>
			</div>

			<div id="dagang">
				<div class="box-body">
					<form action="" method="post">
						<div class="form-group">
							<label for="modal">Modal :</label>
							<div class="inpt input-group">
								<span class="input-group-addon" id="modal_">Rp. </span>
								<input type="number" class="form-control" id="modal" name="modal" aria-describedby="modal_">
							</div>
						</div>
						<div class="form-group">
							<label for="untung">Keuntungan :</label>
							<div class="inpt input-group">
								<span class="input-group-addon" id="untung_">Rp. </span>
								<input type="number" class="form-control" id="untung" name="untung" aria-describedby="untung_">
							</div>
						</div>
						<div class="form-group">
							<label for="piutang">Piutang :</label>
							<div class="inpt input-group">
								<span class="input-group-addon" id="piutang_">Rp. </span>
								<input type="number" class="form-control" id="piutang" name="piutang" aria-describedby="piutang_">
							</div>
						</div>
						<div class="form-group">
							<label for="hutang">Hutang :</label>
							<div class="inpt input-group">
								<span class="input-group-addon" id="hutang_">Rp. </span>
								<input type="number" class="inpt form-control" id="hutang" name="hutang" aria-describedby="hutang_">
							</div>
						</div>
						<div class="form-group">
							<label for="kerugian">Kerugian :</label>
							<div class="inpt input-group">
								<span class="input-group-addon" id="kerugian_">Rp. </span>
								<input type="number" class="inpt form-control" id="kerugian" name="kerugian" aria-describedby="kerugian_">
							</div>
						</div>
						<div class="form-group">
							<label for="nisab">Harga emas saat ini :</label>
							<div class="inpt input-group">
								<span class="input-group-addon">Rp. </span>
								<input type="number" class="inpt form-control" id="nisab" name="nisab">
								<span class="input-group-addon">/gram</span>
							</div>
						</div>
							
						<div class="hit form-group">
							<a type="button" class="btn_ btn btn-info" id="zakat_perdagangan">Hitung</a>
						</div>
					</form>
				</div>
			</div>

			<div id="pertanian">
				<div class="box-body">
					<form method="post">
					</form>
				</div>
			</div>

			<div id="ternak">
				<div class="box-body">
					<form method="post">
					</form>
				</div>
			</div>

			<div id="emas">
				<div class="box-body">
					<form method="post">
						<div class="form-group">
							<label for="perhiasan">Jenis Perhiasan :</label>
							<div class="inpt selectContainer">
								<select class="form-control" name="perhiasan" id="perhiasan">
									<option value="">Pilih jenis perhiasan</option>
									<option value="emas">Emas</option>
									<option value="perak">Perak</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="simpan">Perhiasan yang dimiliki :</label>
							<div class="inpt input-group">
								<input type="number" class="inpt form-control" id="simpan" name="simpan">
								<span class="input-group-addon">Gram</span>
							</div>
						</div>
						<div class="form-group">
							<label for="simpan">Perhiasan yang pakai :</label>
							<div class="inpt input-group">
								<input type="number" class="inpt form-control" id="pakai" name="pakai">
								<span class="input-group-addon">Gram</span>
							</div>
						</div>

						<div class="form-group">
							<label for="harga">Harga perhiasan :</label>
							<div class="inpt input-group">
								<span class="input-group-addon" id="harga">Rp.  </span>
								<input type="number" class="form-control" id="harga_perhiasan" name="harga_perhiasan" aria-describedby="harga">
								<span class="input-group-addon" id="harga">/gram  </span>
							</div>
						</div>

						<div class="hit form-group">
							<a type="button" class="btn_ btn btn-info" id="zakat_emasperak">Hitung</a>
						</div>
					</form>
				</div>
			</div>

			<div id="profesi">
				<div class="box-body">
					<form method="post">
						<div class="form-group">
							<label for="jumlah">Jumlah Penghasilan :</label>
							<div class="inpt input-group">
								<span class="input-group-addon">Rp.  </span>
								<input type="number" class="form-control" id="jumlah_penghasilan" name="jumlah_penghasilan" aria-describedby="jumlah_">
							</div>
						</div>
						<div class="form-group">
							<label for="bonus">Bonus, THR, Lainnya (jika ada) :</label>
							<div class="inpt input-group">
								<span class="input-group-addon">Rp.  </span>
								<input type="number" class="form-control" id="bonus" name="bonus" aria-describedby="bonus_">
							</div>
						</div>
						<div class="form-group">
							<label for="harga">Harga Beras saat ini :</label>
							<div class="inpt input-group">
								<span class="input-group-addon">Rp.  </span>
								<input type="number" class="form-control" id="harga_beras" name="harga_beras" aria-describedby="harga">
								<span class="input-group-addon">/Kg  </span>
							</div>
						</div>

						<div class="hit form-group">
							<a type="button" class="btn_ btn btn-info" id="zakat_profesi">Hitung</a>
						</div>
					</form>
				</div>
			</div>

			<div id="investasi">
				<div class="box-body">
					<form method="post">
						<div class="form-group">
							<label for="jenis">Jenis penghasilan :</label>
							<div class="inpt selectContainer">
								<select class="form-control" name="jenis" id="jenis">
									<option value="">Pilih jenis penghasilan</option>
									<option value="bersih">Penghasilan bersih</option>
									<option value="kotor">Penghasilan kotor</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="penghasilan">Jumlah penghasilan :</label>
							<div class="inpt input-group">
								<span class="input-group-addon">Rp.  </span>
								<input type="number" class="form-control" id="penghasilan" name="penghasilan" aria-describedby="penghasilan">
								<span class="input-group-addon">/tahun</span>
							</div>
						</div>

						<div class="hit form-group">
							<a type="button" class="btn_ btn btn-info" id="zakat_investasi">Hitung</a>
						</div>
					</form>
				</div>
			</div>

			<div id="tabungan">
				<div class="box-body">
					<form method="post">
						<div class="form-group">
							<label for="simpanan">Jumlah simpanan :</label>
							<div class="inpt input-group">
								<span class="input-group-addon">Rp.  </span>
								<input type="number" class="form-control" id="simpanan_tabungan" name="simpanan_tabungan" aria-describedby="simpanan">
							</div>
						</div>

						<div class="form-group">
							<label for="nisab">Harga emas saat ini :</label>
							<div class="inpt input-group">
								<span class="input-group-addon">Rp.  </span>
								<input type="number" class="form-control" id="nisab_tabungan" name="nisab_tabungan" aria-describedby="nisab">
								<span class="input-group-addon">/gram  </span>
							</div>
						</div>

						<div class="hit form-group">
							<a type="button" class="btn_ btn btn-info" id="zakat_tabungan">Hitung</a>
						</div>
					</form>
				</div>
			</div>

			<div id="rikaz">
				<div class="box-body">
					<form method="post">
						<div class="form-group">
							<label for="jumlah">Jumlah Harta :</label>
							<div class="inpt input-group">
									<span class="input-group-addon">Rp.  </span>
									<input type="number" class="form-control" id="jumlah_rikaz" name="jumlah_rikaz" aria-describedby="modal_">
							</div>
						</div>

						<div class="hit form-group">
							<a type="button" class="btn_ btn btn-info" id="zakat_rikaz">Hitung</a>
						</div>
					</form>
				</div>
			</div>

		</div>

		<div id="output" style="text-align:center;"></div>
	</div>

</div>

@push('scripts')
<script type="text/javascript">
	$(document).ready(function() {

		function convertToRupiah(angka)
        {
            var rupiah = '';		
            var angkarev = angka.toString().split('').reverse().join('');
            for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
            return 'Rp. '+rupiah.split('',rupiah.length-1).reverse().join('');
        }

		//select picker
        $('select').selectpicker();
		$('#fitrah').hide();
		$('#dagang').hide();
		$('#pertanian').hide();
		$('#ternak').hide();
		$('#emas').hide();
		$('#profesi').hide();
		$('#investasi').hide();
		$('#tabungan').hide();
		$('#rikaz').hide();
		var html = document.getElementById("output");

		$("#kalkulator_zakat").change(function() {
            if ($(this).val() == 1 ) {
				html.innerHTML = "";
				$('#fitrah').show();
				$('#dagang').hide();
				$('#pertanian').hide();
				$('#ternak').hide();
				$('#emas').hide();
				$('#profesi').hide();
				$('#investasi').hide();
				$('#tabungan').hide();
				$('#rikaz').hide();
            } else if ($(this).val() == 2 ) {
				html.innerHTML = "";
				$('#fitrah').hide();
				$('#dagang').show();
				$('#pertanian').hide();
				$('#ternak').hide();
				$('#emas').hide();
				$('#profesi').hide();
				$('#investasi').hide();
				$('#tabungan').hide();
				$('#rikaz').hide();
			// } else if ($(this).val() == 3 ) {
			// 	$('#fitrah').hide();
			// 	$('#dagang').hide();
			// 	$('#pertanian').show();
			// 	$('#ternak').hide();
			// 	$('#emas').hide();
			// 	$('#profesi').hide();
			// 	$('#investasi').hide();
			// 	$('#tabungan').hide();
			// 	$('#rikaz').hide();
			// } else if ($(this).val() == 4 ) {
			// 	$('#fitrah').hide();
			// 	$('#dagang').hide();
			// 	$('#pertanian').hide();
			// 	$('#ternak').show();
			// 	$('#emas').hide();
			// 	$('#profesi').hide();
			// 	$('#investasi').hide();
			// 	$('#tabungan').hide();
			// 	$('#rikaz').hide();
			} else if ($(this).val() == 5 ) {
				html.innerHTML = "";
				$('#fitrah').hide();
				$('#dagang').hide();
				$('#pertanian').hide();
				$('#ternak').hide();
				$('#emas').show();
				$('#profesi').hide();
				$('#investasi').hide();
				$('#tabungan').hide();
				$('#rikaz').hide();
			} else if ($(this).val() == 6 ) {
				html.innerHTML = "";
				$('#fitrah').hide();
				$('#dagang').hide();
				$('#pertanian').hide();
				$('#ternak').hide();
				$('#emas').hide();
				$('#profesi').show();
				$('#investasi').hide();
				$('#tabungan').hide();
				$('#rikaz').hide();
			} else if ($(this).val() == 7 ) {
				html.innerHTML = "";
				$('#fitrah').hide();
				$('#dagang').hide();
				$('#pertanian').hide();
				$('#ternak').hide();
				$('#emas').hide();
				$('#profesi').hide();
				$('#investasi').show();
				$('#tabungan').hide();
				$('#rikaz').hide();
			} else if ($(this).val() == 8 ) {
				html.innerHTML = "";
				$('#fitrah').hide();
				$('#dagang').hide();
				$('#pertanian').hide();
				$('#ternak').hide();
				$('#emas').hide();
				$('#profesi').hide();
				$('#investasi').hide();
				$('#tabungan').show();
				$('#rikaz').hide();
			} else if ($(this).val() == 9 ) {
				html.innerHTML = "";
				$('#fitrah').hide();
				$('#dagang').hide();
				$('#pertanian').hide();
				$('#ternak').hide();
				$('#emas').hide();
				$('#profesi').hide();
				$('#investasi').hide();
				$('#tabungan').hide();
				$('#rikaz').show();
			}
        });
        $("#kalkulator_zakat").trigger("change");

		$('#zakat_fitrah').click(function() {
			var jumlah = $('#jumlah').val();
			var beras = $('#harga').val();
			var zakat = +jumlah * parseInt(+beras * 2.7);
			var ket;

			if (jumlah == null || jumlah  == "" || harga == null || harga == "") {
				ket = "<div class='alert alert-danger'>Jumlah keluarga dan harga beras <b>tidak boleh kosong</b> !! </div>";
			} else {
				ket = "<div class='alert alert-info'>ZAKAT yang anda keluarkan sebesar <b>"+convertToRupiah(zakat)+"</b> ATAU <b>"+(+jumlah * 2.7)+" Kilogram </b> Beras </div>"
			}
			html.innerHTML = ket;

		});

		$('#zakat_perdagangan').click(function() {
			var modal = $('#modal').val();
			var untung = $('#untung').val();
			var piutang = $('#piutang').val();
			var hutang = $('#hutang').val();
			var kerugian = $('#kerugian').val();
			var nisab = $('#nisab').val();
			var ket;

			var harta = (+modal + +untung + +piutang) - (+hutang + +kerugian);
			var nisab_ = 85 * nisab;

			if (modal == null || modal  == "" || nisab == null || nisab == "") {
				// alert('Jumlah keluarga dan harga beras <b>tidak boleh kosong</b> !! ');
				ket = "<div class='alert alert-danger'>Modal dan Harga Emas <b>tidak boleh kosong</b> !! </div>";
			} else {
				if (harta >= nisab_) {
					var zakat = harta * 0.025;
					ket = "<div class='alert alert-info'>Zakat yang harus anda bayar senilai <b>Rp. "+convertToRupiah(zakat)+",- </b><br> *Zakat dibayarkan jika telah mencapai 1 tahun haul</div>";
				} else {
					ket = "<div class='alert alert-info'><b> Anda Belum Wajib Bayar Zakat </b><br> *Anda bayar zakat jika penghasilan sudah mencapai nisab "+convertToRupiah(nisab_)+",- ATAU setara dengan 85 Gram Emas</div>";
				}
			}

			html.innerHTML = ket;

		});

		$('#zakat_emasperak').click(function() {
			var harga = $('#harga_perhiasan').val();
			var simpan = $('#simpan').val();
			var pakai = $('#pakai').val();
			var perhiasan = $('#perhiasan').val();
			var jumlah = +simpan - +pakai;
			var ket;

			if (perhiasan == 'emas') {
				if (jumlah >= 85) {
					var zakat = (jumlah * harga) * 0.025;
					console.log(jumlah, harga, zakat);
					ket = "<div class='alert alert-info'>Zakat yang harus anda bayar senilai <b>"+convertToRupiah(zakat)+",- </b><br> *Zakat dibayarkan jika mencapai haul 1 tahun</div>";
				} else {
					ket = "<div class='alert alert-info'><b> Anda Belum Wajib Bayar Zakat </b><br> *Anda bayar zakat jika sudah mencapai nisab 85 Gram Emas</div>";
				}
			} else if (perhiasan == 'perak') {
				if (jumlah >= 595) {
					var zakat = (jumlah * harga) * 0.025;
			 		ket = "<div class='alert alert-info'>Zakat yang harus anda bayar senilai <b>"+convertToRupiah(zakat)+",- </b><br> *Zakat dibayarkan jika mencapai haul 1 tahun</div>";
				} else {
					ket = "<div class='alert alert-info'><b> Anda Belum Wajib Bayar Zakat </b><br> *Anda bayar zakat jika sudah mencapai nisab 595 Gram Perak</div>";
				}
			}

			html.innerHTML = ket;

		});

		$('#zakat_profesi').click(function() {
			var harga = $('#harga_beras').val();
			var bonus = $('#bonus').val();
			var jumlah = $('#jumlah_penghasilan').val();
			var hasil = +jumlah + +bonus;
			var nisab_ = 520 * +harga;
			var ket;

			if (hasil >= nisab_) {
				var zakat = +hasil * 0.025;
				ket = "<div class='alert alert-info'> ZAKAT yang anda keluarkan sebesar <b> "+convertToRupiah(zakat)+",- </b></div>";
			} else {
				ket = "<div class='alert alert-info'><b> Anda belum wajib mengeluarkan ZAKAT.</b><br>*Anda wajib bayar zakat jika penhasilan setara dengan harga 520 kg BERAS atau "+convertToRupiah(nisab_)+",-</div>";
			}

			html.innerHTML = ket;

		})

		$('#zakat_investasi').click(function() {
			var jenis = $('#jenis').val();
			var penghasilan = $('#penghasilan').val();
			var ket;

			if (jenis == "bersih") {
				var zakat = penghasilan * 0.05;
				ket = "<div class='alert alert-info'> ZAKAT yang anda keluarkan sebesar <b> "+convertToRupiah(zakat)+",- </b></div>";
			} else {
				var zakat = penghasilan * 0.1;
				ket = "<div class='alert alert-info'> ZAKAT yang anda keluarkan sebesar <b> "+convertToRupiah(zakat)+",- </b></div>";
			}
			html.innerHTML = ket;
		});

		$('#zakat_tabungan').click(function() {
			var simpanan = $('#simpanan_tabungan').val();
			var nisab = $('#nisab_tabungan').val();
			var nisab_ = 85 * +nisab;
			var ket;

			if (simpanan >= nisab_) {
				var zakat = simpanan * 0.025;
				ket = "<div class='alert alert-info'> ZAKAT yang anda keluarkan sebesar <b>Rp. "+convertToRupiah(zakat)+",- </b><br> *ZAKAT dibayar jika telah mencapai nisab 85 Gram emas dan mencapai haul 1 tahun</div>";
			} else {
				ket = "<div class='alert alert-info'><b> Anda belum wajib mengeluarkan ZAKAT </b><br>*Anda wajib bayar zakat jika jumlah tabungan mencapai nisab 85 Gram emas dan mencapai haul 1 tahun</div>";
			}
			html.innerHTML = ket;
		});

		$('#zakat_rikaz').click(function() {
			var jumlah = $('#jumlah_rikaz').val();
			var zakat = +jumlah * 0.2;
			var ket;

			if (jumlah != "") {
				ket = "<div class='alert alert-info'> ZAKAT yang anda keluarkan sebesar <b>Rp. "+convertToRupiah(zakat)+",- </b></div>";
			} else {
				ket = "<div class='alert alert-danger'>Jumlah harta yang ditemukan <b>tidak boleh kosong</b> !! </div>";
			}
			html.innerHTML = ket;
		});

	});
</script>
@endpush
@endsection