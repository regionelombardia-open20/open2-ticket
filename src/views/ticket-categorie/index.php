<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open2\amos\ticket\views\ticket-categorie
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

use open20\amos\core\views\AmosGridView;
use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\models\TicketCategorie;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open2\amos\ticket\models\search\TicketCategorieSearch $model
 */

$this->title = AmosTicket::t('amosticket', 'Categorie');
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => '/ticket/ticket-categorie/index'];
//$this->params['breadcrumbs'][] = $this->title;

/** @var AmosTicket $module */
$module = \Yii::$app->getModule('ticket');
$fielsdToHide = (!empty($module) && is_array($module->categoryFieldsHide)) ? $module->categoryFieldsHide : [];
?>
<div class="news-categorie-index">
    <?php echo $this->render('_search', ['model' => $model]); ?>
    <?php
    
    $columns = [];
    
    if ($module->enableCategoryIcon) {
        $columns[] = [
            'label' => $model->getAttributeLabel('categoryIcon'),
            'format' => 'raw',
            'value' => function ($model) {
                /** @var TicketCategorie $model */
                $url = $model->getCategoryIconUrl('table_small');
                $contentImage = Html::img($url, ['class' => 'gridview-image', 'alt' => $model->getAttributeLabel('categoryIcon')]);
                return $contentImage;
            }
        ];
    }
    
    $columns[] = 'titolo';
    
    $columns[] = 'descrizione:html';
    
    if (!$module->oneLevelCategories) {
        $columns['categoria_padre_id'] = [
            'attribute' => 'categoriaPadre.nomeCompleto',
            'label' => AmosTicket::t('amosticket', 'Categoria padre')
        ];
    }

    if (!in_array('tecnica', $fielsdToHide) || !in_array('administrative', $fielsdToHide)) {
        if ($module->enableAdministrativeTicketCategory && !in_array('tecnica', $fielsdToHide) && !in_array('administrative', $fielsdToHide)) {
            $columns[] = [
                'label' => AmosTicket::t('amosticket', '#category_type'),
                'value' => function ($model) {
                    /** @var TicketCategorie $model */
                    if ($model->tecnica) {
                        return AmosTicket::t('amosticket', '#external_technical');
                    } elseif ($model->administrative) {
                        return AmosTicket::t('amosticket', '#external_administrative');
                    } else {
                        return AmosTicket::t('amosticket', '#category_type_empty');
                    }
                }
            ];
        } elseif ($module->enableAdministrativeTicketCategory && in_array('tecnica', $fielsdToHide) && !in_array('administrative', $fielsdToHide)) {
            $columns[] = 'administrative:boolean';
        } elseif (!in_array('tecnica', $fielsdToHide)) {
            $columns[] = 'tecnica:boolean';
        }
    }
    
    $columns[] = [
        'class' => 'open20\amos\core\views\grid\ActionColumn'
    ];
    
    echo AmosGridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'columns' => $columns,
    ]);
    ?>
</div>
