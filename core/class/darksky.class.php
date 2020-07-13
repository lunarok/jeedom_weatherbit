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

    public function preUpdate() {
        if ($this->getConfiguration('geoloc') == '') {
            throw new Exception(__('La géolocalisation ne peut etre vide',__FILE__));
        }
        if ($this->getConfiguration('apikey') == '') {
            throw new Exception(__('La clef API ne peut etre vide',__FILE__));
        }
    }

    public function postUpdate() {
        //info actual
        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'summary');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Condition', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('summary');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','actual');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'icon');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Icone', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('icon');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','actual');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'precipIntensity');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Intensité de Précipitation', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('precipIntensity');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','actual');
        $weatherbitCmd->setUnite( 'mm/h' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'precipProbability');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Probabilité de Précipitation', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('precipProbability');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','actual');
        $weatherbitCmd->setUnite( '%' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'precipType');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Type de Précipitation', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('precipType');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','actual');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'temperature');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Température', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('temperature');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','actual');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'apparentTemperature');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Température Apparente', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('apparentTemperature');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','actual');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'dewPoint');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Point de Rosée', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('dewPoint');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','actual');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'humidity');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Humidité', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('humidity');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','actual');
        $weatherbitCmd->setUnite( '%' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'windGust');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Vitesse de Rafale', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('windGust');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','actual');
        $weatherbitCmd->setUnite( 'km/h' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'windSpeed');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Vitesse du Vent', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('windSpeed');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','actual');
        $weatherbitCmd->setUnite( 'km/h' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'windBearing');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Direction du Vent', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('windBearing');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','actual');
        $weatherbitCmd->setUnite( '°' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'windBearing0');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Provenance du Vent', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('windBearing0');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','actual');
        $weatherbitCmd->setUnite( '°' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'cloudCover');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Couverture Nuageuse', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('cloudCover');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','actual');
        $weatherbitCmd->setUnite( '%' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'pressure');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Pression', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('pressure');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','actual');
        $weatherbitCmd->setUnite( 'hPa' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'ozone');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Ozone', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('ozone');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','actual');
        $weatherbitCmd->setUnite( 'DU' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'uvIndex');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Index UV', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('uvIndex');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','actual');
        $weatherbitCmd->save();

        //info H+1
        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'summaryh1');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Condition H+1', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('summaryh1');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','h1');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'iconh1');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Icone H+1', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('iconh1');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','h1');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'precipIntensityh1');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Intensité de Précipitation H+1', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('precipIntensityh1');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h1');
        $weatherbitCmd->setUnite( 'mm/h' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'precipProbabilityh1');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Probabilité de Précipitation H+1', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('precipProbabilityh1');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h1');
        $weatherbitCmd->setUnite( '%' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'precipTypeh1');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Type de Précipitation H+1', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('precipTypeh1');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','h1');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureh1');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Température H+1', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('temperatureh1');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h1');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'apparentTemperatureh1');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Température Apparente H+1', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('apparentTemperatureh1');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h1');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'dewPointh1');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Point de Rosée H+1', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('dewPointh1');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h1');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'humidityh1');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Humidité H+1', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('humidityh1');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h1');
        $weatherbitCmd->setUnite( '%' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'windSpeedh1');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Vitesse du Vent H+1', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('windSpeedh1');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h1');
        $weatherbitCmd->setUnite( 'km/h' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'windGusth1');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Vitesse de Rafale H+1', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('windGusth1');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h1');
        $weatherbitCmd->setUnite( 'km/h' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'windBearingh1');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Direction du Vent H+1', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('windBearingh1');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h1');
        $weatherbitCmd->setUnite( '°' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'windBearing0h1');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Provenance du Vent H+1', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('windBearing0h1');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h1');
        $weatherbitCmd->setUnite( '°' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'cloudCoverh1');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Couverture Nuageuse H+1', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('cloudCoverh1');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h1');
        $weatherbitCmd->setUnite( '%' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'pressureh1');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Pression H+1', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('pressureh1');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h1');
        $weatherbitCmd->setUnite( 'hPa' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'ozoneh1');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Ozone H+1', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('ozoneh1');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h1');
        $weatherbitCmd->setUnite( 'DU' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'uvIndexh1');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Index UV H+1', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('uvIndexh1');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h1');
        $weatherbitCmd->save();

        //status H+2
        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'summaryh2');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Condition H+2', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('summaryh2');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','h2');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'iconh2');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Icone H+2', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('iconh2');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','h2');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'precipIntensityh2');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Intensité de Précipitation H+2', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('precipIntensityh2');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h2');
        $weatherbitCmd->setUnite( '%' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'precipProbabilityh2');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Probabilité de Précipitation H+2', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('precipProbabilityh2');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h2');
        $weatherbitCmd->setUnite( '%' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'precipTypeh2');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Type de Précipitation H+2', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('precipTypeh2');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','h2');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureh2');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Température H+2', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('temperatureh2');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h2');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'apparentTemperatureh2');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Température Apparente H+2', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('apparentTemperatureh2');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h2');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'dewPointh2');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Point de Rosée H+2', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('dewPointh2');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h2');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'humidityh2');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Humidité H+2', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('humidityh2');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h2');
        $weatherbitCmd->setUnite( '%' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'windSpeedh2');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Vitesse du Vent H+2', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('windSpeedh2');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h2');
        $weatherbitCmd->setUnite( 'km/h' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'windGusth2');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Vitesse de Rafale H+2', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('windGusth2');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h2');
        $weatherbitCmd->setUnite( 'km/h' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'windBearingh2');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Direction du Vent H+2', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('windBearingh2');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h2');
        $weatherbitCmd->setUnite( '°' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'windBearing0h2');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Provenance du Vent H+2', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('windBearing0h2');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h2');
        $weatherbitCmd->setUnite( '°' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'cloudCoverh2');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Couverture Nuageuse H+2', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('cloudCoverh2');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h2');
        $weatherbitCmd->setUnite( '%' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'pressureh2');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Pression H+2', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('pressureh2');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h2');
        $weatherbitCmd->setUnite( 'hPa' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'ozoneh2');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Ozone H+2', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('ozoneh2');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h2');
        $weatherbitCmd->setUnite( 'DU' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'uvIndexh2');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Index UV H+2', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('uvIndexh2');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h2');
        $weatherbitCmd->save();

        //status H+3
        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'summaryh3');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Condition H+3', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('summaryh3');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','h3');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'iconh3');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Icone H+3', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('iconh3');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','h3');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'precipIntensityh3');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Intensité de Précipitation H+3', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('precipIntensityh3');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h3');
        $weatherbitCmd->setUnite( 'mm/h' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'precipProbabilityh3');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Probabilité de Précipitation H+3', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('precipProbabilityh3');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h3');
        $weatherbitCmd->setUnite( '%' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'precipTypeh3');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Type de Précipitation H+3', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('precipTypeh3');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','h3');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureh3');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Température H+3', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('temperatureh3');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h3');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'apparentTemperatureh3');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Température Apparente H+3', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('apparentTemperatureh3');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h3');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'dewPointh3');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Point de Rosée H+3', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('dewPointh3');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h3');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'humidityh3');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Humidité H+3', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('humidityh3');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h3');
        $weatherbitCmd->setUnite( '%' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'windSpeedh3');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Vitesse du Vent H+3', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('windSpeedh3');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h3');
        $weatherbitCmd->setUnite( 'km/h' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'windGusth3');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Vitesse de Rafale H+3', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('windGusth3');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h3');
        $weatherbitCmd->setUnite( 'km/h' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'windBearingh3');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Direction du Vent H+3', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('windBearingh3');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h3');
        $weatherbitCmd->setUnite( '°' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'windBearing0h3');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Provenance du Vent H+3', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('windBearing0h3');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h3');
        $weatherbitCmd->setUnite( '°' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'cloudCoverh3');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Couverture Nuageuse H+3', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('cloudCoverh3');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h3');
        $weatherbitCmd->setUnite( '%' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'pressureh3');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Pression H+3', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('pressureh3');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h3');
        $weatherbitCmd->setUnite( 'hPa' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'ozoneh3');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Ozone H+3', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('ozoneh3');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h3');
        $weatherbitCmd->setUnite( 'DU' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'uvIndexh3');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Index UV H+3', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('uvIndexh3');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h3');
        $weatherbitCmd->save();

        //status H+4
        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'summaryh4');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Condition H+4', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('summaryh4');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','h4');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'iconh4');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Icone H+4', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('iconh4');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','h4');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'precipIntensityh4');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Intensité de Précipitation H+4', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('precipIntensityh4');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h4');
        $weatherbitCmd->setUnite( 'mm/h' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'precipProbabilityh4');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Probabilité de Précipitation H+4', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('precipProbabilityh4');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h4');
        $weatherbitCmd->setUnite( '%' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'precipTypeh4');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Type de Précipitation H+4', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('precipTypeh4');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','h4');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureh4');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Température H+4', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('temperatureh4');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h4');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'apparentTemperatureh4');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Température Apparente H+4', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('apparentTemperatureh4');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h4');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'dewPointh4');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Point de Rosée H+4', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('dewPointh4');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h4');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'humidityh4');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Humidité H+4', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('humidityh4');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h4');
        $weatherbitCmd->setUnite( '%' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'windSpeedh4');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Vitesse du Vent H+4', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('windSpeedh4');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h4');
        $weatherbitCmd->setUnite( 'km/h' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'windGusth4');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Vitesse de Rafale H+4', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('windGusth4');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h4');
        $weatherbitCmd->setUnite( 'km/h' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'windBearingh4');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Direction du Vent H+4', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('windBearingh4');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h4');
        $weatherbitCmd->setUnite( '°' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'windBearing0h4');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Provenance du Vent H+4', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('windBearing0h4');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h4');
        $weatherbitCmd->setUnite( '°' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'cloudCoverh4');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Couverture Nuageuse H+4', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('cloudCoverh4');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h4');
        $weatherbitCmd->setUnite( '%' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'pressureh4');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Pression H+4', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('pressureh4');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h4');
        $weatherbitCmd->setUnite( 'hPa' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'ozoneh4');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Ozone H+4', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('ozoneh4');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h4');
        $weatherbitCmd->setUnite( 'DU' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'uvIndexh4');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Index UV H+4', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('uvIndexh4');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h4');
        $weatherbitCmd->save();

        //status H+5
        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'summaryh5');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Condition H+5', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('summaryh5');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','h5');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'iconh5');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Icone H+5', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('iconh5');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','h5');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'precipIntensityh5');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Intensité de Précipitation H+5', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('precipIntensityh5');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h5');
        $weatherbitCmd->setUnite( 'mkm/h' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'precipProbabilityh5');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Probabilité de Précipitation H+5', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('precipProbabilityh5');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h5');
        $weatherbitCmd->setUnite( '%' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'precipTypeh5');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Type de Précipitation H+5', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('precipTypeh5');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','h5');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureh5');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Température H+5', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('temperatureh5');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h5');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'apparentTemperatureh5');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Température Apparente H+5', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('apparentTemperatureh5');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h5');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'dewPointh5');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Point de Rosée H+5', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('dewPointh5');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h5');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'humidityh5');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Humidité H+5', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('humidityh5');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h5');
        $weatherbitCmd->setUnite( '%' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'windSpeedh5');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Vitesse du Vent H+5', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('windSpeedh5');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h5');
        $weatherbitCmd->setUnite( 'km/h' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'windGusth5');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Vitesse de Rafale H+5', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('windGusth5');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h5');
        $weatherbitCmd->setUnite( 'km/h' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'windBearingh5');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Direction du Vent H+5', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('windBearingh5');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h5');
        $weatherbitCmd->setUnite( '°' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'windBearing0h5');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Provenance du Vent H+5', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('windBearing0h5');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h5');
        $weatherbitCmd->setUnite( '°' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'cloudCoverh5');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Couverture Nuageuse H+5', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('cloudCoverh5');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h5');
        $weatherbitCmd->setUnite( '%' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'pressureh5');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Pression H+5', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('pressureh5');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h5');
        $weatherbitCmd->setUnite( 'hPa' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'ozoneh5');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Ozone H+5', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('ozoneh5');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h5');
        $weatherbitCmd->setUnite( 'DU' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'uvIndexh5');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Index UV H+5', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('uvIndexh5');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','h5');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'sunriseTime');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Lever du Soleil', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('sunriseTime');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','actual');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'sunsetTime');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Coucher du Soleil', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('sunsetTime');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','actual');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'summaryweek');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Condition semaine', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('summaryweek');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','actual');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'iconweek');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Icone semaine', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('iconweek');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','actual');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'summaryhours');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Condition prochaines heures', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('summaryhours');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','actual');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'iconhours');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Icone prochaines heures', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('iconhours');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','actual');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'apparentTemperatureMin');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Température Minimum Apparente', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('apparentTemperatureMin');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'apparentTemperatureMax');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Température Maximum Apparente', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('apparentTemperatureMax');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureMin_1');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Température Minimum Jour', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('temperatureMin_1');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureMax_1');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Température Maximum Jour', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('temperatureMax_1');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'uvIndex_1');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Index UV Jour', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('uvIndex_1');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'precipProbability_1');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Probalitié Pluie Jour', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('precipProbability_1');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'summary_1');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Condition Jour', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('summary_1');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'icon_1');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Icone Jour', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('icon_1');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureMin_2');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Température Minimum +1', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('temperatureMin_2');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureMax_2');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Température Maximum +1', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('temperatureMax_2');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'uvIndex_2');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Index UV Jour +1', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('uvIndex_2');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'precipProbability_2');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Propbalitié Pluie Jour +1', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('precipProbability_2');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'summary_2');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Condition Jour +1', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('summary_2');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'icon_2');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Icone Jour +1', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('icon_2');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureMin_3');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Température Minimum +2', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('temperatureMin_3');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureMax_3');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Température Maximum +2', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('temperatureMax_3');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'uvIndex_3');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Index UV Jour +2', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('uvIndex_3');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'precipProbability_3');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Propbalitié Pluie Jour +2', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('precipProbability_3');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'summary_3');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Condition Jour +2', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('summary_3');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'icon_3');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Icone Jour +2', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('icon_3');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureMin_4');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Température Minimum +3', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('temperatureMin_4');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureMax_4');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Température Maximum +3', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('temperatureMax_4');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'uvIndex_4');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Index UV Jour +3', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('uvIndex_4');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'precipProbability_4');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Propbalitié Pluie Jour +3', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('precipProbability_4');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'summary_4');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Condition Jour +3', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('summary_4');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'icon_4');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Icone Jour +3', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('icon_4');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureMin_5');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Température Minimum +4', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('temperatureMin_5');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureMax_5');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Température Maximum +4', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('temperatureMax_5');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->setUnite( '°C' );
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'uvIndex_5');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Index UV Jour +4', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('uvIndex_5');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'precipProbability_5');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Propbalitié Pluie Jour +4', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('precipProbability_5');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('numeric');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'summary_5');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Condition Jour +4', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('summary_5');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'icon_5');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Icone Jour +4', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('icon_5');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','daily');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'alert');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Alertes', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('alert');
            $weatherbitCmd->setType('info');
            $weatherbitCmd->setSubType('string');
        }
        $weatherbitCmd->setConfiguration('category','actual');
        $weatherbitCmd->save();

        $weatherbitCmd = weatherbitCmd::byEqLogicIdAndLogicalId($this->getId(),'refresh');
        if (!is_object($weatherbitCmd)) {
            $weatherbitCmd = new weatherbitCmd();
            $weatherbitCmd->setName(__('Rafraichir', __FILE__));
            $weatherbitCmd->setEqLogic_id($this->getId());
            $weatherbitCmd->setLogicalId('refresh');
            $weatherbitCmd->setType('action');
            $weatherbitCmd->setSubType('other');
            $weatherbitCmd->save();
        }
        if (null !== ($this->getConfiguration('geoloc', '')) && $this->getConfiguration('geoloc', '') != 'none') {
            weatherbit::getInformations();
        } else {
            log::add('weatherbit', 'error', 'geoloc non saisie');
        }
    }


    public function getInformations($frequence = 'all') {
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
        $this->getCurrent($params);
        $this->getAlert($params);
        $this->refreshWidget();
    }

    public function getCurrent($_params) {
      $url = 'https://api.weatherbit.io/v2.0/current?' . $_params;
      https://api.weatherbit.io/v2.0/alerts?
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
      $parsed_json = json_decode($json_string, true);
    }

    public function getAlert($_params) {
      $url = 'https://api.weatherbit.io/v2.0/alerts?' . $_params;
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
      $parsed_json = json_decode($json_string, true);
    }

    public function getHourly($parsed_json) {
        $i = 1;
        while ($i < 6) {
            foreach ($parsed_json['hourly']['data'][$i] as $key => $value) {
                if ($key != 'solar') {
                    if ($key == 'windBearing') {
                        $this->checkAndUpdateCmd('windBearing0h' . $i, $value);
                        if ($value > 179) {
                            $value = $value -180;
                        } else {
                            $value = $value + 180;
                        }
                    }
                    if ($key == 'humidity' || $key == 'cloudCover') {
                        $value = $value * 100;
                    }
                    $this->checkAndUpdateCmd($key . 'h' . $i, $value);
                }
            }
            $i++;
        }
        foreach ($parsed_json['currently'] as $key => $value) {
            //log::add('weatherbit', 'debug', $key . ' ' . $value);
            if ($key != 'time' && $key != 'solar') {
                if ($key == 'windBearing') {
                    $this->checkAndUpdateCmd('windBearing0', $value);
                    if ($value > 179) {
                        $value = $value -180;
                    } else {
                        $value = $value + 180;
                    }
                }
                if ($key == 'humidity' || $key == 'cloudCover') {
                    $value = $value * 100;
                }
                $this->checkAndUpdateCmd($key, $value);
            }
        }

        if (!empty($parsed_json['alert'])) {
            $title = '';
            foreach ($parsed_json['alert'] as $key => $value) {
                if ($key == 'title') {
                    $title .= ', ' . $value;
                }
            }
            $this->checkAndUpdateCmd('alert', $value);
        }

        foreach ($parsed_json['daily']['data'][0] as $key => $value) {
            if ($key == 'apparentTemperatureMax' || $key == 'apparentTemperatureMin' || $key == 'temperatureMax' || $key == 'temperatureMin') {
                $this->checkAndUpdateCmd($key, $value);
            }
        }
            //daily
        $i = 0;
        while ($i < 5) {
            $j = $i +1;
            foreach ($parsed_json['daily']['data'][$i] as $key => $value) {
                if ($key == 'temperatureMax' || $key == 'temperatureMin' || $key == 'summary' || $key == 'icon' || $key == 'uvIndex' || $key == 'precipProbability') {
                    $this->checkAndUpdateCmd($key . '_' . $j, $value);
                }
            }
            $i++;
        }

        $this->checkAndUpdateCmd('summaryhours', $parsed_json['hourly']['summary']);
        $this->checkAndUpdateCmd('iconhours', $parsed_json['hourly']['icon']);
        $this->checkAndUpdateCmd('summaryweek', $parsed_json['daily']['summary']);
        $this->checkAndUpdateCmd('iconweek', $parsed_json['daily']['icon']);
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
            $eqLogic = $this->getEqLogic();
            $eqLogic->getInformations();
        }
    }

}

?>
