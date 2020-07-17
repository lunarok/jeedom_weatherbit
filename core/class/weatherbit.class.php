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

    //public static $_widgetPossibility = array('custom' => true);

    public static function cronHourly() {
        $eqLogics = self::byType('weatherbit', true);
        foreach ($eqLogics as $weatherbit) {
            if (null !== ($weatherbit->getConfiguration('geoloc', ''))) {
                $weatherbit->getInformations('hourly');
                //$weatherbit->getInformations('all');
                if (date('G')  == 3) {
                    $weatherbit->getInformations('daily');
                }
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
          $command['name'] = $command['name'] . $_step;
        }
        $command['logicalId'] = $_step . $command['configuration']['apiId'];
        $command['configuration']['category'] = $_type;
        $command['configuration']['step'] = $_step;
        if (strpos($_step, 'current') !== false) {
          $list = array('wind_gust_spd','app_max_temp','app_min_temp','pop','snow_depth','dni','moon_phase','moon_phase_lunation');
          if (in_array($command['configuration']['apiId'], $list)) {
            continue;
          }
        }
        if (strpos($_step, 'hourly') !== false) {
          $list = array('max_temp','min_temp','app_max_temp','app_min_temp','sunrise','sunset','elev_angle','h_angle','moon_phase','moon_phase_lunation');
          if (in_array($command['configuration']['apiId'], $list)) {
            continue;
          }
        }
        if (strpos($_step, 'daily') !== false) {
          $list = array('max_temp','min_temp','app_temp','solar_rad','dhi','ghi','dni','sunrise','sunset','elev_angle','h_angle');
          if (in_array($command['configuration']['apiId'], $list)) {
            continue;
          }
        }
        log::add('weatherbit', 'debug', 'command : ' . print_r($command, true));
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

    public function postUpdate() {
        if (null !== ($this->getConfiguration('geoloc', '')) && $this->getConfiguration('geoloc', '') != 'none') {
          $this->loadCmdFromConf('weather', 'current');
          $this->loadCmdFromConf('weather', 'daily1');
          $this->loadCmdFromConf('weather', 'daily2');
          $this->loadCmdFromConf('weather', 'daily3');
          $this->loadCmdFromConf('weather', 'hourly1');
          $this->loadCmdFromConf('weather', 'hourly2');
          $this->loadCmdFromConf('weather', 'hourly3');
          $this->loadCmdFromConf('weather', 'hourly4');
          $this->loadCmdFromConf('weather', 'hourly5');
          $this->loadCmdFromConf('weather', 'hourly6');
          $this->loadCmdFromConf('weather', 'hourly6');
          $this->loadCmdFromConf('weather', 'hourly6');
          $this->loadCmdFromConf('alerts', 'current');
          $this->loadCmdFromConf('airquality', 'current');
          $this->loadCmdFromConf('airquality', 'forecast1');
          $this->loadCmdFromConf('airquality', 'forecast2');
          $this->loadCmdFromConf('airquality', 'forecast24');
          weatherbit::getInformations();
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
        $params = 'lat=' . $_lat . '&lon=' . $_lon . '&lang=' . $_lan . '&key=' . $_key;
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
          }
        }
        $this->getCurrent($params, $cmd_weather);
        $this->getAlerts($params, $cmd_alerts);
        $this->getAirquality($params, $cmd_aqi);
        $this->getForecastDaily($params, $cmd_weather);
        $this->getForecastHourly($param, $cmd_weather);
        $this->getForecastAirquality($params, $cmd_aqi);
        $this->getUsage();
        //$this->refreshWidget();
    }

    public function setWeather($_json, $_category, $_cmdlist) {
      $list = array("icon", "code", "description");
      foreach ($_cmdlist as $value) {
        if (in_array($value, $list)) {
          $this->checkAndUpdateCmd($_category . $value, $_json['weather'][$value]);
        } else {
          $this->checkAndUpdateCmd($_category . $value, $_json[$value]);
        }
      }
    }

    public function getCurrent($_params, $_cmdlist) {
      $parsed_json = $this->callWeatherbit('current', $_params);
      $this->setWeather($parsed_json['data'], 'current', $_cmdlist['current']);
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
        $this->checkAndUpdateCmd($value, $parsed_json['data'][$value]);
      }
    }

    public function getForecastDaily($_params, $_cmdlist) {
      $parsed_json = $this->callWeatherbit('forecast/daily', $_params);
      $this->setWeather($parsed_json['data'][0], 'daily1', $_cmdlist['daily1']);
      $this->setWeather($parsed_json['data'][1], 'daily2', $_cmdlist['daily2']);
      $this->setWeather($parsed_json['data'][2], 'daily3', $_cmdlist['daily3']);
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
        $this->checkAndUpdateCmd('forecast1' . $value, $parsed_json[0][$value]);
        $this->checkAndUpdateCmd('forecast2' . $value, $parsed_json[1][$value]);
        $this->checkAndUpdateCmd('forecast24' . $value, $parsed_json[23][$value]);
      }
    }

    public function getForecastEnergy($_params, $_cmdlist) {
      $parsed_json = $this->callWeatherbit('forecast/energy', $_params);
    }

    public function getUsage() {
      $parsed_json = $this->callWeatherbit('subscription/usage', 'key=' . $this->getConfiguration('apikey', ''));
      log::add('weatherbit', 'debug', 'Appel API restants : ' . $parsed_json['calls_remaining']);
    }

    public function callWeatherbit($_uri, $_params) {
      $url = 'https://api.weatherbit.io/v2.0/' . $_uri . '?' . $_params;
      log::add('weatherbit', 'debug', $url);
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
      return json_decode($json_string, true);
    }


    public function loadingData($eqlogic) {
        $return = array();
        $weatherbit = weatherbit::byId($eqlogic);
        if ($weatherbit->getConfiguration('geoloc') == 'jeedom') {
            $geolocval = config::byKey('info::latitude') . ',' . config::byKey('info::longitude');
        } else {
            $geolocval = geotravCmd::byEqLogicIdAndLogicalId($weatherbit->getConfiguration('geoloc'),'location:coordinate')->execCmd();
        }
        $apikey = $weatherbit->getConfiguration('apikey', '');
        $lang = explode('_',config::byKey('language'));
        $url = 'https://api.weatherbit.net/forecast/' . $apikey .'/' . trim($geolocval) . '?units=ca&lang=' . $lang[0] . '&solar=1';
        log::add('weatherbit', 'debug', $url);
        $request_http = new com_http($url);
        $request_http->setNoReportError(true);
        $json_string = $request_http->exec(8);
        if ($json_string == '') {
          return;
        }
        //$json_string = file_get_contents($url);
        $parsed_json = json_decode($json_string, true);
        //log::add('weatherbit', 'debug', print_r($json_string, true));
        //log::add('weatherbit', 'debug', print_r($parsed_json, true));
        //log::add('weatherbit', 'debug', print_r($parsed_json['currently'], true));

        foreach ($parsed_json['hourly']['data'] as $value) {
            $return['previsions']['time'][] = $value['time'] . '000';
            $return['previsions']['temperature'][] = $value['temperature'];
            $return['previsions']['precipIntensity'][] = $value['precipIntensity'];
            $return['previsions']['windSpeed'][] = $value['windSpeed'];
            $return['previsions']['pressure'][] = $value['pressure'];
            $return['previsions']['uvIndex'][] = $value['uvIndex'];
        }

        $return['status'] = array(
            'summary' => $parsed_json['currently']['summary'],
            'icon' => $parsed_json['currently']['icon'],
            'temperature' => $parsed_json['currently']['temperature'] . '°C',
            'apparentTemperature' => '(' . $parsed_json['currently']['apparentTemperature'] . '°C)',
            'humidity' => $parsed_json['currently']['humidity']*100 . '%',
            'precipProbability' => $parsed_json['currently']['precipProbability']*100 . '%',
            'windSpeed' => $parsed_json['currently']['windSpeed'] . 'km/h',
            'windBearing' => $parsed_json['currently']['windBearing'] > 179 ? $parsed_json['currently']['windBearing'] -180 : $windBearing_status = $parsed_json['currently']['windBearing'] + 180,
            'cloudCover' => $parsed_json['currently']['cloudCover']*100 . '%',
            'pressure' => $parsed_json['currently']['pressure'] . 'hPa',
            'ozone' => $parsed_json['currently']['ozone'] . 'DU',
            'uvIndex' => $parsed_json['currently']['uvIndex'],
        );

        $return['hour'] = array(
            'summary' => $parsed_json['hourly']['data']['1']['summary'],
            'icon' => $parsed_json['hourly']['data']['1']['icon'],
            'temperature' => $parsed_json['hourly']['data']['1']['temperature'] . '°C',
            'apparentTemperature' => '(' . $parsed_json['hourly']['data']['1']['apparentTemperature'] . '°C)',
            'humidity' => $parsed_json['hourly']['data']['1']['humidity']*100 . '%',
            'precipProbability' => $parsed_json['hourly']['data']['1']['precipProbability']*100 . '%',
            'windSpeed' => $parsed_json['hourly']['data']['1']['windSpeed'] . 'km/h',
            'windBearing' => $parsed_json['hourly']['data']['1']['windBearing'] > 179 ? $parsed_json['hourly']['data']['0']['windBearing'] -180 : $windBearing_status = $parsed_json['hourly']['data']['0']['windBearing'] + 180,
            'cloudCover' => $parsed_json['hourly']['data']['1']['cloudCover']*100 . '%',
            'pressure' => $parsed_json['hourly']['data']['1']['pressure'] . 'hPa',
            'ozone' => $parsed_json['hourly']['data']['1']['ozone'] . 'DU',
            'uvIndex' => $parsed_json['hourly']['data']['1']['uvIndex'],
        );

        $return['day0'] = array(
            'summary' => $parsed_json['daily']['data']['0']['summary'],
            'icon' => $parsed_json['daily']['data']['0']['icon'],
            'temperatureMin' => $parsed_json['daily']['data']['0']['temperatureMin'] . '°C',
            'temperatureMax' => $parsed_json['daily']['data']['0']['temperatureMax'] . '°C',
            'humidity' => $parsed_json['daily']['data']['0']['humidity']*100 . '%',
            'precipProbability' => $parsed_json['daily']['data']['0']['precipProbability']*100 . '%',
            'windSpeed' => $parsed_json['daily']['data']['0']['windSpeed'] . 'km/h',
            'windBearing' => $parsed_json['daily']['data']['0']['windBearing'] > 179 ? $parsed_json['daily']['data']['0']['windBearing'] -180 : $windBearing_status = $parsed_json['daily']['data']['0']['windBearing'] + 180,
            'cloudCover' => $parsed_json['daily']['data']['0']['cloudCover']*100 . '%',
            'pressure' => $parsed_json['daily']['data']['0']['pressure'] . 'hPa',
            'ozone' => $parsed_json['daily']['data']['0']['ozone'] . 'DU',
            'sunriseTime' => date('H:i',$parsed_json['daily']['data']['0']['sunriseTime']),
            'sunsetTime' => date('H:i',$parsed_json['daily']['data']['0']['sunsetTime']),
            'uvIndex' => $parsed_json['daily']['data']['0']['uvIndex'],
        );

        $return['day1'] = array(
            'summary' => $parsed_json['daily']['data']['1']['summary'],
            'icon' => $parsed_json['daily']['data']['1']['icon'],
            'temperatureMin' => $parsed_json['daily']['data']['1']['temperatureMin'] . '°C',
            'temperatureMax' => $parsed_json['daily']['data']['1']['temperatureMax'] . '°C',
            'humidity' => $parsed_json['daily']['data']['1']['humidity']*100 . '%',
            'precipProbability' => $parsed_json['daily']['data']['1']['precipProbability']*100 . '%',
            'windSpeed' => $parsed_json['daily']['data']['1']['windSpeed'] . 'km/h',
            'windBearing' => $parsed_json['daily']['data']['1']['windBearing'] > 179 ? $parsed_json['daily']['data']['1']['windBearing'] -180 : $windBearing_status = $parsed_json['daily']['data']['1']['windBearing'] + 180,
            'cloudCover' => $parsed_json['daily']['data']['1']['cloudCover']*100 . '%',
            'pressure' => $parsed_json['daily']['data']['1']['pressure'] . 'hPa',
            'ozone' => $parsed_json['daily']['data']['1']['ozone'] . 'DU',
            'sunriseTime' => date('H:i',$parsed_json['daily']['data']['1']['sunriseTime']),
            'sunsetTime' => date('H:i',$parsed_json['daily']['data']['1']['sunsetTime']),
            'uvIndex' => $parsed_json['daily']['data']['1']['uvIndex'],
        );

        $return['day2'] = array(
            'summary' => $parsed_json['daily']['data']['2']['summary'],
            'icon' => $parsed_json['daily']['data']['2']['icon'],
            'temperatureMin' => $parsed_json['daily']['data']['2']['temperatureMin'] . '°C',
            'temperatureMax' => $parsed_json['daily']['data']['2']['temperatureMax'] . '°C',
            'humidity' => $parsed_json['daily']['data']['2']['humidity']*100 . '%',
            'precipProbability' => $parsed_json['daily']['data']['2']['precipProbability']*100 . '%',
            'windSpeed' => $parsed_json['daily']['data']['2']['windSpeed'] . 'km/h',
            'windBearing' => $parsed_json['daily']['data']['2']['windBearing'] > 179 ? $parsed_json['daily']['data']['2']['windBearing'] -180 : $windBearing_status = $parsed_json['daily']['data']['2']['windBearing'] + 180,
            'cloudCover' => $parsed_json['daily']['data']['2']['cloudCover']*100 . '%',
            'pressure' => $parsed_json['daily']['data']['2']['pressure'] . 'hPa',
            'ozone' => $parsed_json['daily']['data']['2']['ozone'] . 'DU',
            'sunriseTime' => date('H:i',$parsed_json['daily']['data']['2']['sunriseTime']),
            'sunsetTime' => date('H:i',$parsed_json['daily']['data']['2']['sunsetTime']),
            'uvIndex' => $parsed_json['daily']['data']['2']['uvIndex'],
        );

        $return['day3'] = array(
            'summary' => $parsed_json['daily']['data']['3']['summary'],
            'icon' => $parsed_json['daily']['data']['3']['icon'],
            'temperatureMin' => $parsed_json['daily']['data']['3']['temperatureMin'] . '°C',
            'temperatureMax' => $parsed_json['daily']['data']['3']['temperatureMax'] . '°C',
            'humidity' => $parsed_json['daily']['data']['3']['humidity']*100 . '%',
            'precipProbability' => $parsed_json['daily']['data']['3']['precipProbability']*100 . '%',
            'windSpeed' => $parsed_json['daily']['data']['3']['windSpeed'] . 'km/h',
            'windBearing' => $parsed_json['daily']['data']['3']['windBearing'] > 179 ? $parsed_json['daily']['data']['3']['windBearing'] -180 : $windBearing_status = $parsed_json['daily']['data']['3']['windBearing'] + 180,
            'cloudCover' => $parsed_json['daily']['data']['3']['cloudCover']*100 . '%',
            'pressure' => $parsed_json['daily']['data']['3']['pressure'] . 'hPa',
            'ozone' => $parsed_json['daily']['data']['3']['ozone'] . 'DU',
            'sunriseTime' => date('H:i',$parsed_json['daily']['data']['3']['sunriseTime']),
            'sunsetTime' => date('H:i',$parsed_json['daily']['data']['3']['sunsetTime']),
            'uvIndex' => $parsed_json['daily']['data']['3']['uvIndex'],
        );

        $return['day4'] = array(
            'summary' => $parsed_json['daily']['data']['4']['summary'],
            'icon' => $parsed_json['daily']['data']['4']['icon'],
            'temperatureMin' => $parsed_json['daily']['data']['4']['temperatureMin'] . '°C',
            'temperatureMax' => $parsed_json['daily']['data']['4']['temperatureMax'] . '°C',
            'humidity' => $parsed_json['daily']['data']['4']['humidity']*100 . '%',
            'precipProbability' => $parsed_json['daily']['data']['4']['precipProbability']*100 . '%',
            'windSpeed' => $parsed_json['daily']['data']['4']['windSpeed'] . 'km/h',
            'windBearing' => $parsed_json['daily']['data']['4']['windBearing'] > 179 ? $parsed_json['daily']['data']['4']['windBearing'] -180 : $windBearing_status = $parsed_json['daily']['data']['4']['windBearing'] + 180,
            'cloudCover' => $parsed_json['daily']['data']['4']['cloudCover']*100 . '%',
            'pressure' => $parsed_json['daily']['data']['4']['pressure'] . 'hPa',
            'ozone' => $parsed_json['daily']['data']['4']['ozone'] . 'DU',
            'sunriseTime' => date('H:i',$parsed_json['daily']['data']['4']['sunriseTime']),
            'sunsetTime' => date('H:i',$parsed_json['daily']['data']['4']['sunsetTime']),
            'uvIndex' => $parsed_json['daily']['data']['4']['uvIndex'],
        );

        $return['day5'] = array(
            'summary' => $parsed_json['daily']['data']['5']['summary'],
            'icon' => $parsed_json['daily']['data']['5']['icon'],
            'temperatureMin' => $parsed_json['daily']['data']['5']['temperatureMin'] . '°C',
            'temperatureMax' => $parsed_json['daily']['data']['5']['temperatureMax'] . '°C',
            'humidity' => $parsed_json['daily']['data']['5']['humidity']*100 . '%',
            'precipProbability' => $parsed_json['daily']['data']['5']['precipProbability']*100 . '%',
            'windSpeed' => $parsed_json['daily']['data']['5']['windSpeed'] . 'km/h',
            'windBearing' => $parsed_json['daily']['data']['5']['windBearing'] > 179 ? $parsed_json['daily']['data']['5']['windBearing'] -180 : $windBearing_status = $parsed_json['daily']['data']['5']['windBearing'] + 180,
            'cloudCover' => $parsed_json['daily']['data']['5']['cloudCover']*100 . '%',
            'pressure' => $parsed_json['daily']['data']['5']['pressure'] . 'hPa',
            'ozone' => $parsed_json['daily']['data']['5']['ozone'] . 'DU',
            'sunriseTime' => date('H:i',$parsed_json['daily']['data']['5']['sunriseTime']),
            'sunsetTime' => date('H:i',$parsed_json['daily']['data']['5']['sunsetTime']),
            'uvIndex' => $parsed_json['daily']['data']['5']['uvIndex'],
        );

        $return['day6'] = array(
            'summary' => $parsed_json['daily']['data']['6']['summary'],
            'icon' => $parsed_json['daily']['data']['6']['icon'],
            'temperatureMin' => $parsed_json['daily']['data']['6']['temperatureMin'] . '°C',
            'temperatureMax' => $parsed_json['daily']['data']['6']['temperatureMax'] . '°C',
            'humidity' => $parsed_json['daily']['data']['6']['humidity']*100 . '%',
            'precipProbability' => $parsed_json['daily']['data']['6']['precipProbability']*100 . '%',
            'windSpeed' => $parsed_json['daily']['data']['6']['windSpeed'] . 'km/h',
            'windBearing' => $parsed_json['daily']['data']['6']['windBearing'] > 179 ? $parsed_json['daily']['data']['6']['windBearing'] -180 : $windBearing_status = $parsed_json['daily']['data']['6']['windBearing'] + 180,
            'cloudCover' => $parsed_json['daily']['data']['6']['cloudCover']*100 . '%',
            'pressure' => $parsed_json['daily']['data']['6']['pressure'] . 'hPa',
            'ozone' => $parsed_json['daily']['data']['6']['ozone'] . 'DU',
            'sunriseTime' => date('H:i',$parsed_json['daily']['data']['6']['sunriseTime']),
            'sunsetTime' => date('H:i',$parsed_json['daily']['data']['6']['sunsetTime']),
            'uvIndex' => $parsed_json['daily']['data']['6']['uvIndex'],
        );

        return $return;
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
                $replace['#day#'] = date_fr(date('l', strtotime('+' . $i . ' days')));

                $j = $i + 1;
                $temperature_min = $this->getCmd(null, 'temperatureMin_' . $j);
                $replace['#low_temperature#'] = is_object($temperature_min) ? round($temperature_min->execCmd()) : '';

                $temperature_max = $this->getCmd(null, 'temperatureMax_' . $j);
                $replace['#hight_temperature#'] = is_object($temperature_max) ? round($temperature_max->execCmd()) : '';
                $replace['#tempid#'] = is_object($temperature_max) ? $temperature_max->getId() : '';

                $icone = $this->getCmd(null, 'icon_' . $j);
                $replace['#icone#'] = is_object($icone) ? $icone->getId() : '';

                $html_forecast .= template_replace($replace, $forcast_template);
            }
        }

        $replace['#forecast#'] = $html_forecast;
        $replace['#city#'] = $this->getName();

        $temperature = $this->getCmd(null, 'temperature');
        $replace['#temperature#'] = is_object($temperature) ? round($temperature->execCmd()) : '';
        $replace['#tempid#'] = is_object($temperature) ? $temperature->getId() : '';

        $conditionday = $this->getCmd(null, 'summaryhours');
        $replace['#conditionday#'] = is_object($conditionday) ? $conditionday->execCmd() : '';
        $replace['#conditiondayid#'] = is_object($conditionday) ? $conditionday->getId() : '';

        $humidity = $this->getCmd(null, 'humidity');
        $replace['#humidity#'] = is_object($humidity) ? $humidity->execCmd() : '';

        $uvindex = $this->getCmd(null, 'uvIndex');
        $replace['#uvi#'] = is_object($uvindex) ? $uvindex->execCmd() : '';

        $pressure = $this->getCmd(null, 'pressure');
        $replace['#pressure#'] = is_object($pressure) ? $pressure->execCmd() : '';
        $replace['#pressureid#'] = is_object($pressure) ? $pressure->getId() : '';

        $wind_speed = $this->getCmd(null, 'windSpeed');
        $replace['#windspeed#'] = is_object($wind_speed) ? $wind_speed->execCmd() : '';
        $replace['#windid#'] = is_object($wind_speed) ? $wind_speed->getId() : '';

        $sunrise = $this->getCmd(null, 'sunriseTime');
        $replace['#sunrise#'] = is_object($sunrise) ? substr_replace($sunrise->execCmd(),':',-2,0) : '';
        $replace['#sunriseid#'] = is_object($sunrise) ? $sunrise->getId() : '';

        $sunset = $this->getCmd(null, 'sunsetTime');
        $replace['#sunset#'] = is_object($sunset) ? substr_replace($sunset->execCmd(),':',-2,0) : '';
        $replace['#sunsetid#'] = is_object($sunset) ? $sunset->getId() : '';

        $wind_direction = $this->getCmd(null, 'windBearing');
        $replace['#wind_direction#'] = is_object($wind_direction) ? $wind_direction->execCmd() : 0;

        $refresh = $this->getCmd(null, 'refresh');
        $replace['#refresh#'] = is_object($refresh) ? $refresh->getId() : '';

        $condition = $this->getCmd(null, 'summary');
        $icone = $this->getCmd(null, 'icon');
        if (is_object($condition)) {
            $replace['#iconeid#'] = $icone->getId();
            $replace['#condition#'] = $condition->execCmd();
            $replace['#conditionid#'] = $condition->getId();
            $replace['#collectDate#'] = $condition->getCollectDate();
        } else {
            $replace['#icone#'] = '';
            $replace['#condition#'] = '';
            $replace['#collectDate#'] = '';
        }

        $icone = $this->getCmd(null, 'icon');
        $replace['#icone#'] = is_object($icone) ? $icone->execCmd() : '';

        $icone1 = $this->getCmd(null, 'icon_1');
        $replace['#icone1#'] = is_object($icone1) ? $icone1->execCmd() : '';
        $replace['#iconeid1#'] = is_object($icone1) ? $icone1->getId() : '';

        $icone2 = $this->getCmd(null, 'icon_2');
        $replace['#icone2#'] = is_object($icone2) ? $icone2->execCmd() : '';
        $replace['#iconeid2#'] = is_object($icone2) ? $icone2->getId() : '';

        $icone3 = $this->getCmd(null, 'icon_3');
        $replace['#icone3#'] = is_object($icone3) ? $icone3->execCmd() : '';
        $replace['#iconeid3#'] = is_object($icone3) ? $icone3->getId() : '';

        $icone4 = $this->getCmd(null, 'icon_4');
        $replace['#icone4#'] = is_object($icone4) ? $icone4->execCmd() : '';
        $replace['#iconeid4#'] = is_object($icone4) ? $icone4->getId() : '';

        $icone5 = $this->getCmd(null, 'icon_5');
        $replace['#icone5#'] = is_object($icone5) ? $icone5->execCmd() : '';
        $replace['#iconeid5#'] = is_object($icone5) ? $icone5->getId() : '';

        $parameters = $this->getDisplay('parameters');
        if (is_array($parameters)) {
            foreach ($parameters as $key => $value) {
                $replace['#' . $key . '#'] = $value;
            }
        }

        return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'current', 'weatherbit')));
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
