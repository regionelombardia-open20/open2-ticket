<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open2\amos\ticket\views\ticket
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

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\TextEditorWidget;
use open20\amos\core\helpers\Html;
use open20\amos\workflow\widgets\WorkflowTransitionButtonsWidget;
use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\models\Ticket;
use open2\amos\ticket\models\TicketCategorie;
use open2\amos\ticket\utility\TicketUtility;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var open2\amos\ticket\models\Ticket $model
 * @var open2\amos\ticket\models\Ticket $model_old_ticket
 */

?>
<div class="ticket-form-change-category col-xs-12">
    <?php $form = ActiveForm::begin(); ?>

    <?php $this->beginBlock('dettagli'); ?>
    <div class="row">
        <div class="col-xs-12 nop">
            <h2><?= $model->titolo ?></h2>
        </div>
        <div class="col-xs-12 nop">
            <?php
            $oldCategory = TicketCategorie::findOne($model_old_ticket->ticket_categoria_id);
            $ticketCategoryId = $model->ticket_categoria_id;
            ?>

            <?=
            $form->field($model, 'ticket_categoria_id')->widget(Select::className(), [
                'auto_fill' => true,
                'options' => [
                    'placeholder' => AmosTicket::t('amosticket', '#ticket_category_field_placeholder'),
                    'id' => 'ticket_categoria_id-id',
                    'disabled' => false,
                    'value' => $ticketCategoryId
                ],
                'data' =>
                    ArrayHelper::map(TicketUtility::getTicketCategories($oldCategory, true)
                        ->orderBy('titolo')->all(), 'id', 'titolo'),
            ]);
            ?>

            <?=
            $form->field($model, 'forward_message')->widget(TextEditorWidget::className(), [
                'clientOptions' => [
                    'placeholder' => AmosTicket::t('amosticket', '#forward_message_field_placeholder'),
                    'lang' => substr(Yii::$app->language, 0, 2)
                ]
            ])
            ?>

            <?php
            if ($model->isNewRecord) { //default categoria attiva
                $model->forward_message_to_operator = '0';
                $model->forward_notify = '1';
            }
            ?>

            <?=
            Html::tag('div', $form->field($model, 'forward_message_to_operator')->inline()->radioList(
                [
                    '1' => AmosTicket::t('amosticket', 'Si'),
                    '0' => AmosTicket::t('amosticket', 'No')
                ], ['class' => 'forward_operator-choice'])
                , ['class' => 'col-xs-12 nop']);
            ?>

            <?=
            Html::tag('div', $form->field($model, 'forward_notify')->inline()->radioList(
                [
                    '1' => AmosTicket::t('amosticket', 'Si'),
                    '0' => AmosTicket::t('amosticket', 'No')
                ], ['class' => 'notify-choice'])
                , ['class' => 'col-xs-12 nop']);
            ?>
        </div>
    </div>

    <?php $this->endBlock('dettagli'); ?>

    <?php
    $itemsTab[] = [
        'label' => Yii::t('cruds', 'dettagli'),
        'content' => $this->blocks['dettagli'],
    ];
    ?>

    <?=
    Tabs::widget(
        [
            'encodeLabels' => false,
            'items' => $itemsTab
        ]
    );
    ?>

    <?=
    WorkflowTransitionButtonsWidget::widget([
        'form' => $form,
        'model' => $model,
        'workflowId' => Ticket::TICKET_WORKFLOW,
        'viewWidgetOnNewRecord' => true,
        //'closeSaveButtonWidget' => CloseSaveButtonWidget::widget($config),
        'closeButton' => Html::a(AmosTicket::t('amosticket', 'Annulla'), Yii::$app->session->get('previousUrl'), ['class' => 'btn btn-secondary']),
        //'initialStatusName' => "PROCESSING",
        //'initialStatus' => Ticket::TICKET_WORKFLOW_STATUS_PROCESSING,
        'initialStatusName' => "WAITING",
        'initialStatus' => Ticket::TICKET_WORKFLOW_STATUS_WAITING,
        'draftButtons' => [
            /*             * **
              Ticket::TICKET_WORKFLOW_STATUS_CLOSED => [
              'button' => Html::submitButton(AmosTicket::t('amosticket', 'Salvaoooooo'), ['class' => 'btn btn-workflow']),
              'description' => 'le modifiche al ticket; verrà presto preso in carico'
              ],** */
            'default' => [
                'button' => Html::submitButton(AmosTicket::t('amosticket', 'Inoltra il ticket'), ['class' => 'btn btn-workflow']),
                'description' => AmosTicket::t('amosticket', 'questo viene chiuso e se ne apre un altro'),
            ]
        ]
    ]);
    ?>

    <?php ActiveForm::end(); ?>
</div>
