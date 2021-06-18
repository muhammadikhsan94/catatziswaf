@extends('template.app')

@section('content')

<div class="row">

	<div class="alert alert-success alert-dismissible">
		<h5><i class="fa fa-exclamation-circle red"></i> Selamat Datang, <b>{{ strtoupper(Auth::user()->nama) }}</b>.</h5>
		<span style="text-align: justify;">Berikut ini adalah halaman aplikasi zakat untuk anda sebagai LAZIS. Jika anda bukan sebagai LAZIS, silahkan hubungi Panziswil anda. Terima Kasih.</span>
	</div>
	
</div>

<div class="box box-info">
	<div class="row">
		<div id="WilayahCharts" style="width: 100%;text-align:center;"></div>
		<div id="PaketCharts" style="width: 100%;text-align:center;"></div>
	</div>
</div>

@push('scripts')

<script type="text/javascript">
	$(document).ready(function() {

		var panzisda = <?php echo json_encode($data['panzisda']); ?>;
		// Create the chart
		let wilayahCharts = Highcharts.chart('WilayahCharts', {
			chart: {
				type: 'column'
			},
			title: {
				text: 'Perolehan Pengumpulan Lembaga Berdasarkan Wilayah'
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
					text: 'Transaksi'
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
				enabled: false,
			},
			tooltip: {
				shared: true,
				useHTML: true,
				headerFormat: '<table>',
				pointFormat: '<tr>' +
						'<td style="color: {point.color}">Nama Wilayah: </td>' +
						'<td style="text-align: right"><b>{point.name}</b></td>' +
						'</tr><tr>' +
						'<td>Perolehan: </td>' +
						'<td style="text-align: right"><b>Rp. {point.y}</b></td>' +
						'</tr>',
				footerFormat: '</table>'
			},
			series: [{
				data: panzisda,
			}],
			plotOptions: {
				column: {
					colorByPoint: true,
					pointPadding: 0.2,
					borderWidth: 0
				}
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

		var paketzakat = <?php echo json_encode($data['paketzakat']); ?>;
		// Create the chart
		let paketCharts = Highcharts.chart('PaketCharts', {
			chart: {
				type: 'pie'
			},
			title: {
				text: 'Perolehan Pengumpulan Lembaga Berdasarkan Paket Zakat'
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
					text: 'Transaksi'
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
				enabled: false,
			},
			tooltip: {
				shared: true,
				useHTML: true,
				headerFormat: '<table>',
				pointFormat: '<tr>' +
						'<td style="color: {point.color}">Nama Wilayah: </td>' +
						'<td style="text-align: right"><b>{point.name}</b></td>' +
						'</tr><tr>' +
						'<td>Perolehan: </td>' +
						'<td style="text-align: right"><b>Rp. {point.y}</b></td>' +
						'</tr>',
				footerFormat: '</table>'
			},
			series: [{
				data: paketzakat,
			}],
			plotOptions: {
				pie: {
					colorByPoint: true,
					pointPadding: 0.2,
					borderWidth: 0
				}
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