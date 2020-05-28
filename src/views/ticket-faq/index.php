<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\ticket\views\ticket-faq
 * @category   CategoryName
 */

use open20\amos\core\views\DataProviderView;
use open20\amos\ticket\AmosTicket;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\ticket\models\search\TicketFaqSearch $model
 */

$this->title = AmosTicket::t('amosticket', 'Gestione Faq');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-faq-index">
    <?php echo $this->render('_search', ['model' => $model]); ?>
    <?php
    echo DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                //  ['class' => 'yii\grid\SerialColumn'],
                //           'id',

                'ticket_categoria_id' => [
                    'attribute' => 'ticketCategoria.nomeCompleto',
                    'label' => AmosTicket::t('amosticket', 'Categoria')
                ],
                'domanda:html',
                'risposta:html',

                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                ],
            ],
        ],
    ]);
    ?>
</div>
