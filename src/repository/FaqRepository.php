<?php

namespace open2\amos\ticket\repository;

use open2\amos\ticket\models\TicketFaq;
use yii\helpers\VarDumper;

class FaqRepository
{

    private $whiteListAttributes = [
        'id', 'domanda', 'risposta', 'ticket_categoria_id'
    ];

    /**
     * Retrieves the FAQ list for a specific category.
     *
     * @param int $categoryId The ID of the category.
     * @return array The FAQ list with only the white-listed attributes.
     */
    public function getFaqsByCategory($categoryId)
    {
        $faqList = TicketFaq::find()->andWhere(['ticket_categoria_id' => $categoryId])->orderBy('domanda')->asArray()->All();
        return $this->whiteListAttributes($faqList);
    }

    /**
     * Filters the FAQ list to include only the white-listed attributes.
     *
     * @param array $faqList The original FAQ list.
     * @return array The filtered FAQ list.
     */
    private function whiteListAttributes($faqList)
    {
        $toRet = [];
        foreach ($faqList as $faq) {
            $toRet[] = array_intersect_key($faq, array_flip($this->whiteListAttributes));
        }
        return $toRet;
    }

}