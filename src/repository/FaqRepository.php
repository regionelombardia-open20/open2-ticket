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