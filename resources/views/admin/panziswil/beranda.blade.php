@extends('template.app')

@section('content')

<div class="row">

	<div class="alert alert-success alert-dismissible">
		<h5><i class="fa fa-exclamation-circle red"></i> Selamat Datang, <b>{{ strtoupper(Auth::user()->nama) }}</b>.</h5>
		<span style="text-align: justify;">Berikut ini adalah halaman aplikasi zakat untuk anda sebagai Panzisda. Jika anda bukan sebagai Panzisda, silahkan hubungi Panziswil anda. Terima Kasih.</span>
	</div>
</div>

<div class="box box-info">
	<!-- <p style="padding: 10px;">
		<button type="button" id="showTable" class="btn btn-success pull-right">
			<i class="fa fa-plus"> Tampilkan Tabel </i>
		</button>
	</p> -->
	<div id="myCharts" style="height:500px;width: 100%;text-align:center;"></div>

	<div class="row">
		<div class="col-md-6">
			<div id="LembagaCharts" style="width: 100%;text-align:center;"></div>
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

		var panzisda = <?php echo json_encode($data['panzisda']); ?>;
		var realisasi = 0;
		var targets = 0;
		var percent = 0;

		var target = [];
		var persentase = [];
		for(x in panzisda) {
			target.push([panzisda[x].name, panzisda[x].target]);
			persentase.push(parseFloat(panzisda[x].persentase));

			realisasi = +realisasi + parseInt(panzisda[x].y);
			targets = +targets + parseInt(panzisda[x].target);
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
				pointFormat: '<tr>'+
					'<td style="color: {series.color}">Wilayah: </td>' +
					'<td style="text-align: right"><b>{point.name}</b></td>' +
					'</tr><tr>'+
					'<td>Target: </td>' +
					'<td style="text-align: right"><b>Rp. {point.y}</b></td></tr>',
				footerFormat: '</table>'
			},
			series: [{
				name: 'Target:<br><p style="font-size: 11px">'+convertToRupiah(targets)+'</p>',
				data: target,
				color: '#000000',
			},
			{
				name: 'Total Realisasi:<br><p style="font-size: 11px">'+convertToRupiah(realisasi)+'</p>',
				data: panzisda,
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
		var target = <?php echo json_encode($data['target']); ?>;
		Highcharts.each(Highcharts.charts, function(p, i) {
			if (p.yAxis[0].max <= target) {
				target = target;
			}
		})
		Highcharts.each(Highcharts.charts, function(p, i) {
			p.yAxis[0].update({
				max: target
			})
		});

		var lembaga = <?php echo json_encode($data['lembaga']); ?>;
		// Create the chart
		let lembagaCharts = Highcharts.chart('LembagaCharts', {
			chart: {
				type: 'pie'
			},
			title: {
				text: 'Persentase Pengumpulan Tiap Lembaga'
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
				data: lembaga,
			}],
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