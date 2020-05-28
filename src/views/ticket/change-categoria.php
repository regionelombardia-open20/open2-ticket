<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\ticket\views\ticket
 * @category   CategoryName
 */

use open20\amos\ticket\AmosTicket;

/**
 * @var yii\web\View $this
 * @var open20\amos\ticket\models\Ticket $model
 * @var open20\amos\ticket\models\Ticket $model_old_ticket
 */

$this->title = AmosTicket::t('amosticket', 'Inoltra ad altra categoria');
$this->params['breadcrumbs'][] = ['label' => Yii::t('cruds', 'Ticket'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-change_categoria">
    <?= $this->render('_form_change_categoria', [
        'model' => $model,
        'model_old_ticket' => $model_old_ticket
    ]) ?>
</div>
