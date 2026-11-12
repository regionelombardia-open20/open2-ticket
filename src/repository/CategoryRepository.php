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

use open2\amos\ticket\models\TicketCategorie;
use open20\attributes\annotations\Autowire;
use yii\helpers\VarDumper;

class CategoryRepository
{
    #[Autowire(FaqRepository::class)]
    public ?FaqRepository $faqRepository;

    private $whiteListAttributes = [
        'id', 'titolo', 'descrizione', 'abilita_ticket', 'categoria_padre_id'
        ];

    public function getCategoryStructure($onlyCategories = false)
    {
        $allFathers = TicketCategorie::find()->andWhere(['categoria_padre_id' => null])->orderBy('titolo')->asArray()->all();
        $categoriesArray = [];
        foreach ($allFathers as $father) {
            $categoriesArray[] = $this->getChildren($father, $onlyCategories);
        }
        return $categoriesArray;
    }

    private function getChildren($category, $onlyCategories)
    {
        $children = TicketCategorie::find()->andWhere(['categoria_padre_id' => $category['id']])->orderBy('titolo')->asArray()->all();
        $childrenArray = [];
        foreach ($children as $child) {
            $childrenArray[] = $this->getChildren($child, $onlyCategories);
        }
        if (count($childrenArray) > 0) {
            if ($onlyCategories) {
                return [
                    'categoria' => $this->whiteListAttributes($category),
                    'sotto-categorie' => $childrenArray
                ];
            }
            return [
                'categoria' => $this->whiteListAttributes($category),
                'faq-list' => $this->faqRepository->getFaqsByCategory($category['id']),
                'sotto-categorie' => $childrenArray
            ];
        } else {
            if ($onlyCategories) {
                return [
                    'categoria' => $this->whiteListAttributes($category),
                    'sotto-categorie' => $childrenArray
                ];
            }
            return [
                'categoria' => $this->whiteListAttributes($category),
                'faq-list' =>  $this->faqRepository->getFaqsByCategory($category['id']),
            ];
        }
    }


    private function whiteListAttributes($category)
    {
        return array_intersect_key($category, array_flip($this->whiteListAttributes));
    }
}