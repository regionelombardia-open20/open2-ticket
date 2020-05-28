<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\ticket\i18n\grammar
 * @category   CategoryName
 */

namespace open20\amos\ticket\i18n\grammar;

use open20\amos\core\interfaces\ModelGrammarInterface;
use open20\amos\ticket\AmosTicket;

/**
 * Class TicketGrammar
 * @package open20\amos\ticket\i18n\grammar
 */
class TicketGrammar implements ModelGrammarInterface
{
    /**
     * @return string
     */
    public function getModelSingularLabel()
    {
        return AmosTicket::t('amosticket', '#ticket_singular');
    }

    /**
     * @inheritdoc
     */
    public function getModelLabel()
    {
        return AmosTicket::t('amosticket', '#ticket_plural');
    }

    /**
     * @return mixed
     */
    public function getArticleSingular()
    {
        return AmosTicket::t('amosticket', '#article_singular');
    }

    /**
     * @return mixed
     */
    public function getArticlePlural()
    {
        return AmosTicket::t('amosticket', '#article_plural');
    }

    /**
     * @return string
     */
    public function getIndefiniteArticle()
    {
        return AmosTicket::t('amosticket', '#article_indefinite');
    }
}
