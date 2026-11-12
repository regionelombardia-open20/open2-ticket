<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open2\amos\ticket\widgets\graphics\views\fullsize
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

use open20\amos\core\helpers\Html;
use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\models\Ticket;
use open2\amos\ticket\widgets\graphics\WidgetGraphicAssistance;

/**
 * @var yii\web\View $this
 * @var WidgetGraphicAssistance $widget
 * @var Ticket $ticket
 * @var string $listContainerClass
 */

?>

<p><span class="<?= $listContainerClass; ?>"><?= '#'. $ticket->id; ?></span>
    <span><?= Yii::$app->getFormatter()->asDate($ticket->created_at) . ' ' . AmosTicket::t('amosticket', '#from') . ' ' . $ticket->createdUserProfile->nomeCognome; ?>
</p>
<p><?= Html::a($ticket->titolo, $ticket->getFullViewUrl()); ?></p>
