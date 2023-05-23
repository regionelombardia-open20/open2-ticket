<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\views\ticket-categorie
 * @category   CategoryName
 */

use open20\amos\core\views\AmosGridView;
use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\models\TicketCategorie;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open2\amos\ticket\models\search\TicketCategorieSearch $searchModel
 */

$this->title = AmosTicket::t('amosticket', 'Categorie');
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => '/ticket/ticket-categorie/index'];
//$this->params['breadcrumbs'][] = $this->title;

?>
<div class="news-categorie-index">
    <?php echo $this->render('_search', ['model' => $model]); ?>
    <?php
    echo AmosGridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'columns' => [
            [
                'label' => $model->getAttributeLabel('categoryIcon'),
                'format' => 'html',
                'value' => function ($model) {
                    /** @var TicketCategorie $model */
                    $url = $model->getCategoryIconUrl();
                    $contentImage = Html::img($url, ['class' => 'gridview-image', 'alt' => $model->getAttributeLabel('categoryIcon')]);
                    return $contentImage;
                }
            ],
            'titolo',
            'descrizione:html',
            'categoria_padre_id' => [
                'attribute' => 'categoriaPadre.nomeCompleto',
                'label' => AmosTicket::t('amosticket', 'Categoria padre')
            ],
            'tecnica:boolean',
            [
                'class' => 'open20\amos\core\views\grid\ActionColumn'
            ]
        ]
    ]);
    ?>
</div>
