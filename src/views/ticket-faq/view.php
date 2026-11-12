<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open2\amos\ticket\views\ticket-faq
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

use open20\amos\core\forms\ContextMenuWidget;
use open2\amos\ticket\AmosTicket;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var open2\amos\ticket\models\TicketFaq $model
 */

$this->title = strip_tags(substr($model->domanda, 0, 50) . '...');
//$this->params['breadcrumbs'][] = ['label' => Yii::t('cruds', 'Ticket Faq'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = ['label' => AmosTicket::t('amosticket', 'Assistenza'), 'url' => '/ticket'];
$this->params['breadcrumbs'][] = ['label' => AmosTicket::t('amosticket', 'Faq'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-faq-view col-xs-12">
    <?=
    ContextMenuWidget::widget([
        'model' => $model,
        'actionModify' => "/ticket/ticket-faq/update?id=" . $model->id,
        'actionDelete' => "/ticket/ticket-faq/delete?id=" . $model->id,
        'labelDeleteConfirm' => AmosTicket::t('amosticket', 'Sei sicuro di voler cancellare questa faq?'),
    ])
    ?>
    <h2>
        <?= AmosTicket::t('amosticket', 'Dettagli'); ?>
    </h2>
    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'domanda:html',
           
            'risposta:html',
            [
                'attribute' => 'ticket_categoria_id',
                'label' => $model->getAttributeLabel('ticket_categoria_id'),
                'value' => (!is_null($model->ticket_categoria_id) ? $model->ticketCategoria->nomeCompleto : '-'),
            ]
        ],
    ])
    ?>

    <div class="btnViewContainer pull-right">
        <?= Html::a(Yii::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?>
    </div>
</div>
