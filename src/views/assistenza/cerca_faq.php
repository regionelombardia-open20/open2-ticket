<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open2\amos\ticket\views\assistenza
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

use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\assets\TicketAsset;
use open2\amos\ticket\models\TicketCategorie;
use yii\helpers\Html;
use open20\amos\core\icons\AmosIcons;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open2\amos\ticket\models\search\TicketCategorieSearch $searchModel
 * @var array $ticketCategorieArray
 */

TicketAsset::register($this);

$this->title = AmosTicket::t('amosticket', 'Faq');
$this->params['breadcrumbs'][] = ['label' => $this->title];

$mutualClose = false;

// faqId è utilizzato dalla ricerca faq in ES
$autoOpenFaqId = '';
$autoOpenFaqCat = '';
if(!is_null(\Yii::$app->request->get('faqId'))) {
    $autoOpenFaqId = \Yii::$app->request->get('faqId');
    $autoOpenFaqCat = \Yii::$app->request->get('categoriaSelezionataId');
}

/** @var AmosTicket $ticketModule */
$ticketModule = AmosTicket::instance();

if($ticketModule->enableFaqRatingSystem) {
    $js = <<<JS
        $(document).on('ready', function() {
            let faqId = '{$autoOpenFaqId}';
            let faqCat = '{$autoOpenFaqCat}';
            if(faqId != '') {
                let faqResponse = $('#bodyFaq' + faqId);
                faqResponse.collapse('show');
                
                let faq = $('#faqs' + faqId + 'Cat' + faqCat);
                $('html, body').animate({
                    scrollTop: faq.offset().top - $('#headerContent').height()
                }, 1000);
            }
        });
        
        /* Per modificare le classi delle stelle */
        $(document).on('mouseenter', '[id^="container-stars"] span', function(){
           var spanPrecedenti = $(this).prevAll('span');
           var spanSuccessivi = $(this).nextAll('span');
           spanPrecedenti.removeClass('am-star-outline');
           spanPrecedenti.addClass('am-star');
           spanSuccessivi.removeClass('am-star');
           spanSuccessivi.addClass('am-star-outline');
           $(this).addClass('am-star');
           $(this).removeClass('am-star-outline');
        });
    
        /* Invio valutazione utente */
        $(document).on('click', '[id^="container-stars"] span', function(){
           var starValue = 1 + $(this).prevAll('span').length;
           var faqId = $(this).parent().attr('data-faqid');
           
           $.ajax({
                url: '/ticket/assistenza/ajax-faq-star-rating',
                type: 'post',
                dataType: 'json',
                data: {
                    'faqId' : faqId,
                    'starValue' : starValue
                },
                success: function (response) {
                    console.log(response);
                },
                error: function (response) {
                    console.log(response);
                }
            });
        });
JS;
    $this->registerJs($js);
}
?>

<div class="cerca-faq-view">
    <div class="search-elastic">
        <?php if($ticketModule->enableFaqEsSearch) : ?>
            <?= $this->render('parts/_searchElastic'); ?>
        <?php endif; ?>
    </div>
    <div class="row">
    


        <div class="col-xs-12">
            <?php foreach ($ticketCategorieArray as $ticketCategorieLivello) : ?>
                <?php
                $k = 0;
                ?>
                <?php if ($k == 0 && !is_null($ticketCategorieLivello[$k]->categoriaPadre)) : ?>
                    <div class="faq-category-div">
                        <?php /*echo (!is_null($ticketCategorieLivello[$k]->categoriaPadre) ? $ticketCategorieLivello[$k]->categoriaPadre->titolo : '')*/ ?>
                    </div>
                <?php endif; ?>
                <ul class="faq-category-list">
                    <?php foreach ($ticketCategorieLivello as $cat) : ?>
                        <?php /** @var TicketCategorie $cat */ ?>
                        <li class="faq-category-item <?= ($cat->selected) ? 'faq-category-selected' : ''; ?>">
                            <a class="faq-category-link" href="?categoriaSelezionataId=<?= $cat->id ?>#<?=str_replace(' ', '', $cat->titolo);?>" title="<?= AmosTicket::t('amosticket', 'Clicca per navigare la categoria {nomeCat}', ['nomeCat' => $cat->titolo]) ?>">
                                <div>
                                    <div class="image">
                                        <?php
                                        $url = $cat->getCategoryIconUrl('item_community', false);
                                        $contentImage = Html::img($url, [
                                            'class' => 'img-responsive',
                                            'alt' => AmosTicket::t('amosticket', 'Immagine della categoria di faq {nameCat}', ['nameCat' => $cat->titolo])
                                        ]);
                                        echo $contentImage;
                                        ?>
                                    </div>
                                    <div class="title">
                                        <?php if (!is_null($cat->categoriaPadre)) : ?>
                                            <?= Html::tag('small', (!is_null($cat->categoriaPadre) ? $cat->categoriaPadre->titolo : '')) ?>
                                        <?php endif; ?>
                                        <?= Html::tag('p', $cat->titolo, ['class' => 'text-uppercase title-category-faq']) ?>
                                    </div>
                                </div>
                                <div>
                                    <?= $cat->descrizione ?>
                                </div>
                            </a>
                            <?php $k++; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endforeach; ?>
        </div>

        <?php if (!empty($categoriaSelezionata)) : ?>
            <?php
            /** @var TicketCategorie $categoriaSelezionata */
            $faqs = $categoriaSelezionata->ticketFaq;
            ?>
            <?php if (count($faqs) > 0) : ?>
                <div class="col-xs-12">
                    <div id="<?=str_replace(' ', '', $categoriaSelezionata->titolo);?>" class="image-and-title-category m-b-30">
                        <?php
                        $url = $categoriaSelezionata->getCategoryIconUrl('item_community', false);
                        $contentImage = Html::img($url, [
                            'class' => 'img-responsive',
                            'alt' => AmosTicket::t('amosticket', 'Immagine della categoria di faq {nameCat}', ['nameCat' => $categoriaSelezionata->titolo])
                        ]);
                        echo $contentImage;
                        ?>
                        <?= Html::tag('strong', AmosTicket::t('amosticket', 'Lista FAQ') . ' ' . $categoriaSelezionata->titolo, ['class' => 'h3']) ?>
                    </div>
                    <?php foreach ($faqs as $faq) : ?>
                        <div class="faq-list panel-group" id="faqs<?= $faq->id ?>Cat<?= $categoriaSelezionata->id ?>" role="tablist" aria-multiselectable="true">

                            <div class="faq-list-item panel panel-default faq<?= $faq->id ?>">
                                <div class="panel-heading" role="tab" id="headerFaq<?= $faq->id ?>">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" <?= ($mutualClose) ? 'data-parent="faqs' . $faq->id . 'Cat' . $categoriaSelezionata->id . '"' : '' ?> href="#bodyFaq<?= $faq->id ?>" aria-expanded="false" aria-controls="bodyFaq<?= $faq->id ?>">
                                            <?= AmosIcons::show('question-circle-o', [], 'dash') ?><?= $faq->domanda ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="bodyFaq<?= $faq->id ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headerFaq<?= $faq->id ?>">
                                    <div class="panel-body">
                                        <?= $faq->risposta ?>
                                    </div>
                                    <?php if($ticketModule->enableFaqRatingSystem) : ?>
                                        <div class="panel-body">
                                            <?= $this->render('parts/_ratingSystemFaq', ['model' => $faq]) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>
                    <?php endforeach; ?>
                    
                </div>
            <?php endif; ?>
            <?php if ($categoriaSelezionata->abilita_ticket) : ?>

                <?php if (!$categoriaSelezionata->ticketFaq > 0) : ?>
                    <div class="col-xs-12">
                        <div class="callout callout-info">
                            <div class="callout-title">
                                <?= AmosIcons::show('info-outline') ?>
                            </div>
                            <p>
                                <?= AmosTicket::t('amosticket', 'Non sono presenti FAQ per la categoria selezionata {selectedCategory}', ['selectedCategory' => Html::tag('strong', $categoriaSelezionata->titolo)]) ?>
                            </p>
                        </div>
                    </div>
                <?php endif ?>
                <div class="col-xs-12 m-t-50">
                    <div class="callout callout-warning">
                        <div class="callout-title">
                            <?= AmosIcons::show('question-circle-o', [], 'dash') ?>
                        </div>
                        <p>
                            <?=
                            '<strong class="text-uppercase">' . AmosTicket::t('amosticket', 'Hai bisogno di ulteriore assistenza?') . '</strong>'
                                . ' ' .
                                Html::a(
                                    '<strong>' . AmosTicket::t('amosticket', 'Clicca qui') . '</strong>',
                                    [
                                        '/ticket/ticket/create',
                                        'categoriaId' => $categoriaSelezionata->id
                                    ],
                                    [
                                        'title' => AmosTicket::t('amosticket', 'Clicca qui per aprire un ticket e ricevere assistenza'),
                                        'target' => '_blank',
                                        'class' => ''
                                    ]
                                )
                                . ' ' .
                                AmosTicket::t('amosticket', 'per aprire un nuovo ticket e ricevere supporto!');
                            ?>
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>