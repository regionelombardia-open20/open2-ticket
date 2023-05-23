<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\views\ticket-categorie
 * @category   CategoryName
 */

use open20\amos\attachments\components\AttachmentsInput;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\core\helpers\Html;
use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\utility\TicketUtility;
use yii\helpers\ArrayHelper;
use yii\web\View;

/**
 * @var yii\web\View $this
 * @var open2\amos\ticket\models\TicketCategorie $model
 * @var yii\widgets\ActiveForm $form
 */

$tecnicaFieldName = Html::getInputName($model, 'tecnica');

/** @var AmosTicket $module */
$module = \Yii::$app->getModule('ticket');

$js = <<<JS

function showHideTechnicalAssistanceDescription() {
    if ($("input:radio[name='$tecnicaFieldName']:checked").val() === "1") {
        $('#technical-assistance-description-container-id').show();
    } else {
        $('#technical-assistance-description-container-id').hide();
    }
}

showHideTechnicalAssistanceDescription();

$("input:radio[name='$tecnicaFieldName']").change(function() {
    showHideTechnicalAssistanceDescription();
});

JS;
$this->registerJs($js, View::POS_READY);

$categoryReferentsHide = (!empty($module) && is_array($module->categoryReferentsHide))? $module->categoryReferentsHide: [];
$fielsdToHide = (!empty($module) && is_array($module->categoryFieldsHide))? $module->categoryFieldsHide: [];
?>

<div class="news-categorie-form">
    <?php
    $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'] // important
    ]);
    ?>
    <div class="row">
        <?php
        if (!in_array('titolo', $fielsdToHide)):
        ?>
        <div class="col-sm-12">
            <?= $form->field($model, 'titolo')->textInput(['maxlength' => true]) ?>
        </div>
        <?php
        endif;
        ?>
        <?php
        if ($module->enableCategoryIcon){
            ?>
            <div class="col-sm-12">
                <div>
                    <?= $form->field($model, 'categoryIcon')->widget(AttachmentsInput::classname(), [
                        'options' => [// Options of the Kartik's FileInput widget
                            'multiple' => false, // If you want to allow multiple upload, default to false
                            'accept' => "image/*"
                        ],
                        'pluginOptions' => [// Plugin options of the Kartik's FileInput widget
                            'maxFileCount' => 1,
                            'showRemove' => false, // Client max files,
                            'indicatorNew' => false,
                            'allowedPreviewTypes' => ['image'],
                            'previewFileIconSettings' => false,
                            'overwriteInitial' => false,
                            'layoutTemplates' => false
                        ]
                    ]) ?>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <div class="row">
        <?php
        if (!in_array('descrizione', $fielsdToHide)):
        ?>
        <div class="col-lg-12 col-sm-12">
            <?= $form->field($model, 'descrizione')->widget(\yii\redactor\widgets\Redactor::className(), [
                'clientOptions' => [
                    'buttonsHide' => [
                        'image',
                        'file'
                    ],
                    'lang' => substr(Yii::$app->language, 0, 2)
                ]
            ]) ?>
        </div>
        <?php
        endif;
        ?>

        <?php
        if (!$module->oneLevelCategories) {
            ?>
            <div class="col-lg-12 col-sm-12">
                <?php
                $fatherCategoryId = $model->categoria_padre_id;
                ?>
                <?= $form->field($model, 'categoria_padre_id')->widget(Select::className(), [
                    'auto_fill' => false,
                    'options' => [
                        'placeholder' => AmosTicket::t('amosticket', '#father_category_field_placeholder'),
                        'id' => 'categoria_padre_id-id',
                        'disabled' => false,
                        'value' => $fatherCategoryId
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'data' =>
                        ArrayHelper::map(TicketUtility::getTicketCategories($model)
                            ->orderBy('titolo')->all(),
                            'id', 'nomeCompleto'),
                ]); ?>
            </div>
            <?php
        }
        ?>
    </div>
    <div class="row">

        <?php
        if (!in_array('attiva', $fielsdToHide)):
        ?>
        <?= Html::tag('div',
            $form->field($model, 'attiva')->inline()->radioList(
                TicketUtility::getBooleanFieldsValues(true),
                ['class' => 'comment-choice']),
            ['class' => 'col-md-4 col-xs-12']);
        ?>
        <?php
        endif;
        ?>

        <?php
        if (!in_array('abilita_ticket', $fielsdToHide)):
        ?>
        <?= Html::tag('div',
            $form->field($model, 'abilita_ticket')->inline()->radioList(
                TicketUtility::getBooleanFieldsValues(true),
                ['class' => 'comment-choice']),
            ['class' => 'col-md-4 col-xs-12']);
        ?>
        <?php
        endif;
        ?>

    </div>

    <?php
    if (!in_array('tecnica', $fielsdToHide)):
    ?>
    <div class="row">
        <?= Html::tag('div',
            $form->field($model, 'tecnica')->inline()->radioList(
                TicketUtility::getBooleanFieldsValues(true),
                ['class' => 'comment-choice']),
            ['class' => 'col-md-4 col-xs-12']);
        ?>
        <div class="col-lg-6 col-sm-6">
            <?= $form->field($model, 'email_tecnica')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row" id="technical-assistance-description-container-id">
        <div class="col-xs-12">
            <?= $form->field($model, 'technical_assistance_description')->textarea(['rows' => 5]) ?>
        </div>
    </div>
    <?php
    endif;
    ?>
    <div class="row">
        <?php
        if (!in_array('enable_dossier_id', $fielsdToHide)):
        ?>
        <?= Html::tag('div',
            $form->field($model, 'enable_dossier_id')->inline()->radioList(
                TicketUtility::getBooleanFieldsValues(true),
                ['class' => 'comment-choice']),
            ['class' => 'col-md-4 col-xs-12']);
        ?>
        <?php
        endif;
        ?>
        <?php
        if (!in_array('enable_phone', $fielsdToHide)):
        ?>
        <?= Html::tag('div',
            $form->field($model, 'enable_phone')->inline()->radioList(
                TicketUtility::getBooleanFieldsValues(true),
                ['class' => 'comment-choice']),
            ['class' => 'col-md-4 col-xs-12']);
        ?>
        <?php
        endif;
        ?>
    </div>

    <?php if (!empty($community)) : ?>
        <?php
        $this->registerCss(<<<CSS
        .disabled-field {
            pointer-events:none;
        }
CSS
        );
        $this->registerJs(<<<JS
            $(".field-ticketcategorie-abilita_per_community").addClass("disabled-field");
JS
        );
        ?>
        <div class="row">
            <?php
            if (!in_array('abilita_per_community', $fielsdToHide)):
            ?>
            <div class="col-lg-12 col-sm-12">
                <?=
                $form->field($model, 'abilita_per_community')->checkbox()->label(AmosTicket::t('amosticket', '#is_category_for_community', ['communityName' => $community->name]));
                ?>
            </div>
            <?php
            endif;
            ?>
        </div>
    <?php endif; ?>

    <?php
    if ($categoryReferentsHide):
    ?>
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <?php
            /**
             */
            echo \softark\duallistbox\DualListbox::widget([
                'model' => $model_referenti,
                'name' => 'ids',
                'attribute' => 'ids',
                'items' => $referenti,
                'options' => [
                    'multiple' => true,
                    'size' => 25,
                ],
                'clientOptions' => [
                    'nonSelectedListLabel' => \Yii::t('app', 'utenti'),
                    'selectedListLabel' => AmosTicket::t('amosticket', 'Referenti di questa categoria'),
                    'moveOnSelect' => true,
                    'moveAllLabel' => \Yii::t('app', 'aggiungi tutti'),
                    'removeAllLabel' => \Yii::t('app', 'rimuovi tutti'),
                    'filterTextClear' => \Yii::t('app', 'mostra tutti'),
                    'filterPlaceHolder' => \Yii::t('app', 'filtro'),
                    'infoTextFiltered' => '<span class="label label-warning">' . \Yii::t('app', 'filtro') . '</span> {0} ' . \Yii::t('app', 'di') . ' {1}',
                    'infoText' => \Yii::t('app', 'elementi totali') . ' {0}',
                    'infoTextEmpty' => \Yii::t('app', 'nessun elemento'),
                ],
            ]);
            ?>
        </div>
    </div>
    <?php
    endif;
    ?>

    <?= RequiredFieldsTipWidget::widget() ?>
    <?= CreatedUpdatedWidget::widget(['model' => $model]) ?>
    <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>
    <?php ActiveForm::end(); ?>
</div>
