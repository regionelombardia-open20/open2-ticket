<?php 
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