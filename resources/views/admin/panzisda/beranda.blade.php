@extends('template.app')

@section('content')

<div class="row">

	<div class="alert alert-success alert-dismissible">
		<h5><i class="fa fa-exclamation-circle red"></i> Selamat Datang, <b>{{ strtoupper(Auth::user()->nama) }}</b>.</h5>
		<span style="text-align: justify;">Berikut ini adalah halaman aplikasi zakat untuk anda sebagai Panzisda. Jika anda bukan sebagai Panzisda, silahkan hubungi Panziswil anda. Terima Kasih.</span>
	</div>

</div>

<div class="box box-info">
	<div id="myCharts" style="height:500px;width: 100%;text-align:center;"></div>

	<div class="row">
		<div class="col-md-6">
			<div id="RealisasiCharts" style="width: 100%;text-align:center;"></div>
		</div>
		<div class="col-md-6">
			<div id="PaketCharts" style="width: 100%;text-align:center;"></div>
		</div>
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

		var manajer = <?php echo json_encode($data['manajer']); ?>;
		var duta = <?php echo json_encode($data['duta']); ?>;
		var target = [];
		var persentase = [];
		var realisasi = 0;
		var targets = 0;
		var percent = 0;

		for(x in manajer) {
			target.push([manajer[x].name, manajer[x].target, manajer[x].nama]);
			persentase.push(parseFloat(manajer[x].persentase));
			console.log(manajer[x].nama);

			realisasi = +realisasi + parseInt(manajer[x].y);
			targets = +targets + parseInt(manajer[x].target);
		}

		percent = parseFloat((realisasi / targets) * 100);

		// Create the chart
		let charts = Highcharts.chart('myCharts', {
			chart: {
				type: 'column'
			},
			title: {
				text: 'Transaksi'
			},
			accessibility: {
				announceNewData: {
					enabled: true
				}
			},
			xAxis: {
				type: 'category',
			},
			yAxis: {
				title: {
					text: 'Total Transaksi'
				},
				labels: {
					formatter: function() {
						var absValue = Math.abs(this.value);
						if (absValue >= 1000000 && absValue < 1000000000) {
							absValue = (this.value / 1000000) + ' JUTA';
						} else if (absValue >= 1000000000) {
							absValue = (this.value / 1000000000) + ' MILIAR';
						}
						return absValue;
						// return Highcharts.numberFormat(this.value, 0);
					}
				}
			},
			legend: {
				align: 'right',
				verticalAlign: 'middle',
				layout: 'vertical'
			},
			tooltip: {
				shared: true,
				useHTML: true,
				headerFormat: '<table>',
				pointFormat: '<tr>' +
					'<td>Duta Zakat: </td>' +
					'<td style="text-align: right"><b>{point.name} {point.nama}</b></td>' +
					'</tr><tr>' +
					'<td style="color: black">Target: </td>' +
					'<td style="text-align: right"><b>Rp. {point.y}</b></td>' +
					'</tr>',
				footerFormat: '</table>'
			},
			series: [{
				name: 'Target:<br><p style="font-size: 11px">'+convertToRupiah(targets)+'</p>',
				data: target,
				color: '#000000',
			},
			{
				name: 'Total Realisasi:<br><p style="font-size: 11px">'+convertToRupiah(realisasi)+'</p>',
				data: manajer,
				color: '#00CED1',
				tooltip: {
					shared: true,
					useHTML: true,
					headerFormat: '<table>',
					pointFormat: '<tr><td>Realisasi: </td>' +
						'<td style="text-align: right"><b>Rp. {point.y}</b></td></tr>',
					footerFormat: '</table>'
				}
			},
			{
				name: 'Persentase:<br><p style="font-size: 11px">'+percent.toFixed(2)+' %</p>',
				data: persentase,
				color: '#008000',
				tooltip: {
					shared: true,
					useHTML: true,
					headerFormat: '<table>',
					pointFormat: '<tr><td>Persentase: </td>' +
						'<td style="text-align: right"><b>{point.y} %</b></td></tr>',
					footerFormat: '</table>'
				}
			}],
			drilldown: {
				series: duta,
			},
			responsive: {  
				rules: [{  
					condition: {  
						maxWidth: 700  
					},  
					chartOptions: {  
						legend: {  
							enabled: false  
						}  
					}  
				}]  
			}
		});

		var realisasi = <?php echo json_encode($data['realisasi']); ?>;
		var targets = [];
		for (x in realisasi) {
			targets.push(realisasi[x].target);
		}
		// Create the chart
		let realisasiCharts = Highcharts.chart('RealisasiCharts', {
			chart: {
				type: 'column'
			},
			title: {
				text: 'Realisasi Pengumpulan Panzisda'
			},
			accessibility: {
				announceNewData: {
					enabled: true
				}
			},
			xAxis: {
				type: 'category',
			},
			yAxis: {
				title: {
					text: 'Total Transaksi'
				},
				labels: {
					formatter: function() {
						var absValue = Math.abs(this.value);
						if (absValue >= 1000000 && absValue < 1000000000) {
							absValue = (this.value / 1000000) + ' JUTA';
						} else if (absValue >= 1000000000) {
							absValue = (this.value / 1000000000) + ' MILIAR';
						}
						return absValue;
					}
				}
			},
			legend: {
				align: 'right',
				verticalAlign: 'middle',
				layout: 'vertical'
			},
			tooltip: {
				shared: true,
				useHTML: true,
			},
			series: [{
				name: 'Realisasi',
				data: realisasi,
				tooltip: {
					shared: true,
					useHTML: true,
					headerFormat: '<table>',
					pointFormat: '<tr>' +
						'<td style="color: red">Nama Wilayah: </td>' +
						'<td style="text-align: right"><b>{point.name}</b></td>' +
						'</tr><tr>' +
						'<td style="color: blue">Persentase: </td>' +
						'<td style="text-align: right"><b>{point.persentase} %</b></td>' +
						'</tr><tr>' +
						'<td style="color: {series.color}">{series.name}: </td>' +
						'<td style="text-align: right"><b>Rp. {point.y_}</b></td>' +
						'</tr>',
					footerFormat: '</table>'
				},
			},
			{
				name: 'Target',
				data: targets,
				tooltip: {
					shared: true,
					useHTML: true,
					headerFormat: '<table>',
					pointFormat: '<tr><td style="color: {series.color}">{series.name}: </td>' +
						'<td style="text-align: right"><b>Rp. {point.y}</b></td></tr>',
					footerFormat: '</table>'
				}
			}],
			responsive: {  
				rules: [{  
					condition: {  
						maxWidth: 700  
					},  
					chartOptions: {  
						legend: {  
							enabled: false  
						}  
					}  
				}]  
			}
		});

		var paketzakat = <?php echo json_encode($data['paketzakat']); ?>;
		// Create the chart
		let paketCharts = Highcharts.chart('PaketCharts', {
			chart: {
				type: 'pie'
			},
			title: {
				text: 'Persentase Pengumpulan Tiap Paket Zakat'
			},
			accessibility: {
				announceNewData: {
					enabled: true
				}
			},
			xAxis: {
				type: 'category',
			},
			yAxis: {
				title: {
					text: 'Total Transaksi'
				}
			},
			legend: {
				enabled: false
			},
			tooltip: {
				headerFormat: '<span style="font-size:15px;text-align:center;">{point.key}</span><table>',
				pointFormat: '<tr>' +
				'<td>Persentase: </td>' +
				'<td style="text-align: right"><b>{point.persentase} %</b></td>' +
				'</tr><tr>' +
				'<td>Dana Terkumpul: </td>' +
				'<td style="text-align: right"><b>Rp. {point.y}</b></td>' +
				'</tr>',
				footerFormat: '</table>',
				shared: true,
				useHTML: true
			},
			plotOptions: {
				column: {
					colorByPoint: true,
					pointPadding: 0.2,
					borderWidth: 0
				}
			},
			series: [{
				data: paketzakat,
			}],
		});
	});
</script>
<script src="{{asset('lte/dist/js/highcharts.js')}}"></script>
<script src="{{asset('lte/dist/js/data.js')}}"></script>
<script src="{{asset('lte/dist/js/drilldown.js')}}"></script>
<script src="{{asset('lte/dist/js/exporting.js')}}"></script>
<script src="{{asset('lte/dist/js/export-data.js')}}"></script>
<script src="{{asset('lte/dist/js/accessibility.js')}}"></script>
@endpush

@endsection