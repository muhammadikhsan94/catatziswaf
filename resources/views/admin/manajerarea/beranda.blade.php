@extends('template.app')

@section('content')

<div class="row">

	<div class="alert alert-success alert-dismissible">
		<h5><i class="fa fa-exclamation-circle red"></i> Selamat Datang, <b>{{ strtoupper(Auth::user()->nama) }}</b>.</h5>
		<span style="text-align: justify;">Berikut ini adalah halaman aplikasi zakat untuk anda sebagai Manajer Area. Jika anda bukan sebagai Manajer Area, silahkan hubungi Panzisda wilayah anda. Terima Kasih.</span>
	</div>
	
</div>

<div class="box box-info">
	<div class="dataTables_scrollBody">
		<div id="myCharts" style="width: 100%;min-height: 500px;text-align:center;"></div>
	</div>
</div>

@push('scripts')
<script type="text/javascript">
	$(document).ready(function() {
		function konversi(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
		var manajer = <?php echo json_encode($data['manajer']); ?>;
		var duta = <?php echo json_encode($data['duta']); ?>;
		var target_manajer = [];
		for(x in manajer) {
			target_manajer.push(manajer[x].target);
		}
		var target_duta = [];
		for(x in duta) {
			target_duta.push(duta[x].target);
		}
		// Create the chart
		let charts = Highcharts.chart('myCharts', {
			chart: {
				type: 'column'
			},
			title: {
				text: 'Transaksi'
			},
			subtitle: {
				text: 'Data Manajer dan Duta'
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
					'<td style="color: {series.color}">Duta Zakat: </td>' +
					'<td style="text-align: right"><b>{point.name}</b></td>' +
					'</tr><tr>' +
					'<td style="color: {series.color}">Persentase: </td>' +
					'<td style="text-align: right"><b>{point.persentase} %</b></td>' +
					'</tr><tr>' +
					'<td style="color: {series.color}">Realisasi: </td>' +
					'<td style="text-align: right"><b>Rp. {point.y_}</b></td>' +
					'</tr><tr>' +
					'<td style="color: {series.color}">Target: </td>' +
					'<td style="text-align: right"><b>Rp. {point.target}</b></td>' +
					'</tr>',
				footerFormat: '</table>'
			},
			series: [{
				name: 'Realisasi',
				data: manajer,
				tooltip: {
					shared: true,
					useHTML: true,
					headerFormat: '<table>',
					pointFormat: '<tr>' +
						'<td style="color: red">Manajer: </td>' +
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
				data: target_manajer,
				tooltip: {
					shared: true,
					useHTML: true,
					headerFormat: '<table>',
					pointFormat: '<tr><td style="color: {series.color}">{series.name}: </td>' +
						'<td style="text-align: right"><b>Rp. {point.y}</b></td></tr>',
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