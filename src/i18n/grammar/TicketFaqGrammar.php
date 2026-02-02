<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\i18n\grammar
 * @category   CategoryName
 */

namespace open2\amos\ticket\i18n\grammar;

use open20\amos\core\interfaces\ModelGrammarInterface;
use open2\amos\ticket\AmosTicket;

/**
 * Class TicketGrammar
 * @package open2\amos\ticket\i18n\grammar
 */
class TicketFaqGrammar implements ModelGrammarInterface
{
    /**
     * @return string
     */
    public function getModelSingularLabel()
    {
        return AmosTicket::t('amosticket', '#ticket_faq_singular');
    }

    /**
     * @inheritdoc
     */
    public function getModelLabel()
    {
        return AmosTicket::t('amosticket', '#ticket_faq_plural');
    }

    /**
     * @return mixed
     */
    public function getArticleSingular()
    {
        return AmosTicket::t('amosticket', '#article_faq_singular');
    }

    /**
     * @return mixed
     */
    public function getArticlePlural()
    {
        return AmosTicket::t('amosticket', '#article_faq_plural');
    }

    /**
     * @return string
     */
    public function getIndefiniteArticle()
    {
        return AmosTicket::t('amosticket', '#article_faq_indefinite');
    }
}
