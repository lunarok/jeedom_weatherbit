
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
$('#btCurrent').on('click', function () {
  createCmd('current');
});

$('#btAqi').on('click', function () {
  createCmd('aqi');
});

$('#btEnergy').on('click', function () {
  createCmd('energy');
});

$('#btHourly').on('click', function () {
  createCmd('hourly');
});

$('#btDaily').on('click', function () {
  createCmd('daily');
});

$('#btAg').on('click', function () {
  createCmd('ag');
});

function createCmd(type) {
 $.ajax({// fonction permettant de faire de l'ajax
   type: "POST", // methode de transmission des données au fichier php
   url: "plugins/weatherbit/core/ajax/weatherbit.ajax.php", // url du fichier php
   data: {
     action: "createcmd",
     id: $('.eqLogicAttr[data-l1key=id]').value(),
     type: type,
   },
   dataType: 'json',
   error: function(request, status, error) {
     handleAjaxError(request, status, error);
   },
   success: function(data) { // si l'appel a bien fonctionné

     if (data.state != 'ok') {
       $('#div_alert').showAlert({message: data.result, level: 'danger'});
       return;
     }
     $('#div_alert').showAlert({message: 'Recherche ok', level: 'success'});
     window.location.reload();
   }
 });
}

$("#butCol").click(function(){
  $("#hidCol").toggle("slow");
  document.getElementById("listCol").classList.toggle('col-lg-12');
  document.getElementById("listCol").classList.toggle('col-lg-10');
});

$(".li_eqLogic").on('click', function (event) {
  if (event.ctrlKey) {
    var type = $('body').attr('data-page')
    var url = '/index.php?v=d&m='+type+'&p='+type+'&id='+$(this).attr('data-eqlogic_id')
    window.open(url).focus()
  } else {
    jeedom.eqLogic.cache.getCmd = Array();
    if ($('.eqLogicThumbnailDisplay').html() != undefined) {
      $('.eqLogicThumbnailDisplay').hide();
    }
    $('.eqLogic').hide();
    if ('function' == typeof (prePrintEqLogic)) {
      prePrintEqLogic($(this).attr('data-eqLogic_id'));
    }
    if (isset($(this).attr('data-eqLogic_type')) && isset($('.' + $(this).attr('data-eqLogic_type')))) {
      $('.' + $(this).attr('data-eqLogic_type')).show();
    } else {
      $('.eqLogic').show();
    }
    $(this).addClass('active');
    $('.nav-tabs a:not(.eqLogicAction)').first().click()
    $.showLoading()
    jeedom.eqLogic.print({
      type: isset($(this).attr('data-eqLogic_type')) ? $(this).attr('data-eqLogic_type') : eqType,
      id: $(this).attr('data-eqLogic_id'),
      status : 1,
      error: function (error) {
        $.hideLoading();
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      },
      success: function (data) {
        $('body .eqLogicAttr').value('');
        if(isset(data) && isset(data.timeout) && data.timeout == 0){
          data.timeout = '';
        }
        $('body').setValues(data, '.eqLogicAttr');
        if ('function' == typeof (printEqLogic)) {
          printEqLogic(data);
        }
        if ('function' == typeof (addCmdToTable)) {
          $('.cmd').remove();
          for (var i in data.cmd) {
            addCmdToTable(data.cmd[i]);
          }
        }
        $('body').delegate('.cmd .cmdAttr[data-l1key=type]', 'change', function () {
          jeedom.cmd.changeType($(this).closest('.cmd'));
        });

        $('body').delegate('.cmd .cmdAttr[data-l1key=subType]', 'change', function () {
          jeedom.cmd.changeSubType($(this).closest('.cmd'));
        });
        addOrUpdateUrl('id',data.id);
        $.hideLoading();
        modifyWithoutSave = false;
        setTimeout(function(){
          modifyWithoutSave = false;
        },1000)
      }
    });
  }
  return false;
});


function addCmdToTable(_cmd) {
    if (!isset(_cmd)) {
        var _cmd = {configuration: {}};
    }
    if (!isset(_cmd.configuration)) {
        _cmd.configuration = {};
    }

    if (init(_cmd.configuration.step) == 'current') {
        var disabled = (init(_cmd.configuration.virtualAction) == '1') ? 'disabled' : '';
        var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
        tr += '<td>';
        tr += '<span class="cmdAttr" data-l1key="id"></span>';
        tr += '</td>';
        tr += '<td>';
        tr += '<span class="cmdAttr" data-l1key="name"></span></td>';
        tr += '<td>';
        if (init(_cmd.subType) == "numeric") {
            tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> ';
        }
        tr += '</td>';
        tr += '<td>';
        if (is_numeric(_cmd.id)) {
            tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> ';
            tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fas fa-rss"></i> {{Tester}}</a>';
        }
        tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
        tr += '</td>';
        tr += '</tr>';
        $('#table_cmd tbody').append(tr);
        $('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    }

    if (init(_cmd.configuration.step) == 'daily0') {
        var disabled = (init(_cmd.configuration.virtualAction) == '1') ? 'disabled' : '';
        var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
        tr += '<td>';
        tr += '<span class="cmdAttr" data-l1key="id"></span>';
        tr += '</td>';
        tr += '<td>';
        tr += '<span class="cmdAttr" data-l1key="name"></span></td>';
        tr += '<td>';
        if (init(_cmd.subType) == "numeric") {
            tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> ';
        }
        tr += '</td>';
        tr += '<td>';
        if (is_numeric(_cmd.id)) {
            tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> ';
            tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fas fa-rss"></i> {{Tester}}</a>';
        }
        tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
        tr += '</td>';
        tr += '</tr>';
        $('#0h_cmd tbody').append(tr);
        $('#0h_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    }

    if (init(_cmd.configuration.step) == 'daily1' || init(_cmd.configuration.step) == 'forecast24') {
        var disabled = (init(_cmd.configuration.virtualAction) == '1') ? 'disabled' : '';
        var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
        tr += '<td>';
        tr += '<span class="cmdAttr" data-l1key="id"></span>';
        tr += '</td>';
        tr += '<td>';
        tr += '<span class="cmdAttr" data-l1key="name"></span></td>';
        tr += '<td>';
        if (init(_cmd.subType) == "numeric") {
            tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> ';
        }
        tr += '</td>';
        tr += '<td>';
        if (is_numeric(_cmd.id)) {
            tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> ';
            tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fas fa-rss"></i> {{Tester}}</a>';
        }
        tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
        tr += '</td>';
        tr += '</tr>';
        $('#24h_cmd tbody').append(tr);
        $('#24h_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    }

    if (init(_cmd.configuration.step) == 'daily2') {
        var disabled = (init(_cmd.configuration.virtualAction) == '1') ? 'disabled' : '';
        var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
        tr += '<td>';
        tr += '<span class="cmdAttr" data-l1key="id"></span>';
        tr += '</td>';
        tr += '<td>';
        tr += '<span class="cmdAttr" data-l1key="name"></span></td>';
        tr += '<td>';
        if (init(_cmd.subType) == "numeric") {
            tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> ';
        }
        tr += '</td>';
        tr += '<td>';
        if (is_numeric(_cmd.id)) {
            tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> ';
            tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fas fa-rss"></i> {{Tester}}</a>';
        }
        tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
        tr += '</td>';
        tr += '</tr>';
        $('#48h_cmd tbody').append(tr);
        $('#48h_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    }

    if (init(_cmd.configuration.step) == 'daily3') {
        var disabled = (init(_cmd.configuration.virtualAction) == '1') ? 'disabled' : '';
        var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
        tr += '<td>';
        tr += '<span class="cmdAttr" data-l1key="id"></span>';
        tr += '</td>';
        tr += '<td>';
        tr += '<span class="cmdAttr" data-l1key="name"></span></td>';
        tr += '<td>';
        if (init(_cmd.subType) == "numeric") {
            tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> ';
        }
        tr += '</td>';
        tr += '<td>';
        if (is_numeric(_cmd.id)) {
            tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> ';
            tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fas fa-rss"></i> {{Tester}}</a>';
        }
        tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
        tr += '</td>';
        tr += '</tr>';
        $('#72h_cmd tbody').append(tr);
        $('#72h_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    }

    if (init(_cmd.configuration.step) == 'hourly1' || init(_cmd.configuration.step) == 'forecast1') {
        var disabled = (init(_cmd.configuration.virtualAction) == '1') ? 'disabled' : '';
        var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
        tr += '<td>';
        tr += '<span class="cmdAttr" data-l1key="id"></span>';
        tr += '</td>';
        tr += '<td>';
        tr += '<span class="cmdAttr" data-l1key="name"></span></td>';
        tr += '<td>';
        if (init(_cmd.subType) == "numeric") {
            tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> ';
        }
        tr += '</td>';
        tr += '<td>';
        if (is_numeric(_cmd.id)) {
            tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> ';
            tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fas fa-rss"></i> {{Tester}}</a>';
        }
        tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
        tr += '</td>';
        tr += '</tr>';
        $('#h1_cmd tbody').append(tr);
        $('#h1_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    }

    if (init(_cmd.configuration.step) == 'hourly2' || init(_cmd.configuration.step) == 'forecast2') {
        var disabled = (init(_cmd.configuration.virtualAction) == '1') ? 'disabled' : '';
        var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
        tr += '<td>';
        tr += '<span class="cmdAttr" data-l1key="id"></span>';
        tr += '</td>';
        tr += '<td>';
        tr += '<span class="cmdAttr" data-l1key="name"></span></td>';
        tr += '<td>';
        if (init(_cmd.subType) == "numeric") {
            tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> ';
        }
        tr += '</td>';
        tr += '<td>';
        if (is_numeric(_cmd.id)) {
            tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> ';
            tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fas fa-rss"></i> {{Tester}}</a>';
        }
        tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
        tr += '</td>';
        tr += '</tr>';
        $('#h2_cmd tbody').append(tr);
        $('#h2_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    }

    if (init(_cmd.configuration.step) == 'hourly3') {
        var disabled = (init(_cmd.configuration.virtualAction) == '1') ? 'disabled' : '';
        var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
        tr += '<td>';
        tr += '<span class="cmdAttr" data-l1key="id"></span>';
        tr += '</td>';
        tr += '<td>';
        tr += '<span class="cmdAttr" data-l1key="name"></span></td>';
        tr += '<td>';
        if (init(_cmd.subType) == "numeric") {
            tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> ';
        }
        tr += '</td>';
        tr += '<td>';
        if (is_numeric(_cmd.id)) {
            tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> ';
            tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fas fa-rss"></i> {{Tester}}</a>';
        }
        tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
        tr += '</td>';
        tr += '</tr>';
        $('#h3_cmd tbody').append(tr);
        $('#h3_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    }

    if (init(_cmd.configuration.step) == 'hourly4') {
        var disabled = (init(_cmd.configuration.virtualAction) == '1') ? 'disabled' : '';
        var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
        tr += '<td>';
        tr += '<span class="cmdAttr" data-l1key="id"></span>';
        tr += '</td>';
        tr += '<td>';
        tr += '<span class="cmdAttr" data-l1key="name"></span></td>';
        tr += '<td>';
        if (init(_cmd.subType) == "numeric") {
            tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> ';
        }
        tr += '</td>';
        tr += '<td>';
        if (is_numeric(_cmd.id)) {
            tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> ';
            tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fas fa-rss"></i> {{Tester}}</a>';
        }
        tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
        tr += '</td>';
        tr += '</tr>';
        $('#h4_cmd tbody').append(tr);
        $('#h4_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    }

    if (init(_cmd.configuration.step) == 'hourly5') {
        var disabled = (init(_cmd.configuration.virtualAction) == '1') ? 'disabled' : '';
        var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
        tr += '<td>';
        tr += '<span class="cmdAttr" data-l1key="id"></span>';
        tr += '</td>';
        tr += '<td>';
        tr += '<span class="cmdAttr" data-l1key="name"></span></td>';
        tr += '<td>';
        if (init(_cmd.subType) == "numeric") {
            tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> ';
        }
        tr += '</td>';
        tr += '<td>';
        if (is_numeric(_cmd.id)) {
            tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> ';
            tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fas fa-rss"></i> {{Tester}}</a>';
        }
        tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
        tr += '</td>';
        tr += '</tr>';
        $('#h5_cmd tbody').append(tr);
        $('#h5_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    }

    if (init(_cmd.configuration.step) == 'hourly6') {
        var disabled = (init(_cmd.configuration.virtualAction) == '1') ? 'disabled' : '';
        var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
        tr += '<td>';
        tr += '<span class="cmdAttr" data-l1key="id"></span>';
        tr += '</td>';
        tr += '<td>';
        tr += '<span class="cmdAttr" data-l1key="name"></span></td>';
        tr += '<td>';
        if (init(_cmd.subType) == "numeric") {
            tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> ';
        }
        tr += '</td>';
        tr += '<td>';
        if (is_numeric(_cmd.id)) {
            tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> ';
            tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fas fa-rss"></i> {{Tester}}</a>';
        }
        tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
        tr += '</td>';
        tr += '</tr>';
        $('#h6_cmd tbody').append(tr);
        $('#h6_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    }
}
