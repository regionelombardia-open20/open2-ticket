<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\views\assistenza
 * @category   CategoryName
 */

use open20\amos\core\forms\AccordionWidget;
use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\assets\TicketAsset;
use open2\amos\ticket\models\TicketCategorie;
use open2\amos\ticket\models\TicketFaq;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open2\amos\ticket\models\search\TicketCategorieSearch $searchModel
 * @var array $ticketCategorieArray
 */
/* Pjax::begin([
  'id' => 'pjax-cercaFaq', // checked id on the inspect element
  //'options' => ['class' => 'pjax-container clearfix'],
  'enablePushState' => false, // I would like the browser to change link
  'timeout' => 60000,// Timeout needed
  'linkSelector' => '.js-linkcategoria'

  ]); */

TicketAsset::register($this);

$this->title = AmosTicket::t('amosticket', 'Faq');
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => '/ticket'];
//$this->params['breadcrumbs'][] = $this->title;

$this->registerCss(".categorySelected { background-color:#338079; }");
?>

<?php
/* Modal::begin([
    'id' => 'create-ticket',
    'header' => '<span id="modal-title"></span>',
]);
?>
<div>
    Attendere ....
</div>
<?php
Modal::end();
*/
/* ?>

<?=
Html::a("prova", null, [
    'data-toggle' => 'modal',
    'data-lesson-name' => $model["titolo"],
    'data-lesson-instance' => 7,
    'data-target' => '#lesson-info'
]);
*/ ?>
<div class=" assist-title-primary col-xs-12 text-center nop">
    <h1><?= AmosTicket::t('amosticket', 'How can we help you?') ?></h1>
</div>
<?php foreach ($ticketCategorieArray as $ticketCategorieLivello): ?>
    <?php
    $k = 0;
    ?>
    <div class="col-xs-12 assist-faq">
        <?php foreach ($ticketCategorieLivello as $cat): ?>
            <?php /** @var TicketCategorie $cat */ ?>
            <?php if ($k == 0 && !is_null($cat->categoriaPadre)): ?>
                <h2 class="assist-faq-title"> <?= (!is_null($cat->categoriaPadre) ? $cat->categoriaPadre->titolo : ''); ?></h2>
            <?php endif; ?>
            <div class="assist-faq-item <?= ($cat->selected) ? 'categorySelected' : ''; ?>">
                <a class="js-linkcategoria faq" href="?categoriaSelezionataId=<?= $cat->id ?>">
                    <?php
                    $url = $cat->getCategoryIconUrl();
                    $contentImage = Html::img($url, [
                        'class' => 'img-responsive faq-item_image',
                        'alt' => AmosTicket::t('amosticket', 'Immagine della categoria')
                    ]);
                    ?>
                    <?= $contentImage; ?>
                    <div class="faq-item_title">
                        <?= Html::tag('h3', $cat->titolo) ?>
                    </div>
                    <?= $cat->descrizione ?>
                </a>
            </div>
            <?php $k++; ?>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>

<?php if (!empty($categoriaSelezionata)): ?>
    <?php
    /** @var TicketCategorie $categoriaSelezionata */
    $faqs = $categoriaSelezionata->ticketFaq;
    ?>
    <?php if (count($faqs) > 0): ?>
        <div class="col-xs-12 faq-content">
            <h1 class="text-center"><?= AmosTicket::t('amosticket', 'Frequent questions') ?></h1>
            <?php foreach ($faqs as $faq): ?>
                <?php
                /** @var TicketFaq $faq */
                $divFaq = Html::tag('div', $faq->risposta, ['class' => 'col-xs-12 nop']);
                ?>
                <?= AccordionWidget::widget([
                    'items' => [
                        [
                            'header' => $faq->domanda,
                            'content' => $divFaq . '<div class="clearfix"></div>',
                        ]
                    ],
                    'headerOptions' => ['tag' => 'h2'],
                    'clientOptions' => [
                        'collapsible' => true,
                        'active' => 'false',
                        'icons' => [
                            'header' => 'ui-icon-amos am am-plus-square',
                            'activeHeader' => 'ui-icon-amos am am-minus-square',
                        ]
                    ],
                ]); ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if ($categoriaSelezionata->abilita_ticket): ?>
        <div class="col-xs-12 btn-tools-container m-t-30 text-center">
            <h2>
                <?= Html::a(AmosTicket::t('amosticket', 'Hai bisogno di ulteriore assistenza?'),
                    ['/ticket/ticket/create', 'categoriaId' => $categoriaSelezionata->id])
                ?>
                <?php
                /* = Html::a(AmosTicket::t('amosticket', 'Hai bisogno di ulteriore assistenza?'), null, ['style' => 'font-size:18px',
                     'class' => 'btn btn-administration-primary',
                     'data-toggle' => 'modal',
                     'data-category-name' => $categoriaSelezionata["titolo"],
                     'data-category-instance' => $categoriaSelezionata->id,
                     'data-target' => '#create-ticket'
                 ])*/
                ?>
            </h2>
        </div>
    <?php endif; ?>
<?php endif; ?>
