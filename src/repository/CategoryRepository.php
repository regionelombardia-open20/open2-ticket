<?php

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