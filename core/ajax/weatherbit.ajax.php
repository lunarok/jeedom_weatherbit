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

try {
  require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
  include_file('core', 'authentification', 'php');

  if (!isConnect('admin')) {
    throw new Exception(__('401 - Accès non autorisé', __FILE__));
  }

  if (init('action') == 'createcmd') {
    $eqLogic = eqLogic::byId(init('id'));
    if (!is_object($eqLogic)) {
      return true;
    }
    $eqLogic->loadCmdFromType(init('type'));
    ajax::success();
  }

  if (init('action') == 'loadingData') {
    if (init('value') == '0') {
      foreach (eqLogic::byType('weatherbit', true) as $eqLogic) {
        $value = $eqLogic->getId();
        break;
      }
    } else {
      $value = init('value');
    }
    $eqLogic = eqLogic::byId($value);
    if (!is_object($eqLogic)) {
      return true;
    }
    ajax::success($eqLogic->loadingData());
  }

  if (init('action') == 'getWeatherbit') {
    foreach (eqLogic::byType('weatherbit', true) as $eqLogic) {
      if ($eqLogic->getIsEnable() == 0 || $eqLogic->getIsVisible() == 0) {
        continue;
      }
      $return[] = '<button class="btn btn-default weatherbitEqlogic" id="' . $eqLogic->getId() . '" type="button" onClick="loadingData(' . $eqLogic->getId() . ')">' . $eqLogic->getName() . '</button>';
    }
    ajax::success($return);
  }

  throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
  /*     * *********Catch exeption*************** */
} catch (Exception $e) {
  ajax::error(displayExeption($e), $e->getCode());
}
?>
