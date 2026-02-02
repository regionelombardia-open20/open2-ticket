<?php

use yii\helpers\Html;
use open2\amos\ticket\AmosTicket;
use open20\amos\core\icons\AmosIcons;

/** @var \open2\amos\ticket\models\TicketFaq $model */
?>
<div class="faq-evaluation-container flexbox flex-wrap">

    <div><?= AmosTicket::t('amosticket', '#faq_usefullness_question') ?></div>
  
    <div>
        <?= AmosTicket::t('amosticket', '#make_faq_evaluation') ?>
    </div>
    <!-- la div deve contenere solo le stelle (span), questione di jquery in 'cerca_faq.php' -->
    <div id="container-stars-<?= $model->id ?>" data-faqid="<?= $model->id ?>">
        <?= AmosIcons::show('star-outline', ['class' => 'star-rating']) ?>
        <?= AmosIcons::show('star-outline', ['class' => 'star-rating']) ?>
        <?= AmosIcons::show('star-outline', ['class' => 'star-rating']) ?>
        <?= AmosIcons::show('star-outline', ['class' => 'star-rating']) ?>
        <?= AmosIcons::show('star-outline', ['class' => 'star-rating']) ?>
    </div>
      <!-- utente di redazione -->
      <?php if(Yii::$app->user->can('SUPERUSER')) : ?>
        <div class="w-100">
            <?= AmosTicket::t('amosticket', '#average_faq_rating') . ' ' . $model->starValutation ?>
        </div>
    <?php endif; ?>
</div>


