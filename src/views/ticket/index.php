<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\ticket\views\ticket
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use open20\amos\ticket\AmosTicket;
use open20\amos\ticket\models\Ticket;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\ticket\models\search\TicketSearch $model
 */

$columnsGrid = [
    'id',
    'titolo:ntext',
    'status' => [
        'attribute' => 'status',
        'value' => function ($model) {
            /** @var Ticket $model */
            return $model->getWorkflowStatusLabel();
        }
    ],
    'created_by' => [
        'attribute' => 'createdUserProfile',
        'label' => AmosTicket::t('amosticket', 'Aperto Da'),
        'value' => function ($model) {
            /** @var Ticket $model */
            $createdUserProfile = $model->createdUserProfile;
            return Html::a($createdUserProfile->nomeCognome, $createdUserProfile->getFullViewUrl(), [
                'title' => AmosTicket::t('amosticket', 'Apri il profilo di {nome_profilo}', ['nome_profilo' => $createdUserProfile->nomeCognome])
            ]);
        },
        'format' => 'html'
    ],
    'created_at' => [
        'label' => AmosTicket::t('amosticket', 'Aperto il'),
        'value' => function ($model) {
            /** @var Ticket $model */
            return Yii::$app->formatter->asDatetime($model->created_at, 'humanalwaysdatetime');
        }
    ],

    'partnership_id' => [
        'attribute' => 'partnership_id',
        'label' => AmosTicket::t('amosticket', 'Relativo alla sede'),
        'value' => function ($model) {
            /** @var Ticket $model */
            $partnership_principale = $model->partnership;
            if (!is_null($partnership_principale)) {
                return $partnership_principale->name;
            } else {
                return '---';
            }
        }
    ],

    /** **
     * 'closed_by' => [
     * 'attribute' => 'closed_by',
     * ],
     ** */
    'closed_by' => [
        'label' => AmosTicket::t('amosticket', 'Chiuso Da'),
        'value' => function ($model) {
            /** @var Ticket $model */
            $closedUserProfile = $model->closedUserProfile;
            if ($closedUserProfile) {
                return Html::a($closedUserProfile->nomeCognome, $closedUserProfile->getFullViewUrl(), [
                    'title' => AmosTicket::t('amosticket', 'Apri il profilo di {nome_profilo}', ['nome_profilo' => $closedUserProfile->nomeCognome])
                ]);
            } else {
                return '--';
            }
        },
        'format' => 'html'
    ],
    'closed_at' => [
        'attribute' => 'closed_at',
        'value' => function ($model) {
            /** @var Ticket $model */
            return Yii::$app->formatter->asDatetime($model->closed_at, 'humanalwaysdatetime');
        }
    ],
    'ticketCategoria.nomeCompleto',
    'action' => [
        'class' => 'open20\amos\core\views\grid\ActionColumn',
    ],
];
$hideColumns = (isset($this->params['hideColumns'])) ? $this->params['hideColumns'] : null;
if (!is_null($hideColumns) && is_array($hideColumns)) {
    foreach ($hideColumns as $c) {
        if (isset($columnsGrid[$c])) {
            unset($columnsGrid[$c]);
        }
    }
}
?>
<div class="ticket-index">
    <?php echo $this->render('_search', ['model' => $model]); ?>
    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'createNewBtnParams' => null,
        'gridView' => [
            'columns' => $columnsGrid,
            'enableExport' => Yii::$app->user->can('EXPORT_TICKETS'),
        ]
    ]); ?>
</div>
