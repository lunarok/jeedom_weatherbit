<?php

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

require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class weatherbit extends eqLogic {
    public static $_widgetPossibility = array('custom' => true);

    public static function cronHourly() {
        $eqLogics = self::byType('weatherbit', true);
        foreach ($eqLogics as $weatherbit) {
            if (null !== ($weatherbit->getConfiguration('geoloc', ''))) {
                $weatherbit->getInformations();
            } else {
                log::add('weatherbit', 'error', 'geoloc non saisie');
            }
        }
    }

    public static function start() {
        foreach (self::byType('weatherbit', true) as $weatherbit) {
            if (null !== ($weatherbit->getConfiguration('geoloc', ''))) {
                $weatherbit->getInformations('all');
            } else {
                log::add('weatherbit', 'error', 'geoloc non saisie');
            }
        }
    }

    public function loadCmdFromConf($_type, $_step) {
  		/*create commands based on template*/
  		if (!is_file(dirname(__FILE__) . '/../config/devices/' . $_type . '.json')) {
  			return;
  		}
  		$content = file_get_contents(dirname(__FILE__) . '/../config/devices/' . $_type . '.json');
  		if (!is_json($content)) {
  			return;
  		}
  		$device = json_decode($content, true);
  		if (!is_array($device) || !isset($device['commands'])) {
  			return true;
  		}
  		foreach ($device['commands'] as $command) {
  			$cmd = null;
        if ($_step != 'current') {
          $command['name'] = $command['name'] . ' ' . $_step;
        }
        $command['logicalId'] = $_step . $command['configuration']['apiId'];
        $command['configuration']['category'] = $_type;
        $command['configuration']['step'] = $_step;
        if (strpos($_step, 'current') !== false) {
          $list = array('wind_gust_spd','app_max_temp','app_min_temp','pop','snow_depth','dni','moon_phase','moon_phase_lunation','moonrise_ts','moonset_ts');
          if (in_array($command['configuration']['apiId'], $list)) {
            continue;
          }
        }
        if (strpos($_step, 'hourly') !== false) {
          $list = array('max_temp','min_temp','app_max_temp','app_min_temp','sunrise','sunset','elev_angle','h_angle','moon_phase','moon_phase_lunation','moonrise_ts','moonset_ts');
          if (in_array($command['configuration']['apiId'], $list)) {
            continue;
          }
        }
        if (strpos($_step, 'daily') !== false) {
          $list = array('max_temp','min_temp','app_temp','solar_rad','dhi','ghi','dni','elev_angle','h_angle');
          if (in_array($command['configuration']['apiId'], $list)) {
            continue;
          }
        }
        if (strpos($_step, 'forecast') !== false) {
          $list = array('pollen_level_tree','pollen_level_grass','pollen_level_weed','predominant_pollen_type','dhi');
          if (in_array($command['configuration']['apiId'], $list)) {
            continue;
          }
        }
        //log::add('weatherbit', 'debug', 'command : ' . print_r($command, true));
  			foreach ($this->getCmd() as $liste_cmd) {
  				if ((isset($command['logicalId']) && $liste_cmd->getLogicalId() == $command['logicalId'])
  				|| (isset($command['name']) && $liste_cmd->getName() == $command['name'])) {
  					$cmd = $liste_cmd;
  					break;
  				}
  			}
  			if ($cmd == null || !is_object($cmd)) {
  				$cmd = new weatherbitCmd();
  				$cmd->setEqLogic_id($this->getId());
  				utils::a2o($cmd, $command);
  				$cmd->save();
  			}
  		}
  	}

    public function preUpdate() {
        if ($this->getConfiguration('geoloc') == '') {
            throw new Exception(__('La géolocalisation ne peut etre vide',__FILE__));
        }
        if ($this->getConfiguration('apikey') == '') {
            throw new Exception(__('La clef API ne peut etre vide',__FILE__));
        }
    }

    public function postAjax() {
        if (null !== ($this->getConfiguration('geoloc', '')) && $this->getConfiguration('geoloc', '') != 'none') {
          $cmds = $this->getCmd();
          if (count($cmds) > 0) {
            //
            $this->loadCmdFromConf('energy', 'daily0');
            $this->loadCmdFromConf('energy', 'daily1');
            $this->loadCmdFromConf('energy', 'daily2');
            $this->loadCmdFromConf('energy', 'daily3');
          } else {
            $this->loadCmdFromConf('weather', 'current');
            $this->loadCmdFromConf('weather', 'daily0');
            $this->loadCmdFromConf('weather', 'daily1');
            $this->loadCmdFromConf('weather', 'daily2');
            $this->loadCmdFromConf('weather', 'daily3');
            $this->loadCmdFromConf('weather', 'hourly1');
            $this->loadCmdFromConf('weather', 'hourly2');
            $this->loadCmdFromConf('weather', 'hourly3');
            $this->loadCmdFromConf('weather', 'hourly4');
            $this->loadCmdFromConf('weather', 'hourly5');
            $this->loadCmdFromConf('weather', 'hourly6');
            $this->loadCmdFromConf('alerts', 'current');
            $this->loadCmdFromConf('airquality', 'current');
            $this->loadCmdFromConf('airquality', 'forecast1');
            $this->loadCmdFromConf('airquality', 'forecast2');
            $this->loadCmdFromConf('airquality', 'forecast24');
            $this->loadCmdFromConf('energy', 'daily0');
            $this->loadCmdFromConf('energy', 'daily1');
            $this->loadCmdFromConf('energy', 'daily2');
            $this->loadCmdFromConf('energy', 'daily3');
            $this->loadCmdFromConf('ag', 'daily0');
            $this->loadCmdFromConf('ag', 'daily1');
            $this->loadCmdFromConf('ag', 'daily2');
            $this->loadCmdFromConf('ag', 'daily3');
          }
          $this->getInformations();
        } else {
          log::add('weatherbit', 'error', 'geoloc non saisie');
        }
    }


    public function getInformations() {
        if ($this->getConfiguration('geoloc', 'none') == 'none') {
            return;
        }
        if ($this->getConfiguration('geoloc') == 'jeedom') {
            $geolocval = config::byKey('info::latitude') . ',' . config::byKey('info::longitude');
        } else {
            $geolocval = geotravCmd::byEqLogicIdAndLogicalId($this->getConfiguration('geoloc'),'location:coordinate')->execCmd();
        }
        $apikey = $this->getConfiguration('apikey', '');
        $lang = explode('_',config::byKey('language'));
        $geo = explode(',',$geolocval);
        $params = 'lat=' . $geo[0] . '&lon=' . $geo[1] . '&lang=' . $lang[0] . '&key=' . $apikey;
        $cmd_current = $cmd_alerts = $cmd_aqi = $cmd_daily = $cmd_hourly = $cmd_foraqi = $cmd_energy = array();
        foreach ($this->getCmd() as $liste_cmd) {
          switch ($liste_cmd->getConfiguration('category')) {
            case 'alerts':
              $cmd_alerts[] = $liste_cmd->getConfiguration('apiId');
              break;
            case 'weather':
              $cmd_weather[$liste_cmd->getConfiguration('step')][] = $liste_cmd->getConfiguration('apiId');
              break;
            case 'airquality':
              $cmd_aqi[$liste_cmd->getConfiguration('step')][] = $liste_cmd->getConfiguration('apiId');
              break;
            case 'energy':
              $cmd_energy[$liste_cmd->getConfiguration('step')][] = $liste_cmd->getConfiguration('apiId');
              break;
            case 'ag':
              $cmd_ag[$liste_cmd->getConfiguration('step')][] = $liste_cmd->getConfiguration('apiId');
              break;
          }
        }
        $this->getCurrent($params, $cmd_weather);
        $this->getAlerts($params, $cmd_alerts);
        $this->getAirquality($params, $cmd_aqi);
        $this->getForecastDaily($params, $cmd_weather);
        $this->getForecastHourly($params, $cmd_weather);
        $this->getForecastAirquality($params, $cmd_aqi);
        $this->getForecastEnergy($params, $cmd_energy);
        $this->getForecastAgweather($params, $cmd_ag);
        $this->getUsage();
        $this->refreshWidget();
    }

    public function setWeather($_json, $_category, $_cmdlist) {
      $list = array("weather::icon", "weather::code", "weather::description");
      foreach ($_cmdlist as $value) {
        if (in_array($value, $list)) {
          $value2 = str_replace('weather::','',$value);
          $this->checkAndUpdateCmd($_category . $value, $_json['weather'][$value2]);
        } else {
          if ($value == 'sunrise' || $value == 'sunset') {
            if ($_category == 'current') {
              $sun = strtotime($_json[$value] . 'UTC');
              $this->checkAndUpdateCmd($_category . $value, date(Hi,$sun));
            } else {
              $this->checkAndUpdateCmd($_category . $value, date(Hi,$_json[$value . '_ts']));
            }
          } else if ($value == 'moonrise_ts' || $value == 'moonset_ts') {
            $this->checkAndUpdateCmd($_category . $value, date(Hi,$_json[$value]));
          }else {
            $this->checkAndUpdateCmd($_category . $value, $_json[$value]);
          }
        }
      }
    }

    public function getCurrent($_params, $_cmdlist) {
      $parsed_json = $this->callWeatherbit('current', $_params);
      $this->setWeather($parsed_json['data'][0], 'current', $_cmdlist['current']);
    }

    public function getAlerts($_params, $_cmdlist) {
      $parsed_json = $this->callWeatherbit('alerts', $_params);
      $alert = array();
      foreach ($parsed_json['alerts'] as $value) {
        $alert[] = $value['title'] . ' - ' . $value['description'];
      }
      $this->checkAndUpdateCmd('alerts', implode(', ', $alert));
    }

    public function getAirquality($_params, $_cmdlist) {
      $parsed_json = $this->callWeatherbit('current/airquality', $_params);
      foreach ($_cmdlist['current'] as $value) {
        $this->checkAndUpdateCmd('current'.$value, $parsed_json['data'][0][$value]);
      }
    }

    public function getForecastDaily($_params, $_cmdlist) {
      $parsed_json = $this->callWeatherbit('forecast/daily', $_params);
      $this->setWeather($parsed_json['data'][0], 'daily0', $_cmdlist['daily0']);
      $this->setWeather($parsed_json['data'][1], 'daily1', $_cmdlist['daily1']);
      $this->setWeather($parsed_json['data'][2], 'daily2', $_cmdlist['daily2']);
      $this->setWeather($parsed_json['data'][3], 'daily3', $_cmdlist['daily3']);
    }

    public function getForecastHourly($_params, $_cmdlist) {
      $parsed_json = $this->callWeatherbit('forecast/hourly', $_params);
      $this->setWeather($parsed_json['data'][0], 'hourly1', $_cmdlist['hourly1']);
      $this->setWeather($parsed_json['data'][1], 'hourly6', $_cmdlist['hourly2']);
      $this->setWeather($parsed_json['data'][2], 'hourly2', $_cmdlist['hourly3']);
      $this->setWeather($parsed_json['data'][3], 'hourly3', $_cmdlist['hourly4']);
      $this->setWeather($parsed_json['data'][4], 'hourly4', $_cmdlist['hourly5']);
      $this->setWeather($parsed_json['data'][5], 'hourly5', $_cmdlist['hourly6']);
    }

    public function getForecastAirquality($_params, $_cmdlist) {
      $parsed_json = $this->callWeatherbit('forecast/airquality', $_params);
      foreach ($_cmdlist['forecast1'] as $value) {
        $this->checkAndUpdateCmd('forecast1' . $value, $parsed_json['data'][0][$value]);
      }
      foreach ($_cmdlist['forecast2'] as $value) {
        $this->checkAndUpdateCmd('forecast2' . $value, $parsed_json['data'][1][$value]);
      }
      foreach ($_cmdlist['forecast24'] as $value) {
        $this->checkAndUpdateCmd('forecast24' . $value, $parsed_json['data'][23][$value]);
      }
    }

    public function getForecastEnergy($_params, $_cmdlist) {
      $_params = $_params . '&threshold=' . $this->getConfiguration('treshold', '20');
      $parsed_json = $this->callWeatherbit('forecast/energy', $_params);
      foreach ($_cmdlist['daily0'] as $value) {
        if ($value == 'avg_temp') {
          $this->checkAndUpdateCmd('daily0' . $value, $parsed_json['data'][0]['temp']);
        } else {
          $this->checkAndUpdateCmd('daily0' . $value, $parsed_json['data'][0][$value]);
        }
      }
      foreach ($_cmdlist['daily1'] as $value) {
        if ($value == 'avg_temp') {
          $this->checkAndUpdateCmd('daily1' . $value, $parsed_json['data'][1]['temp']);
        } else {
          $this->checkAndUpdateCmd('daily1' . $value, $parsed_json['data'][1][$value]);
        }
      }
      foreach ($_cmdlist['daily2'] as $value) {
        if ($value == 'avg_temp') {
          $this->checkAndUpdateCmd('daily2' . $value, $parsed_json['data'][2]['temp']);
        } else {
          $this->checkAndUpdateCmd('daily2' . $value, $parsed_json['data'][2][$value]);
        }
      }
      foreach ($_cmdlist['daily3'] as $value) {
        if ($value == 'avg_temp') {
          $this->checkAndUpdateCmd('daily3' . $value, $parsed_json['data'][3]['temp']);
        } else {
          $this->checkAndUpdateCmd('daily3' . $value, $parsed_json['data'][3][$value]);
        }
      }
    }

    public function getForecastAgweather($_params, $_cmdlist) {
      $parsed_json = $this->callWeatherbit('forecast/agweather', $_params);
      foreach ($_cmdlist['daily0'] as $value) {
        $this->checkAndUpdateCmd('daily0' . $value, $parsed_json['data'][0][$value]);
      }
      foreach ($_cmdlist['daily1'] as $value) {
        $this->checkAndUpdateCmd('daily1' . $value, $parsed_json['data'][1][$value]);
      }
      foreach ($_cmdlist['daily2'] as $value) {
        $this->checkAndUpdateCmd('daily2' . $value, $parsed_json['data'][2][$value]);
      }
      foreach ($_cmdlist['daily3'] as $value) {
        $this->checkAndUpdateCmd('daily3' . $value, $parsed_json['data'][3][$value]);
      }
    }

    public function getUsage() {
      $parsed_json = $this->callWeatherbit('subscription/usage', 'key=' . $this->getConfiguration('apikey', ''));
      log::add('weatherbit', 'debug', 'Appel API restants : ' . $parsed_json['calls_remaining']);
    }

    public function callWeatherbit($_uri, $_params) {
      $url = 'https://api.weatherbit.io/v2.0/' . $_uri . '?' . $_params;
      log::add('weatherbit', 'debug', 'url : ' . $url);
      $request_http = new com_http($url);
      $request_http->setNoReportError(true);
      $json_string = $request_http->exec(8);
      if ($json_string == '') {
        return;
      }
      //$json_string = file_get_contents($url);
      if ($json_string === false) {
          log::add('weatherbit', 'debug', 'Problème de chargement API');
          return;
      }
      log::add('weatherbit', 'debug', 'result : ' . $json_string);
      return json_decode($json_string, true);
    }


    public function loadingData() {
        $return = array();
        if ($this->getConfiguration('geoloc') == 'jeedom') {
            $geolocval = config::byKey('info::latitude') . ',' . config::byKey('info::longitude');
        } else {
            $geolocval = geotravCmd::byEqLogicIdAndLogicalId($this->getConfiguration('geoloc'),'location:coordinate')->execCmd();
        }
        $apikey = $this->getConfiguration('apikey', '');
        $lang = explode('_',config::byKey('language'));
        $geo = explode(',',$geolocval);
        $params = 'lat=' . $geo[0] . '&lon=' . $geo[1] . '&lang=' . $lang[0] . '&key=' . $apikey;
        $parsed_json = $this->callWeatherbit('forecast/hourly', $params);

        foreach ($parsed_json['data'] as $value) {
            $return['previsions']['time'][] = $value['ts'] . '000';
            $return['previsions']['temperature'][] = $value['temp'];
            $return['previsions']['precipIntensity'][] = $value['precip'];
            $return['previsions']['windSpeed'][] = $value['wind_spd'];
            $return['previsions']['pressure'][] = $value['pres'];
            $return['previsions']['uvIndex'][] = $value['uv'];
        }

        $cmdaqi = $this->getCmd(null, 'currentaqi');
        $cmdpm10 = $this->getCmd(null, 'currentpm10');
        $cmdpm25 = $this->getCmd(null, 'currentpm25');
        $cmdco = $this->getCmd(null, 'currentco');
        $cmdso2 = $this->getCmd(null, 'currentso2');
        $cmdno2 = $this->getCmd(null, 'currentno2');
        $cmdo3 = $this->getCmd(null, 'currento3');
        $return['aqi'] = array(
          'aqi' => $cmdaqi->execCmd(),
          'no2' => $cmdno2->execCmd(),
          'o3' => $cmdo3->execCmd(),
          'co' => $cmdco->execCmd(),
          'so2' => $cmdso2->execCmd(),
          'pm10' => $cmdpm10->execCmd(),
          'pm25' => $cmdpm25->execCmd(),
        );

        $cmdpredominant_pollen_type = $this->getCmd(null, 'currentpredominant_pollen_type');
        $cmdpollen_level_weed = $this->getCmd(null, 'currentpollen_level_weed');
        $cmdpollen_level_grass = $this->getCmd(null, 'currentpollen_level_grass');
        $cmdpollen_level_tree = $this->getCmd(null, 'currentpollen_level_tree');
        $return['pollen'] = array(
          'predominant_pollen_type' => $cmdpredominant_pollen_type->execCmd(),
          'pollen_level_weed' => $cmdpollen_level_weed->execCmd(),
          'pollen_level_grass' => $cmdpollen_level_grass->execCmd(),
          'pollen_level_tree' => $cmdpollen_level_tree->execCmd(),
        );

        $cmdsunrise = $this->getCmd(null, 'daily0sunrise');
        $cmdsunset = $this->getCmd(null, 'daily0sunset');
        $cmdmoonrise_ts = $this->getCmd(null, 'daily0moonrise_ts');
        $cmdmoonset_ts = $this->getCmd(null, 'daily0moonset_ts');
        $cmdelev_angle = $this->getCmd(null, 'currentelev_angle');
        $cmdh_angle = $this->getCmd(null, 'currenth_angle');
        $return['helio'] = array(
          'sunrise' => substr_replace($cmdsunrise->execCmd(),':',-2,0),
          'sunset' => substr_replace($cmdsunset->execCmd(),':',-2,0),
          'moonrise_ts' => substr_replace($cmdmoonrise_ts->execCmd(),':',-2,0),
          'moonset_ts' => substr_replace($cmdmoonset_ts->execCmd(),':',-2,0),
          'elev_angle' => $cmdelev_angle->execCmd(),
          'h_angle' => $cmdh_angle->execCmd(),
        );

        $replace = $this->getReplace('current');
        $return['status'] = array(
          'summary' => $replace['#description#'],
          'icon' => $replace['#icone#'],
          'temperature' => $replace['#temp#'] . '°C',
          'apparentTemperature' => '(' . $replace['#app_temp#'] . '°C)',
          'humidity' => $replace['#humidity#'] . '%',
          'precipProbability' => $replace['#pop#'] . '%',
          'windSpeed' => $replace['#wind_spd#']*3.6 . 'km/h',
          'windBearing' => $replace['#wind_dir#'],
          'cloudCover' => $replace['#clouds#'] . '%',
          'pressure' => $replace['#pres#'] . 'hPa',
          'ozone' => $replace['#ozone#'] . 'DU',
          'uvIndex' => $replace['#uv#'],
        );

        $replace = $this->getReplace('hourly1');
        $return['hour'] = array(
          'summary' => $replace['#description#'],
          'icon' => $replace['#icone#'],
          'temperature' => $replace['#temp#'] . '°C',
          'apparentTemperature' => '(' . $replace['#app_temp#'] . '°C)',
          'humidity' => $replace['#humidity#'] . '%',
          'precipProbability' => $replace['#pop#'] . '%',
          'windSpeed' => $replace['#wind_spd#']*3.6 . 'km/h',
          'windBearing' => $replace['#wind_dir#'],
          'cloudCover' => $replace['#clouds#'] . '%',
          'pressure' => $replace['#pres#'] . 'hPa',
          'ozone' => $replace['#ozone#'] . 'DU',
          'uvIndex' => $replace['#uv#'],
        );

        $replace = $this->getReplace('daily0');
        $return['day0'] = array(
          'summary' => $replace['#description#'],
          'icon' => $replace['#icone#'],
          'temperature' => $replace['#temp#'] . '°C',
          'apparentTemperature' => '(' . $replace['#app_temp#'] . '°C)',
          'humidity' => $replace['#humidity#'] . '%',
          'precipProbability' => $replace['#pop#'] . '%',
          'windSpeed' => $replace['#wind_spd#']*3.6 . 'km/h',
          'windBearing' => $replace['#wind_dir#'],
          'cloudCover' => $replace['#clouds#'] . '%',
          'pressure' => $replace['#pres#'] . 'hPa',
          'ozone' => $replace['#ozone#'] . 'DU',
          'uvIndex' => $replace['#uv#'],
          'sunriseTime' => $replace['#sunrise#'],
          'sunsetTime' => $replace['#sunset#'],
          'temperatureMin' => $replace['#app_min_temp#'] . '°C',
          'temperatureMax' => $replace['#app_max_temp#'] . '°C',
        );

        $replace = $this->getReplace('daily1');
        $return['day1'] = array(
          'summary' => $replace['#description#'],
          'icon' => $replace['#icone#'],
          'temperature' => $replace['#temp#'] . '°C',
          'apparentTemperature' => '(' . $replace['#app_temp#'] . '°C)',
          'humidity' => $replace['#humidity#'] . '%',
          'precipProbability' => $replace['#pop#'] . '%',
          'windSpeed' => $replace['#wind_spd#']*3.6 . 'km/h',
          'windBearing' => $replace['#wind_dir#'],
          'cloudCover' => $replace['#clouds#'] . '%',
          'pressure' => $replace['#pres#'] . 'hPa',
          'ozone' => $replace['#ozone#'] . 'DU',
          'uvIndex' => $replace['#uv#'],
          'sunriseTime' => $replace['#sunrise#'],
          'sunsetTime' => $replace['#sunset#'],
          'temperatureMin' => $replace['#app_min_temp#'] . '°C',
          'temperatureMax' => $replace['#app_max_temp#'] . '°C',
        );

        $replace = $this->getReplace('daily2');
        $return['day2'] = array(
          'summary' => $replace['#description#'],
          'icon' => $replace['#icone#'],
          'temperature' => $replace['#temp#'] . '°C',
          'apparentTemperature' => '(' . $replace['#app_temp#'] . '°C)',
          'humidity' => $replace['#humidity#'] . '%',
          'precipProbability' => $replace['#pop#'] . '%',
          'windSpeed' => $replace['#wind_spd#']*3.6 . 'km/h',
          'windBearing' => $replace['#wind_dir#'],
          'cloudCover' => $replace['#clouds#'] . '%',
          'pressure' => $replace['#pres#'] . 'hPa',
          'ozone' => $replace['#ozone#'] . 'DU',
          'uvIndex' => $replace['#uv#'],
          'sunriseTime' => $replace['#sunrise#'],
          'sunsetTime' => $replace['#sunset#'],
          'temperatureMin' => $replace['#app_min_temp#'] . '°C',
          'temperatureMax' => $replace['#app_max_temp#'] . '°C',
        );

        $replace = $this->getReplace('daily3');
        $return['day3'] = array(
          'summary' => $replace['#description#'],
          'icon' => $replace['#icone#'],
          'temperature' => $replace['#temp#'] . '°C',
          'apparentTemperature' => '(' . $replace['#app_temp#'] . '°C)',
          'humidity' => $replace['#humidity#'] . '%',
          'precipProbability' => $replace['#pop#'] . '%',
          'windSpeed' => $replace['#wind_spd#']*3.6 . 'km/h',
          'windBearing' => $replace['#wind_dir#'],
          'cloudCover' => $replace['#clouds#'] . '%',
          'pressure' => $replace['#pres#'] . 'hPa',
          'ozone' => $replace['#ozone#'] . 'DU',
          'uvIndex' => $replace['#uv#'],
          'sunriseTime' => $replace['#sunrise#'],
          'sunsetTime' => $replace['#sunset#'],
          'temperatureMin' => $replace['#app_min_temp#'] . '°C',
          'temperatureMax' => $replace['#app_max_temp#'] . '°C',
        );
        log::add('weatherbit', 'debug', 'result : ' . print_r($return,true));
        return $return;
    }

    public function getReplace($_step) {
        $replace = array();
        $replace['#icone#'] = $this->getIcone($_step);
        $cmd = $this->getCmd(null, $_step . 'weather::description');
        $replace['#description#'] = is_object($cmd) ? $cmd->execCmd() : '';
        $cmd = $this->getCmd(null, $_step . 'temp');
        $replace['#temp#'] = is_object($cmd) ? round($cmd->execCmd()) : '';
        $cmd = $this->getCmd(null, $_step . 'app_temp');
        $replace['#app_temp#'] = is_object($cmd) ? round($cmd->execCmd()) : '';
        $cmd = $this->getCmd(null, $_step . 'rh');
        $replace['#humidity#'] = is_object($cmd) ? round($cmd->execCmd()) : '';
        $cmd = $this->getCmd(null, $_step . 'pop');
        $replace['#pop#'] = is_object($cmd) ? round($cmd->execCmd()) : '';
        $cmd = $this->getCmd(null, $_step . 'wind_spd');
        $replace['#wind_spd#'] = is_object($cmd) ? round($cmd->execCmd()) : '';
        $cmd = $this->getCmd(null, $_step . 'wind_dir');
        $replace['#wind_dir#'] = is_object($cmd) ? round($cmd->execCmd()) : '';
        $cmd = $this->getCmd(null, $_step . 'clouds');
        $replace['#clouds#'] = is_object($cmd) ? round($cmd->execCmd()) : '';
        $cmd = $this->getCmd(null, $_step . 'pres');
        $replace['#pres#'] = is_object($cmd) ? round($cmd->execCmd()) : '';
        $cmd = $this->getCmd(null, $_step . 'ozone');
        $replace['#ozone#'] = is_object($cmd) ? round($cmd->execCmd()) : '';
        $cmd = $this->getCmd(null, $_step . 'uv');
        $replace['#uv#'] = is_object($cmd) ? round($cmd->execCmd()) : '';
        $cmd = $this->getCmd(null, $_step . 'sunset');
        $replace['#sunset#'] = is_object($cmd) ? substr_replace($cmd->execCmd(),':',-2,0) : '';
        $cmd = $this->getCmd(null, $_step . 'sunrise');
        $replace['#sunrise#'] = is_object($cmd) ? substr_replace($cmd->execCmd(),':',-2,0) : '';
        $cmd = $this->getCmd(null, $_step . 'app_min_temp');
        $replace['#app_min_temp#'] = is_object($cmd) ? round($cmd->execCmd()) : '';
        $cmd = $this->getCmd(null, $_step . 'app_max_temp');
        $replace['#app_max_temp#'] = is_object($cmd) ? round($cmd->execCmd()) : '';
        return $replace;
    }

    public function getGeoloc($_infos = '') {
        $return = array();
        foreach (eqLogic::byType('geoloc') as $geoloc) {
            foreach (geolocCmd::byEqLogicId($geoloc->getId()) as $geoinfo) {
                if ($geoinfo->getConfiguration('mode') == 'fixe' || $geoinfo->getConfiguration('mode') == 'dynamic') {
                    $return[$geoinfo->getId()] = array(
                        'value' => $geoinfo->getName(),
                    );
                }
            }
        }
        return $return;
    }

    public function toHtml($_version = 'dashboard') {
        $replace = $this->preToHtml($_version);
        if (!is_array($replace)) {
            return $replace;
        }
        $version = jeedom::versionAlias($_version);
        if ($this->getDisplay('hideOn' . $version) == 1) {
            return '';
        }

        $html_forecast = '';

        if ($_version != 'mobile' || $this->getConfiguration('fullMobileDisplay', 0) == 1) {
            $forcast_template = getTemplate('core', $version, 'forecast', 'weatherbit');
            for ($i = 0; $i < 5; $i++) {
                if ($i == 0) {
                  $replace['#day#'] = "Aujourd'hui";
                  $temperature_min = $this->getCmd(null, 'daily0app_min_temp');
                  $replace['#low_temperature#'] = is_object($temperature_min) ? round($temperature_min->execCmd()) : '';

                  $temperature_max = $this->getCmd(null, 'daily0app_max_temp');
                  $replace['#hight_temperature#'] = is_object($temperature_max) ? round($temperature_max->execCmd()) : '';
                  $replace['#tempid#'] = is_object($temperature_max) ? $temperature_max->getId() : '';

                  $replace['#icone#'] = $this->getIcone('daily0');
                } else if ($i == 1) {
                  $replace['#day#'] = '+ 1h';
                  $temperature_min = $this->getCmd(null, 'hourly1temp');
                  $replace['#low_temperature#'] = is_object($temperature_min) ? round($temperature_min->execCmd()) : '';

                  $temperature_max = $this->getCmd(null, 'hourly1app_temp');
                  $replace['#hight_temperature#'] = is_object($temperature_max) ? round($temperature_max->execCmd()) : '';
                  $replace['#tempid#'] = is_object($temperature_max) ? $temperature_max->getId() : '';

                  $replace['#icone#'] = $this->getIcone('hourly1');
                } else {
                  if ($i == 2) {
                    $step = 'daily1';
                  } else if ($i == 3) {
                    $step = 'daily2';
                  } else {
                    $step = 'daily3';
                  }
                  $j = $i - 1;
                  $replace['#day#'] = date_fr(date('l', strtotime('+' . $j . ' days')));
                  $temperature_min = $this->getCmd(null, $step . 'app_min_temp');
                  $replace['#low_temperature#'] = is_object($temperature_min) ? round($temperature_min->execCmd()) : '';

                  $temperature_max = $this->getCmd(null, $step . 'app_max_temp');
                  $replace['#hight_temperature#'] = is_object($temperature_max) ? round($temperature_max->execCmd()) : '';
                  $replace['#tempid#'] = is_object($temperature_max) ? $temperature_max->getId() : '';

                  $icone = $this->getCmd(null, $step . 'weather::code');
                  $replace['#icone#'] = $this->getIcone($step);
                }

                $html_forecast .= template_replace($replace, $forcast_template);
            }
        }

        $replace['#forecast#'] = $html_forecast;
        $replace['#city#'] = $this->getName();

        $temperature = $this->getCmd(null, 'currenttemp');
        $replace['#temperature#'] = is_object($temperature) ? round($temperature->execCmd()) : '';
        $replace['#tempid#'] = is_object($temperature) ? $temperature->getId() : '';

        $humidity = $this->getCmd(null, 'currentrh');
        $replace['#humidity#'] = is_object($humidity) ? $humidity->execCmd() : '';

        $uvindex = $this->getCmd(null, 'currentuv');
        $replace['#uvi#'] = is_object($uvindex) ? round($uvindex->execCmd()) : '';

        $pressure = $this->getCmd(null, 'currentpres');
        $replace['#pressure#'] = is_object($pressure) ? $pressure->execCmd() : '';
        $replace['#pressureid#'] = is_object($pressure) ? $pressure->getId() : '';

        $wind_speed = $this->getCmd(null, 'currentwind_spd');
        $replace['#windspeed#'] = is_object($wind_speed) ? $wind_speed->execCmd() * 3.6 : '';
        $replace['#windid#'] = is_object($wind_speed) ? $wind_speed->getId() : '';

        $sunrise = $this->getCmd(null, 'currentsunrise');
        $replace['#sunrise#'] = is_object($sunrise) ? substr_replace($sunrise->execCmd(),':',-2,0) : '';
        $replace['#sunriseid#'] = is_object($sunrise) ? $sunrise->getId() : '';

        $sunset = $this->getCmd(null, 'currentsunset');
        $replace['#sunset#'] = is_object($sunset) ? substr_replace($sunset->execCmd(),':',-2,0) : '';
        $replace['#sunsetid#'] = is_object($sunset) ? $sunset->getId() : '';

        $wind_direction = $this->getCmd(null, 'currentwind_dir');
        $replace['#wind_direction#'] = is_object($wind_direction) ? $wind_direction->execCmd() : 0;

        $refresh = $this->getCmd(null, 'refresh');
        $replace['#refresh#'] = is_object($refresh) ? $refresh->getId() : '';

        $condition = $this->getCmd(null, 'currentweather::description');
        if (is_object($condition)) {
            $replace['#condition#'] = $condition->execCmd();
            $replace['#conditionid#'] = $condition->getId();
            $replace['#collectDate#'] = $condition->getCollectDate();
        } else {
            $replace['#condition#'] = '';
            $replace['#collectDate#'] = '';
        }

        $replace['#icone#'] = $this->getIcone('current');

        $parameters = $this->getDisplay('parameters');
        if (is_array($parameters)) {
            foreach ($parameters as $key => $value) {
                $replace['#' . $key . '#'] = $value;
            }
        }

        return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'current', 'weatherbit')));
    }

    public function getIcone($_step) {
      $iconeCmd = $this->getCmd(null, $_step . 'weather::code');
      $code = $iconeCmd->execCmd();
      $iconeCmd = $this->getCmd(null, $_step . 'pod');
      $pod = $iconeCmd->execCmd();
      if ($code <= 203) {
        $icone = 'thunderstorm';
      } else if ($code <= 233) {
        $icone = 'sleet-storm';
      } else if ($code <= 303) {
        $icone = 'sleet';
      } else if ($code <= 501) {
        $icone = 'rain';
      } else if ($code <= 504) {
        $icone = 'rain-wind';
      } else if ($code <= 522) {
        $icone = 'hail';
      } else if ($code <= 610) {
        $icone = 'snow';
      } else if ($code <= 612) {
        $icone = 'cloudy-gusts';
      } else if ($code <= 625) {
        $icone = 'snow';
      } else if ($code <= 751) {
        $icone = 'fog';
      } else if ($code <= 800) {
        $icone = 'sunny';
      } else if ($code <= 804) {
        $icone = 'cloudy';
      } else {
        $icone = 'rain';
      }
      if (($pod == 'd') || (strpos('daily', $_step) !== false)) {
        $day = 'day';
      } else {
        $day = 'night';
        if ($icone == 'sunny') {
          $icone = 'clear';
        }
      }
      return 'wi-' . $day . '-' . $icone;
    }

}

class weatherbitCmd extends cmd {

    public function execute($_options = null) {
        if ($this->getLogicalId() == 'refresh') {
            $eqLogic = $this;
            $eqLogic->getInformations();
        }
    }

}

?>
