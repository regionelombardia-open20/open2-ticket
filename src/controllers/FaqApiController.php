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

namespace open2\amos\ticket\controllers;

use open2\amos\ticket\repository\CategoryRepository;
use open2\amos\ticket\repository\FaqRepository;
use open20\attributes\annotations\Autowire;
use open20\attributes\annotations\Inject;
use open20\attributes\annotations\Route;
use open20\microfrontend\core\controllers\BearerRestController;
use open20\microfrontend\core\utilities\ArrayService;
use OpenApi\Attributes as OA;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\helpers\VarDumper;
use yii\log\Logger;
use yii\web\Response;


/**
 * @OA\Info(title="Open20 API", version="0.1")
 */
#[OA\Tag(name: "FaqApiController", description: "1.0")]
class FaqApiController extends BearerRestController
{
    #[Autowire(CategoryRepository::class)]
    public ?CategoryRepository $categoryRespository;

    #[Autowire(FaqRepository::class)]
    public ?FaqRepository $faqRepository;


    public function behaviors() {
        $behaviours = parent::behaviors();
        $behaviours = ArrayService::mergeRecursive($behaviours, [
            'authenticator' => [
                'except' => [
                    'categories-structure',
                    'faqs',
                ]
            ],
        ]);
        return $behaviours;
    }


    #[Route('GET', 'microservices/api/v1/ticket/categories-structure', '/ticket/faq-api/categories-structure')]
    #[OA\Get(path: 'microservices/api/v1/ticket/categories-structure', description: 'Returns the structure of the categories of the faq', tags: ['FaqApiController'])]
    #[OA\Response(response: '200', description: 'Returns the structure of the categories of the faq')]
    public function actionCategoriesStructure()
    {
        $toRet = [
            'status' => null,
            'message' => '',
            'data' => null,
        ];

        try {

            $onlyCategories = Yii::$app->request->get('onlyCategories');
            if (empty($onlyCategories) || $onlyCategories == '0') {
                $onlyCategories = false;
            } else {
                $onlyCategories = true;
            }

            $data = $this->categoryRespository->getCategoryStructure($onlyCategories);
            $toRet['status'] = 'OK';
            $toRet['data'] = $data;
        } catch (\Exception $e) {
            Yii::getLogger()->log($e->getMessage() ."\n". $e->getTraceAsString(), Logger::LEVEL_ERROR);
            $toRet['status'] = 'error';
            $toRet['message'] = 'Errore durante la recupero della struttura delle categorie';
            $toRet['data'] = [];
        }

        return $toRet;
    }

    #[Route('GET', 'microservices/api/v1/ticket/faqs', '/ticket/faq-api/faqs')]
    #[OA\Get(path: 'microservices/api/v1/ticket/faqs', description: 'Returns the FAQs of the specified category', tags: ['FaqApiController'])]
    #[OA\Parameter(name: 'categoryId', description: 'Id of the category', in: 'query', required: true, style: 'form')]
    #[OA\Response(response: '200', description: 'Returns the FAQs of the specified category')]
    public function actionFaqs()
    {
        $toRet = [
            'status' => null,
            'message' => '',
            'data' => null,
        ];

        try {
            $categoryId = Yii::$app->request->get('categoryId');
            if (empty($categoryId)) {
                $toRet['status'] = 'error';
                $toRet['message'] = 'È necessario specificare l\'id della categoria';
                $toRet['data'] = [];
            } else {
                $data = $this->faqRepository->getFaqsByCategory($categoryId);
                $toRet['status'] = 'OK';
                $toRet['data'] = $data;
            }
        } catch (\Exception $e) {
            $toRet['status'] = 'error';
            $toRet['message'] = 'Errore durante la ricerca delle FAQ';
            $toRet['data'] = [];
        }

        return $toRet;
    }

    
}