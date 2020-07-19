<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

?>

<link rel="stylesheet" type="text/css" href="plugins/weatherbit/desktop/weather-icons/css/weather-icons.min.css" />

<div class="container-fluid weatherbit-panel">
	<div class="row">
		<div class="col-md-12">
			<div class="btn-group">

				<?php
				$first = 0;
				$eqLogics = weatherbit::byType('weatherbit', true);
				foreach ($eqLogics as $weatherbit) {
					if ($first == 0 ) {
						$selected = $weatherbit->getId();
						$first = 1;
					}
					echo '<button class="btn btn-default weatherbitEqlogic" id="' . $weatherbit->getId() . '" type="button" onClick="loadingData(' . $weatherbit->getId() . ')">' . $weatherbit->getName() . '</button>';
				}
				?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4" class="panel-body">
			<center><strong> Actuellement </strong></center></br>
			<div style="position : relative; left : 15px;">
				<span class="pull-left">
					<br>
						<i id="icone-status" class="wi #icone#" style="font-size: 42px;"></i>
				</span>

				<div class="pull-right" style="margin-right: 20px;margin-top: 0px;">
					<div id="wind-status" style="width: 80px; height: 80px;"></div>
					<center><i class="wi wi-strong-wind"></i><div class="weather-status" data-l1key="windSpeed" style="margin-left: 5px;font-size: 0.8em;"></div></center>
				</div>
				<i class="jeedom-thermo-moyen"></i><span class="weather-status" data-l1key="temperature" style="margin-left: 5px;">   </span><span class="weather-status" data-l1key="apparentTemperature" style="margin-left: 5px;font-size: 0.8em;"> </span><br/>
				<span class="weather-status" data-l1key="summary" style="margin-left: 5px;">   </span><br/>
			</br>
			<i class="wi wi-humidity"></i><span class="weather-status" data-l1key="humidity" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-cloud"></i><span class="weather-status" data-l1key="cloudCover" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-umbrella"></i><span class="weather-status" data-l1key="precipProbability" style="margin-left: 5px;font-size: 0.8em;">  </span></span><i class="wi wi-hot"></i><span class="weather-status" data-l1key="uvIndex" style="margin-left: 5px;font-size: 0.8em;">  </span><br/>
			<i class="wi wi-barometer"></i><span class="weather-status" data-l1key="pressure" style="margin-left: 5px;font-size: 0.8em;">  </span>

		</div>

	</div>
	<div class="col-md-4">
		<center><strong> Dans 1H </strong></center></br>
		<div style="position : relative; left : 15px;">
			<span class="pull-left">
				<br>
					<i id="icone-hour" class="wi #icone#" style="font-size: 42px;"></i>
			</span>

			<div class="pull-right" style="margin-right: 20px;margin-top: 0px;">
				<div id="wind-hour" style="width: 80px; height: 80px;"></div>
				<center><i class="wi wi-strong-wind"></i><div class="weather-hour" data-l1key="windSpeed" style="margin-left: 5px;font-size: 0.8em;"></div></center>
			</div>
			<i class="jeedom-thermo-moyen"></i><span class="weather-hour" data-l1key="temperature" style="margin-left: 5px;">   </span><span class="weather-hour" data-l1key="apparentTemperature" style="margin-left: 5px;font-size: 0.8em;"> </span><br/>
			<span class="weather-hour" data-l1key="summary" style="margin-left: 5px;">   </span><br/>
		</br>
		<i class="wi wi-humidity"></i><span class="weather-hour" data-l1key="humidity" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-cloud"></i><span class="weather-hour" data-l1key="cloudCover" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-umbrella"></i><span class="weather-hour" data-l1key="precipProbability" style="margin-left: 5px;font-size: 0.8em;">  </span></span><i class="wi wi-hot"></i><span class="weather-hour" data-l1key="uvIndex" style="margin-left: 5px;font-size: 0.8em;">  </span><br/>
		<i class="wi wi-barometer"></i><span class="weather-hour" data-l1key="pressure" style="margin-left: 5px;font-size: 0.8em;">  </span> <i class="fas fa-flask"></i> <span class="weather-hour" data-l1key="ozone" style="margin-left: 5px;font-size: 0.8em;">   </span>

	</div>
</div>
<div class="col-md-4">
	<center><strong> Aujourd'hui </strong></center></br>
	<div style="position : relative; left : 15px;">
		<span class="pull-left">
			<br>
				<i id="icone-day0" class="wi #icone#" style="font-size: 42px;"></i>
		</span>

		<div class="pull-right" style="margin-right: 20px;margin-top: 0px;">
			<div id="wind-day0" style="width: 80px; height: 80px;"></div>
			<center><i class="wi wi-strong-wind"></i><div class="weather-day0" data-l1key="windSpeed" style="margin-left: 5px;font-size: 0.8em;"></div></center>
		</div>
		<i class="jeedom-thermo-moyen"></i><span class="weather-day0" data-l1key="temperatureMin" style="margin-left: 5px;">   </span> / <span class="weather-day0" data-l1key="temperatureMax" style="margin-left: 5px;"> </span><br/>
		<span class="weather-day0" data-l1key="summary" style="margin-left: 5px;">   </span><br/>
	</br>
	<i class="wi wi-humidity"></i><span class="weather-day0" data-l1key="humidity" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-cloud"></i><span class="weather-day0" data-l1key="cloudCover" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-umbrella"></i><span class="weather-day0" data-l1key="precipProbability" style="margin-left: 5px;font-size: 0.8em;">  </span></span><i class="wi wi-hot"></i><span class="weather-day0" data-l1key="uvIndex" style="margin-left: 5px;font-size: 0.8em;">  </span><br/>
	<i class="wi wi-barometer"></i><span class="weather-day0" data-l1key="pressure" style="margin-left: 5px;font-size: 0.8em;">  </span> <i class="fas fa-flask"></i> <span class="weather-day0" data-l1key="ozone" style="margin-left: 5px;font-size: 0.8em;">   </span>

	<div>
		<i class="wi wi-sunrise"></i><span class="weather-day0" data-l1key="sunriseTime" style="font-size: 0.8em;"></span><i class="wi wi-sunset"></i><span class="weather-day0" data-l1key="sunsetTime" style="font-size: 0.8em;"></span>
	</div>
</div>
</div>
</div>
</br>
<div class="row">
	<div class="col-md-12">
		<div id="previsions">

		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-4">
		<center><strong> Jour +1 </strong></center></br>
		<div style="position : relative; left : 15px;">
			<span class="pull-left">
				<br>
					<i id="icone-day1" class="wi #icone#" style="font-size: 42px;"></i>
			</span>

			<div class="pull-right" style="margin-right: 20px;margin-top: 0px;">
				<div id="wind-day1" style="width: 80px; height: 80px;"></div>
				<center><i class="wi wi-strong-wind"></i><div class="weather-day1" data-l1key="windSpeed" style="margin-left: 5px;font-size: 0.8em;"></div></center>
			</div>
			<i class="jeedom-thermo-moyen"></i><span class="weather-day1" data-l1key="temperatureMin" style="margin-left: 5px;">   </span> / <span class="weather-day1" data-l1key="temperatureMax" style="margin-left: 5px;"> </span><br/>
			<span class="weather-day1" data-l1key="summary" style="margin-left: 5px;">   </span><br/>
		</br>
		<i class="wi wi-humidity"></i><span class="weather-day1" data-l1key="humidity" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-cloud"></i><span class="weather-day1" data-l1key="cloudCover" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-umbrella"></i><span class="weather-day1" data-l1key="precipProbability" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-hot"></i><span class="weather-day1" data-l1key="uvIndex" style="margin-left: 5px;font-size: 0.8em;">  </span><br/>
		<i class="wi wi-barometer"></i><span class="weather-day1" data-l1key="pressure" style="margin-left: 5px;font-size: 0.8em;">  </span> <i class="fas fa-flask"></i> <span class="weather-day1" data-l1key="ozone" style="margin-left: 5px;font-size: 0.8em;">   </span>

		<div>
			<i class="wi wi-sunrise"></i><span class="weather-day1" data-l1key="sunriseTime" style="font-size: 0.8em;"></span><i class="wi wi-sunset"></i><span class="weather-day1" data-l1key="sunsetTime" style="font-size: 0.8em;"></span>
		</div>
	</div>
</div>
<div class="col-md-4">
	<center><strong> Jour +2 </strong></center></br>
	<div style="position : relative; left : 15px;">
		<span class="pull-left">
			<br>
				<i id="icone-day2" class="wi #icone#" style="font-size: 42px;"></i>
		</span>

		<div class="pull-right" style="margin-right: 20px;margin-top: 0px;">
			<div id="wind-day2" style="width: 80px; height: 80px;"></div>
			<center><i class="wi wi-strong-wind"></i><div class="weather-day2" data-l1key="windSpeed" style="margin-left: 5px;font-size: 0.8em;"></div></center>
		</div>
		<i class="jeedom-thermo-moyen"></i><span class="weather-day2" data-l1key="temperatureMin" style="margin-left: 5px;">   </span> / <span class="weather-day2" data-l1key="temperatureMax" style="margin-left: 5px;"> </span><br/>
		<span class="weather-day2" data-l1key="summary" style="margin-left: 5px;">   </span><br/>
	</br>
	<i class="wi wi-humidity"></i><span class="weather-day2" data-l1key="humidity" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-cloud"></i><span class="weather-day2" data-l1key="cloudCover" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-umbrella"></i><span class="weather-day2" data-l1key="precipProbability" style="margin-left: 5px;font-size: 0.8em;">  </span></span><i class="wi wi-hot"></i><span class="weather-day2" data-l1key="uvIndex" style="margin-left: 5px;font-size: 0.8em;">  </span><br/>
	<i class="wi wi-barometer"></i><span class="weather-day2" data-l1key="pressure" style="margin-left: 5px;font-size: 0.8em;">  </span> <i class="fas fa-flask"></i> <span class="weather-day2" data-l1key="ozone" style="margin-left: 5px;font-size: 0.8em;">   </span>

	<div>
		<i class="wi wi-sunrise"></i><span class="weather-day2" data-l1key="sunriseTime" style="font-size: 0.8em;"></span><i class="wi wi-sunset"></i><span class="weather-day2" data-l1key="sunsetTime" style="font-size: 0.8em;"></span>
	</div>
</div>
</div>
<div class="col-md-4">
	<center><strong> Jour +3 </strong></center></br>
	<div style="position : relative; left : 15px;">
		<span class="pull-left">
			<br>
				<i id="icone-day3" class="wi #icone#" style="font-size: 42px;"></i>
		</span>

		<div class="pull-right" style="margin-right: 20px;margin-top: 0px;">
			<div id="wind-day3" style="width: 80px; height: 80px;"></div>
			<center><i class="wi wi-strong-wind"></i><div class="weather-day3" data-l1key="windSpeed" style="margin-left: 5px;font-size: 0.8em;"></div></center>
		</div>
		<i class="jeedom-thermo-moyen"></i><span class="weather-day3" data-l1key="temperatureMin" style="margin-left: 5px;">   </span> / <span class="weather-day3" data-l1key="temperatureMax" style="margin-left: 5px;"> </span><br/>
		<span class="weather-day3" data-l1key="summary" style="margin-left: 5px;">   </span><br/>
	</br>
	<i class="wi wi-humidity"></i><span class="weather-day3" data-l1key="humidity" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-cloud"></i><span class="weather-day3" data-l1key="cloudCover" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-umbrella"></i><span class="weather-day3" data-l1key="precipProbability" style="margin-left: 5px;font-size: 0.8em;">  </span></span><i class="wi wi-hot"></i><span class="weather-day3" data-l1key="uvIndex" style="margin-left: 5px;font-size: 0.8em;">  </span><br/>
	<i class="wi wi-barometer"></i><span class="weather-day3" data-l1key="pressure" style="margin-left: 5px;font-size: 0.8em;">  </span> <i class="fas fa-flask"></i> <span class="weather-day3" data-l1key="ozone" style="margin-left: 5px;font-size: 0.8em;">   </span>

	<div>
		<i class="wi wi-sunrise"></i><span class="weather-day3" data-l1key="sunriseTime" style="font-size: 0.8em;"></span><i class="wi wi-sunset"></i><span class="weather-day3" data-l1key="sunsetTime" style="font-size: 0.8em;"></span>
	</div>
</div>
</div>
</div>
</div>

<script>
$(function () {

	loadingData(
		<?php
		echo $selected;
		?>);

	});

	function loadingData(eqLogic){

		$.ajax({// fonction permettant de faire de l'ajax
		type: "POST", // methode de transmission des données au fichier php
		url: "plugins/weatherbit/core/ajax/weatherbit.ajax.php", // url du fichier php
		data: {
			action: "loadingData",
			value: eqLogic,
		},
		dataType: 'json',
		error: function (request, status, error) {
			handleAjaxError(request, status, error);
		},
		success: function (data) { // si l'appel a bien fonctionné
		if (data.state != 'ok') {
			$('#div_alert').showAlert({message: data.result, level: 'danger'});
			return;
		}

		$('.weatherbitEqlogic').removeClass('btn-success');
		$('.weatherbitEqlogic').addClass('btn-default');
		$('#' + eqLogic).removeClass('btn-default');
		$('#' + eqLogic).addClass('btn-success');

		$('.weather-status').value('');
		for (var i in data.result.status) {
			$('.weather-status[data-l1key=' + i + ']').value(data.result.status[i]);
		}
		$('.weather-hour').value('');
		for (var i in data.result.hour) {
			$('.weather-hour[data-l1key=' + i + ']').value(data.result.hour[i]);
		}
		$('.weather-day0').value('');
		for (var i in data.result.day0) {
			$('.weather-day0[data-l1key=' + i + ']').value(data.result.day0[i]);
		}
		$('.weather-day1').value('');
		for (var i in data.result.day1) {
			$('.weather-day1[data-l1key=' + i + ']').value(data.result.day1[i]);
		}
		$('.weather-day2').value('');
		for (var i in data.result.day2) {
			$('.weather-day2[data-l1key=' + i + ']').value(data.result.day2[i]);
		}
		$('.weather-day3').value('');
		for (var i in data.result.day3) {
			$('.weather-day3[data-l1key=' + i + ']').value(data.result.day3[i]);
		}

		$("#icone-status").attr('class', 'wi ' + data.result.status.icon);
		$("#icone-hour").attr('class', 'wi ' + data.result.hour.icon);
		$("#icone-day0").attr('class', 'wi ' + data.result.day0.icon);
		$("#icone-day1").attr('class', 'wi ' + data.result.day1.icon);
		$("#icone-day2").attr('class', 'wi ' + data.result.day2.icon);
		$("#icone-day3").attr('class', 'wi ' + data.result.day3.icon);

		roseTrace('wind-status',data.result.status.windBearing);
		roseTrace('wind-hour',data.result.hour.windBearing);
		roseTrace('wind-day0',data.result.day0.windBearing);
		roseTrace('wind-day1',data.result.day1.windBearing);
		roseTrace('wind-day2',data.result.day2.windBearing);
		roseTrace('wind-day3',data.result.day3.windBearing);

		//console.log(data.result.temp.value);

		var options = {
			title : {	text : 'Prévisions'	},
			subtitle: {
				text: 'Température, pression et précipitation des 48h',
				x: -20
			},
			chart: { renderTo: 'previsions' },
			xAxis: {
				type: 'datetime',
			},

			yAxis: [{ // temperature axis
				title: {
					text: 'Température (°C)'
				},
			}, { // precipitation axis
				title: {
					text: null
				},
				labels: {
					enabled: false
				},
				gridLineWidth: 0,
				tickLength: 0
			}, { // Air pressure
				allowDecimals: false,
				title: { // Title on top of axis
					text: 'Pression (hPa)',
				},
				gridLineWidth: 0,
				opposite: true,
				showLastLabel: false
			}],
			credits: {
				enabled: false
			},
			xAxis: {
				categories: [],
				labels: {
					rotation: -45,
					y: 20
				}
			},
			series: [{
				name: 'Température',
				tooltip: {
					valueSuffix: ' °C'
				},
				color: '#00FF00',
				negativeColor: '#48AFE8',
				data: []
			},
			{
				name: 'Précipitations',
				type: 'column',
				color: '#0000FF',
				yAxis: 1,
				tooltip: {
					valueSuffix: ' mm/h'
				},
				data: []
			},
			{
				name: 'Pression',
				yAxis: 2,
				tooltip: {
					valueSuffix: ' hPa'
				},
				data: []
			},
            {
				name: 'Index UV',
				tooltip: {
					valueSuffix: ''
				},
				color: '#FF0000',
				data: []
			}
			],
		};

		for (var i in data.result.previsions.time) {
			//console.log(data.result.previsions.temperature[i]);
			var date = new Date(parseInt(data.result.previsions.time[i]));
			var displayDate = date.getDate() + '/' + (date.getMonth()+1) + ' ' + date.getHours() + ':' + date.getMinutes().padStart(2, "0");
			options.series[0].data.push(parseFloat(data.result.previsions.temperature[i],2));
			options.series[1].data.push(parseFloat(data.result.previsions.precipIntensity[i],2));
			options.series[2].data.push(parseInt(data.result.previsions.pressure[i]));
            options.series[3].data.push(parseInt(data.result.previsions.uvIndex[i]));
			options.xAxis.categories.push(displayDate);

		};

		var chart = new Highcharts.Chart(options);

	}
});
}

function roseTrace(id,value){
	$color = $("html").css("bg-color");
	new Highcharts.Chart({
		chart: {
			renderTo: id,
			type: 'gauge',
			backgroundColor: $color,
			plotBackgroundColor: null,
			plotBackgroundImage: null,
			plotBorderWidth: 0,
			plotShadow: false,
			spacingTop: 0,
			spacingLeft: 0,
			spacingRight: 0,
			spacingBottom: 0
		},
		title: {
			text: null
		},
		credits: {
			enabled: false
		},
		pane: {
			startAngle: 0,
			endAngle: 360,
		},
		exporting : {
			enabled: false
		},
		plotOptions: {
			series: {
				dataLabels: {
					enabled: false
				},
				color: '#000000',
			},
			gauge: {
				dial: {
					radius: '90%',
					backgroundColor: 'silver',
					borderColor: 'silver',
					borderWidth: 1,
					baseWidth: 6,
					topWidth: 1,
					baseLength: '75%', // of radius
					rearLength: '15%'
				},
				pivot: {
					backgroundColor: 'white',
					radius: 0,
				}
			}
		},
		pane: {background: [{backgroundColor: 'transparent'}]},
		yAxis: {
			min: 0,
			max: 360,
			tickWidth: 2,
			tickLength: 10,
			tickColor: '#000000',
			tickInterval: 90,
			lineColor: '#000000',
			lineWidth: 4,
			labels: {
				formatter: function () {
					if (this.value == 360) {
						return '<span style="color : #000000;font-weight:bold;">N</span>';
					} else if (this.value == 90) {
						return '<span style="color : #000000;font-weight:bold;">E</span>';
					} else if (this.value == 180) {
						return '<span style="color : #000000;font-weight:bold;">S</span>';
					} else if (this.value == 270) {
						return '<span style="color : #000000;font-weight:bold;">W</span>';
					}
				}
			},
			title: {
				text: null
			}},
			series: [{
				name: 'Vent',
				data: [value]
			}]
		});

	}



	</script>
