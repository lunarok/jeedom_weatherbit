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
<div class="row">
	<div class="col-md-4">
		<center><strong> Azimuth </strong></center></br>
		<div class="pull-right" style="margin-right: 15px;margin-top: 5px;">
			<div id="azimuth" style="width: 80px; height: 80px;"></div>
		</div>
		<div class="pull-left" style="margin-left: 15px;margin-top: 5px;">
			<div id="sunAlt" style="width: 80px; height: 80px;"></div>
    </div><br/>
		<div style="margin-left: 70px; margin-right: 100px; margin-top: 0px;">
			<center><i class="far fa-sun"></i></center>
			<center style="font-size: 1em; position: relative;left:3px;cursor:default;"><span class="helio" data-l1key="sunrise" style="font-size: 0.8em;"></span> - <span class="helio" data-l1key="sunset" style="font-size: 0.8em;"></span></center>
			<center><i class="far fa-moon"></i></center>
			<center style="font-size: 1em; position: relative;left:3px;cursor:default;"><span class="helio" data-l1key="moonrise" style="font-size: 0.8em;"></span> - <span class="helio" data-l1key="moonset" style="font-size: 0.8em;"></span></center>
		</div>
</div>
<div class="col-md-4">
	<center><strong> Qualité d'Air </strong></center></br>
	<center><div style="display: table; overflow: hidden; position: relative; top: -10px;">
				<center><div class="air-general" style="background-color:#aqicolor#;color:#aqifont#;"><center><span class="aqi" data-l1key="aqi" style="font-size: 0.8em;"></span></center></div></center>
			</div></center>
				<div style="display: table; overflow: hidden; width: 95%">
					<div style="display: table-row;">
						<div style="display: table-cell; width: 33%;cursor:default;font-size: 1em;" class="cmd noRefresh" data-type="info" data-subtype="string" title="Dioxyde d&#145;azote">
							<center><strong>NO</strong><sub style="font-size: 0.6em;">2</sub></center>
							<center><span class="aqi" data-l1key="no2" style="font-size: 0.8em;"></span></center>
						</div>
						<div style="display: table-cell; width: 33%;cursor:default;font-size: 1em;" class="cmd noRefresh" data-type="info" data-subtype="string" title="Ozone">
							<center><strong>O</strong><sub style="font-size: 0.6em;">3</sub></center>
							<center><span class="aqi" data-l1key="o3" style="font-size: 0.8em;"></span></center>
						</div>
						<div style="display: table-cell; width: 33%;cursor:default;font-size: 1em;" class="cmd noRefresh" data-type="info" data-subtype="string" title="Ozone">
							<center><strong>CO</strong></center>
							<center><span class="aqi" data-l1key="co" style="font-size: 0.8em;"></span></center>
						</div>
						</div>
						<div style="display: table-row;">
						<div style="display: table-cell; width: 33%;cursor:default;font-size: 1em;" class="cmd noRefresh" data-type="info" data-subtype="string" title="Ozone">
							<center><strong>SO</strong><sub style="font-size: 0.6em;">2</sub></center>
							<center><span class="aqi" data-l1key="so2" style="font-size: 0.8em;"></span></center>
						</div>
						<div style="display: table-cell; width: 33%;cursor:default;font-size: 1em;" class="cmd noRefresh" data-type="info" data-subtype="string" title="Particules fines &lt; 2,5&micro;m (particules de combustion...)">
							<center><strong>PM</strong><sub style="font-size: 0.6em;">2,5</sub></center>
				    			<center><span class="aqi" data-l1key="pm25" style="font-size: 0.8em;"></span></center>
						</div>
						<div style="display: table-cell; width: 33%;cursor:default;font-size: 1em;" class="cmd noRefresh" data-type="info" data-subtype="string" title="Particules fines &lt; 10&micro;m (poussi&egrave;re, pollen...)">
							<center><strong>PM</strong><sub style="font-size: 0.6em;">10</sub></center>
							<center><span class="aqi" data-l1key="pm10" style="font-size: 0.8em;"></span></center>
						</div>
					</div>
				</div>
</div>
<div class="col-md-4">
	<center><strong> Pollens </strong></center></br>
	<center><div style="display: table; overflow: hidden; position: relative; top: -10px;">
				<center>Dominant : <span class="pollen" data-l1key="predominant_pollen_type" style="font-size: 0.8em;"></span></center>
			</div></center>
				<div style="display: table; overflow: hidden; width: 95%">
					<div style="display: table-row;">
						<div style="display: table-cell; width: 33%;cursor:default;font-size: 1em;" class="cmd noRefresh" data-type="info" data-subtype="string" title="Dioxyde d&#145;azote">
							<center><strong>Graminés</strong></center>
							<center><span class="pollen" data-l1key="pollen_level_weed" style="font-size: 0.8em;"></span></center>
						</div>
						<div style="display: table-cell; width: 33%;cursor:default;font-size: 1em;" class="cmd noRefresh" data-type="info" data-subtype="string" title="Ozone">
							<center><strong>Herbe</strong></center>
							<center><span class="pollen" data-l1key="pollen_level_grass" style="font-size: 0.8em;"></span></center>
						</div>
						<div style="display: table-cell; width: 33%;cursor:default;font-size: 1em;" class="cmd noRefresh" data-type="info" data-subtype="string" title="Ozone">
							<center><strong>Arbres</strong></center>
							<center><span class="pollen" data-l1key="pollen_level_tree" style="font-size: 0.8em;"></span></center>
						</div>
						</div>
				</div>
</div>
</div>
</div>

<style>
	.air-general {
	  display: table-cell;
	  vertical-align: middle;
	  align: center;
	  cursor:default;
	  font-size: 1.5em;
	  font-weight: bold;
	  border-style: solid;
	  border-width: 1px;
	  border-color: #ffffff;
	  border-radius:19px;
	  width:38px;
	  height:38px;
	}
</style>

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

		$('.aqi').value('');
		for (var i in data.result.aqi) {
			$('.aqi[data-l1key=' + i + ']').value(data.result.aqi[i]);
		}

		$('.pollen').value('');
		for (var i in data.result.pollen) {
			$('.pollen[data-l1key=' + i + ']').value(data.result.pollen[i]);
		}

		$('.helio').value('');
		for (var i in data.result.helio) {
			$('.helio[data-l1key=' + i + ']').value(data.result.helio[i]);
		}

		$("#icone-status").attr('class', 'wi ' + data.result.status.icon);
		$("#icone-hour").attr('class', 'wi ' + data.result.hour.icon);
		$("#icone-day0").attr('class', 'wi ' + data.result.day0.icon);
		$("#icone-day1").attr('class', 'wi ' + data.result.day1.icon);
		$("#icone-day2").attr('class', 'wi ' + data.result.day2.icon);
		$("#icone-day3").attr('class', 'wi ' + data.result.day3.icon);

		if (data.result.aqi.aqi <= 50) {
			$(".air-general").attr('style', 'background-color:#00ff1e;color:black;');
		} else if (data.result.aqi.aqi <= 100) {
			$(".air-general").attr('style', 'background-color:#FFde33;color:black;');
		} else if (data.result.aqi.aqi <= 150) {
			$(".air-general").attr('style', 'background-color:#FF9933;color:white;');
		} else if (data.result.aqi.aqi <= 200) {
			$(".air-general").attr('style', 'background-color:#CC0033;color:white;');
		} else if (data.result.aqi.aqi <= 300) {
			$(".air-general").attr('style', 'background-color:#660035;color:white;');
		} else {
			$(".air-general").attr('style', 'background-color:#660035;color:white;');
		}


		roseTrace('wind-status',data.result.status.windBearing);
		roseTrace('wind-hour',data.result.hour.windBearing);
		roseTrace('wind-day0',data.result.day0.windBearing);
		roseTrace('wind-day1',data.result.day1.windBearing);
		roseTrace('wind-day2',data.result.day2.windBearing);
		roseTrace('wind-day3',data.result.day3.windBearing);

		var chart1;
		var chart2;

		if($('#azimuth').html() != undefined){
			chart1 = new Highcharts.Chart({
				chart: {
					renderTo: 'azimuth',
					type: 'gauge',
					backgroundColor: 'transparent',
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
					background: null
				},
				exporting : {
					enabled: false
				},
				plotOptions: {
					series: {
						dataLabels: {
							enabled: false
						}
					},
          gauge: {
            dial: {
              backgroundColor: 'red',
              borderColor: 'red',
            },
            pivot: {
              backgroundColor: 'silver'
            }
          }
				},
				yAxis: {
					min: 0,
					max: 360,
					tickWidth: 2,
					tickLength: 10,
					tickInterval: 90,
					lineWidth: 4,
					labels: {
            distance: -16,
						formatter: function () {
							if (this.value == 360) {
								return '<span style="font-weight:bold;">N</span>';
							} else if (this.value == 90) {
								return '<span style="font-weight:bold;">E</span>';
							} else if (this.value == 180) {
								return '<span style="font-weight:bold;">S</span>';
							} else if (this.value == 270) {
								return '<span style="font-weight:bold;">W</span>';
							}
						}
					},
					title: {
						text: null
					}},
					series: [{
						name: '',
						data: [data.result.helio.h_angle]
					}]
				});
			}
			if($('#sunAlt').html() != undefined){

				chart2 = new Highcharts.Chart({
					chart: {
						renderTo: 'sunAlt',
						type: 'gauge',
						backgroundColor: 'transparent',
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
						startAngle: -180,
						endAngle: 0,
						background: null
					},
					exporting : {
						enabled: false
					},
					plotOptions: {
						series: {
							dataLabels: {
								enabled: false
							}
						},
            gauge: {
              dial: {
                backgroundColor: 'red',
                borderColor: 'red',
              },
              pivot: {
                backgroundColor: 'silver'
              }
            }
					},
					yAxis: {
						min: -90,
						max: 90,
						tickWidth: 2,
						tickLength: 10,
						tickInterval: 90,
						lineWidth: 4,
						labels: {
              distance: -16,
							formatter: function () {
								if (this.value == 0) {
									return '<span style="font-weight:bold;">0</span>';
								} else if (this.value == 45) {
									return '<span style="font-weight:bold;"></span>';
								} else if (this.value == 90) {
									return '<span style="font-weight:bold;">90</span>';
								} else if (this.value == -45) {
									return '<span style="font-weight:bold;"></span>';
								} else if (this.value == -90) {
									return '<span style="font-weight:bold;">-90</span>';
								}
							}
						},
						title: {
							text: null
						}},
						series: [{
							name: '',
							data: [data.result.helio.elev_angle]
						}]
					});
				}

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
			var displayDate = ("0" + date.getDate()).slice(-2) + '/' + ("0" + (date.getMonth() + 1)).slice(-2) + ' ' + ("0" + date.getHours()).slice(-2) + ':' + ("0" + date.getMinutes()).slice(-2);
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
	$color = $('.backgroundforJeedom').css("background-color");
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
