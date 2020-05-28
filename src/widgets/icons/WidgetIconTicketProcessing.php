<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\ticket\widgets\icons
 * @category   CategoryName
 */

namespace open20\amos\ticket\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use open20\amos\ticket\AmosTicket;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconTicketProcessing
 * @package open20\amos\ticket\widgets\icons
 */
class WidgetIconTicketProcessing extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosTicket::tHtml('amosticket', 'Ticket in corso'));
        $this->setDescription(AmosTicket::t('amosticket', 'Visualizza i ticket in corso'));
        $this->setIcon('feed');
        $this->setUrl(['/ticket/ticket/ticket-processing']);
        $this->setCode('TICKET_PROCESSING');
        $this->setModuleName('ticket');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                [
                    'bk-backgroundIcon',
                    'color-primary'
                ]
            )
        );
    }

    /**
     * Aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
     * 
     * @inheritdoc
     */
    public function getOptions()
    {
        return ArrayHelper::merge(
            parent::getOptions(),
            ['children' => []]
        );
    }

}
