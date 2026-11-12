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

use yii\web\View;
use open2\amos\ticket\AmosTicket;

$js = <<<JS
    /* Redirect quando si preme invio */
    $("#search-elastic").on("keyup", function(event){
        if (event.keyCode === 13) {
            event.preventDefault();
            $(location). attr('href', '/ricerca-faq?searchtext=' + $( this ).val());
        }
    });
    
    /* Redirect quando si clicca sulla lente nella barra di ricerca */
    $("#search-submit").on("click", function(event){
        event.preventDefault();
        $(location). attr('href', '/ricerca-faq?searchtext=' + $( ("#search-elastic") ).val());
    });
JS;

$this->registerJs($js, \yii\web\View::POS_READY);

?>
<div class="elasticsearch-box">
    <div class="form-group">
        <label class="control-label"><?=AmosTicket::t('amosticket', 'Come possiamo aiutarti?')?></label>
        <div class="elasticsearch-box-container">
            <input id="search-elastic" type="search" class="form-control">
            <button id="search-submit" class="search-input-icon">
                <span class="am am-search"></span>
            </button>
        </div>
        <div class="hint-block"><?=AmosTicket::t('amosticket', 'Inserisci il testo da ricercare. Limite massimo 100 caratteri')?></div>
    </div>
</div>