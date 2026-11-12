<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open2\amos\ticket\mail\generic
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
 */

use open2\amos\ticket\AmosTicket;

/**
 * @var $this yii\web\View
 */

$appLink = Yii::$app->urlManager->createAbsoluteUrl(['/']);
$appName = Yii::$app->name;

$this->title = AmosTicket::t('amosticket', 'Registrazione {appName}', ['appName' => $appName]);
$this->registerCssFile('http://fonts.googleapis.com/css?family=Roboto');
?>


<table width=" 600" border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td>
      <div class="corpo" style="border:1px solid #cccccc;padding:10px;margin-bottom:10px;background-color:#ffffff;margin-top:20px">
        <div class="sezione titolo" style="overflow:hidden;color:#000000;">
          <h2 style="padding:5px 0;	margin:0;"><?= AmosTicket::t('amosticket', 'Servizio assistenza'); ?></h2>
        </div>
        <div class="sezione" style="overflow:hidden;color:#000000;">
          <div class="testo">
          <?php
            foreach ($body as $paragraph) {
              echo '<p>' . $paragraph . '<br /></p>';
            }
          ?>
          </div>
        </div>
      </div>
    </td>
  </tr>
</table>

<table width="600" border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td>
      <p style="text-align:center"><?= AmosTicket::t('amosticket', '*** Questa è una e-mail generata automaticamente, si prega di non rispondere***'); ?></p>
    </td>
  </tr>
</table>