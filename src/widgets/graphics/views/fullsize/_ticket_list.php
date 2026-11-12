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
use open20\amos\core\icons\AmosIcons;
use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\models\Ticket;
use open2\amos\ticket\widgets\graphics\WidgetGraphicAssistance;

/**
 * @var yii\web\View $this
 * @var WidgetGraphicAssistance $widget
 * @var Ticket[] $ticketsList
 * @var string $listTitle
 * @var array $linkToTicketList
 * @var string $listContainerClass
 */

?>

<div class="<?= $listContainerClass; ?>">
    <div>
        <h4><?= $listTitle; ?></h4>
        <?= Html::a(AmosIcons::show('open-in-new',['class' => 'am-2']), $linkToTicketList) ?>
    </div>
    <section>
        <?php if (count($ticketsList) == 0): ?>
            <div class="list-items list-empty"><p><?= AmosTicket::t('amosticket', '#widget_assistance_list_no_tickets') ?></p></div>
        <?php endif; ?>
        <div class="list-items">
            <?php foreach ($ticketsList as $ticket): ?>
                <div class="widget-listbox-option" role="option">
                    <article class="wrap-item-box">
                        <?= $this->render('_ticket_list_element', [
                            'widget' => $widget,
                            'ticket' => $ticket,
                            'listContainerClass' => $listContainerClass,
                        ]) ?>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>
