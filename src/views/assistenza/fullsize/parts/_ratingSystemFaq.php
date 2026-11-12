<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open2\amos-ticket
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


