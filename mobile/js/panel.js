/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

function initWeatherbitPanel() {
  displayWeatherbit();
  $(window).on("orientationchange", function (event) {
    setTileSize('.eqLogic');
    $('#div_displayEquipementWeatherbit').packery({gutter : 4});
  });
}

function displayWeatherbit() {
  $.showLoading();
  $.ajax({
    type: 'POST',
    url: 'plugins/weatherbit/core/ajax/weatherbit.ajax.php',
    data: {
      action: 'getWeatherbit',
      version: 'mview'
    },
    dataType: 'json',
    error: function (request, status, error) {
      handleAjaxError(request, status, error);
    },
    success: function (data) {
      if (data.state != 'ok') {
        $('#div_alert').showAlert({message: data.result, level: 'danger'});
        return;
      }

      $('#buttonWeatherbit').empty();
      for (var i in data.result) {
        $('#buttonWeatherbit').append(data.result[i]).trigger('create');
      }

      setTileSize('.eqLogic');
      $('#div_displayEquipementWeatherbit').packery({gutter : 4});
      $.hideLoading();
    }
  });
}


$(function () {

  loadingData(0);

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

  var skycons = new Skycons({'color':'black'});
  skycons.set('icone-status', data.result.status.icon);
  skycons.set('icone-hour', data.result.hour.icon);
  skycons.set('icone-day0', data.result.day0.icon);
  skycons.set('icone-day1', data.result.day1.icon);
  skycons.play();

  roseTrace('wind-status',data.result.status.windBearing);
  roseTrace('wind-hour',data.result.hour.windBearing);
  roseTrace('wind-day0',data.result.day0.windBearing);
  roseTrace('wind-day1',data.result.day1.windBearing);

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

var t = 0;
for (var i in data.result.previsions.time) {
  //console.log(data.result.previsions.temperature[i]);
  var date = new Date(parseInt(data.result.previsions.time[i]));
  var displayDate = date.getDate() + '/' + (date.getMonth()+1) + ' ' + date.getHours() + ':' + date.getMinutes();
  t++;
  options.series[0].data.push(parseFloat(data.result.previsions.temperature[i],2));
  options.series[1].data.push(parseFloat(data.result.previsions.precipIntensity[i],2));
  options.series[2].data.push(parseInt(data.result.previsions.pressure[i]));
  options.series[3].data.push(parseInt(data.result.previsions.uvIndex[i]));
  options.xAxis.categories.push(displayDate);
  if (t>=8){
    break;
  }

};

var chart = new Highcharts.Chart(options);

}
});
}

function roseTrace(id,value){
  new Highcharts.Chart({
    chart: {
      renderTo: id,
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
