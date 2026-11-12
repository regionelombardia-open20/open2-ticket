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

use open20\amos\core\forms\AccordionWidget;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\forms\TextEditorWidget;
use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\utility\TicketUtility;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var open2\amos\ticket\models\TicketFaq $model
 * @var yii\widgets\ActiveForm $form
 */

?>

<div class="ticket-faq-form col-xs-12 nop">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'ticket_categoria_id')->widget(Select::className(), [
        'auto_fill' => false,
        'options' => [
            'placeholder' => AmosTicket::t('amosticket', '#ticket_category_field_placeholder'),
            'id' => 'ticket_categoria_id-id',
            'disabled' => false,
            'value' => $model->ticket_categoria_id
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'data' =>
            ArrayHelper::map(TicketUtility::getTicketCategories(null, false)
                ->orderBy('titolo')->all(), 'id', 'nomeCompleto'),
    ]); ?>
	<div class="row">
		<div class="col-lg-12 col-sm-12">
            <?=
            $form->field($model, 'domanda')->widget(
                TextEditorWidget::className(),
                [
                    'options' => ['placeholder' => AmosTicket::t('amosticket', 'Inserisci...')],
                    'clientOptions' => [
                        'lang' => substr(Yii::$app->language, 0, 2),
                        /*'plugins' => $rtePlugins,
                        'toolbar' => $rteToolbar,*/
                        'buttonsHide' => [
                            'file'
                        ],
                    ],
                ]
            )
            ?>

		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-sm-12">
            <?=
            $form->field($model, 'risposta')->widget(
                TextEditorWidget::className(),
                [
                    'options' => ['placeholder' => AmosTicket::t('amosticket', 'Inserisci...')],
                    'clientOptions' => [
                        'lang' => substr(Yii::$app->language, 0, 2),
                        /* 'plugins' => $rtePlugins,
                         'toolbar' => $rteToolbar,*/
                        'buttonsHide' => [
                            'file'
                        ],
                    ],
                ]
            )
            ?>

		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-sm-12">
            <?php
            $moduleSeo = \Yii::$app->getModule('seo');
            if (isset($moduleSeo)) :
                ?>
                <?=
                AccordionWidget::widget([
                    'items' => [
                        [
                            'header' => AmosTicket::t('amosticket', '#settings_seo_title'),
                            'content' => \open20\amos\seo\widgets\SeoWidget::widget([
                                'contentModel' => $model,
                            ]),
                        ]
                    ],
                    'headerOptions' => ['tag' => 'h2'],
                    'options' => Yii::$app->user->can('ADMIN') ? [] : ['style' => 'display:none;'],
                    'clientOptions' => [
                        'collapsible' => true,
                        'active' => 'false',
                        'icons' => [
                            'header' => 'ui-icon-amos am am-plus-square',
                            'activeHeader' => 'ui-icon-amos am am-minus-square',
                        ]
                    ],
                ]);
                ?>
            <?php endif; ?>
		</div>
	</div>
	<div class="clearfix"></div>
    <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>
    <?php ActiveForm::end(); ?>
</div>
