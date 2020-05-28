<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\ticket\controllers
 * @category   CategoryName
 */

namespace open20\amos\ticket\controllers;

use open20\amos\core\controllers\CrudController;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use open20\amos\ticket\AmosTicket;
use open20\amos\ticket\models\search\TicketCategorieSearch;
use open20\amos\ticket\models\TicketCategorie;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class AssistenzaController
 *
 * @property \open20\amos\ticket\models\TicketCategorie $model
 * @property \open20\amos\ticket\models\search\TicketCategorieSearch $modelSearch
 *
 * @packageopen20\amos\ticket\controllers
 */
class AssistenzaController extends CrudController
{
    /**
     * Trait used for initialize the tab dashboard
     */
    use TabDashboardControllerTrait;

    /**
     * @var string $layout
     */
    public $layout = 'list';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'cerca-faq',
                        ],
                        'roles' => ['OPERATORE_TICKET']
                    ],
                ]
            ],
        ]);
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->initDashboardTrait();

        $this->setModelObj(new TicketCategorie());
        $this->setModelSearch(new TicketCategorieSearch());

        $this->setAvailableViews([
            /* 'grid' => [
                 'name' => 'grid',
                 'label' => Yii::t('amoscore', '{iconaTabella}' . Html::tag('p', Yii::t('amoscore', 'Table')), [
                     'iconaTabella' => AmosIcons::show('view-list-alt')
                 ]),
                 'url' => '?currentView=grid'
             ],*/
        ]);

        parent::init();

        $this->setUpLayout();
    }

    /**
     * @param int|null $categoriaSelezionataId
     * @return string
     */
    public function actionCercaFaq($categoriaSelezionataId = null)
    {
        Url::remember();
        Yii::$app->view->params['createNewBtnParams'] = [
            'layout' => '' // To always hide create new button.
        ];
        Yii::$app->session->set(AmosTicket::beginCreateNewSessionKey(), Url::previous());
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        $categoriaSelezionata = null;
        if ($categoriaSelezionataId) {
            $categoriaSelezionata = TicketCategorie::findOne($categoriaSelezionataId);
        }

        $ticketCategorieArray = $this->getFratelliConPadre($categoriaSelezionataId);
        $ticketCategorieArray = array_reverse($ticketCategorieArray);

        return $this->render('cerca_faq', [
            'ticketCategorieArray' => $ticketCategorieArray,
            'model' => $this->getModelSearch(),
            'categoriaSelezionata' => $categoriaSelezionata,
            'url' => ($this->url) ? $this->url : NULL,
            'parametro' => ($this->parametro) ? $this->parametro : NULL
        ]);
    }

    /**
     * Funzione ricorsiva che ritorna un array in cui ogni record contiene un array di
     * categorie "fratelli" e il record sucessivo contiene il padre e tutti i suoi fratelli.
     * @param int $catPadreId
     * @param int|null $catFiglioSelezionatoId
     * @return array
     */
    public function getFratelliConPadre($catPadreId, $catFiglioSelezionatoId = null)
    {
        $res = [];
        $catRicerca = new TicketCategorieSearch();
        $catRicerca->categoria_padre_id = $catPadreId;
        $tmpModels = $catRicerca->searchPerFaq(null)->getModels();
        foreach ($tmpModels as $cat) {
            if ($cat->id == $catFiglioSelezionatoId) {
                $cat->selected = true;
            }
        }
        $res[] = $tmpModels;
        if ($catPadreId) {
            $catPadre = TicketCategorie::findOne($catPadreId);
            $res = array_merge($res, $this->getFratelliConPadre($catPadre->categoria_padre_id, $catPadre->id));
        }
        return $res;
    }
}
