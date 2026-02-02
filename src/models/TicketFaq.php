<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\models
 * @category   CategoryName
 */

namespace open2\amos\ticket\models;

use open20\amos\core\behaviors\TaggableBehavior;
use open20\amos\core\interfaces\ContentModelInterface;
use open20\amos\seo\behaviors\SeoContentBehavior;
use open20\amos\seo\interfaces\SeoModelInterface;
use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\i18n\grammar\TicketFaqGrammar;
use open2\amos\ticket\widgets\icons\WidgetIconTicketDashboard;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class TicketFaq
 * This is the model class for table "ticket_faq".
 * @package open2\amos\ticket\models
 */
class TicketFaq extends \open2\amos\ticket\models\base\TicketFaq implements ContentModelInterface, SeoModelInterface
{
    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'domanda'
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'SeoContentBehavior' => [
                'class' => SeoContentBehavior::class,
                'titleAttribute' => 'domanda',
                'descriptionAttribute' => 'risposta',
                'imageAttribute' => null,
                'defaultOgType' => 'article',
                'schema' => null
            ],
            'TaggableBehavior' => [
                'class' => TaggableBehavior::class
            ]
        ]);
    }
    
    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }
    
    public static function getEditFields()
    {
        $labels = self::attributeLabels();
        
        return [
            [
                'slug' => 'domanda',
                'label' => $labels['domanda'],
                'type' => 'text'
            ],
            [
                'slug' => 'risposta',
                'label' => $labels['risposta'],
                'type' => 'text'
            ],
            [
                'slug' => 'ticket_categoria_id',
                'label' => $labels['ticket_categoria_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'version',
                'label' => $labels['version'],
                'type' => 'integer'
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function getSchema()
    {
    
    }
    
    /**
     * @inheritdoc
     */
    public function getPublicatedFrom()
    {
        return $this->created_at;
    }
    
    /**
     * @inheritdoc
     */
    public function getPublicatedAt()
    {
        return $this->created_at;
    }
    
    /**
     * @inheritdoc
     */
    public function getCategory()
    {
        return null;
    }
    
    /**
     * @inheritdoc
     */
    public function getCwhValidationStatuses()
    {
        return [];
    }
    
    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return $this->domanda;
    }
    
    /**
     * @inheritdoc
     */
    public function getShortDescription()
    {
        return $this->getDescription(true);
    }
    
    /**
     * @inheritdoc
     */
    public function getDescription($truncate)
    {
        /** @var AmosTicket $ticketModule */
        $ticketModule = \Yii::$app->getModule(AmosTicket::getModuleName());
        $ret = $this->risposta;

        if ($truncate) {
            $ret = $this->__shortText($this->risposta, $ticketModule->faqDescriptionLimit);
        }

        return $ret;
    }
    
    /**
     * @inheritdoc
     */
    public function getDraftStatus()
    {
        return null;
    }
    
    /**
     * @inheritdoc
     */
    public function getGrammar()
    {
        return new TicketFaqGrammar();
    }
    
    /**
     * @inheritdoc
     */
    public function getGridViewColumns()
    {
        return [];
    }
    
    /**
     * @inheritdoc
     */
    public function getPluginWidgetClassname()
    {
        return WidgetIconTicketDashboard::className();
    }
    
    /**
     * @inheritdoc
     */
    public function getToValidateStatus()
    {
        return null;
    }
    
    /**
     * @inheritdoc
     */
    public function getValidatedStatus()
    {
        return null;
    }
    
    /**
     * @inheritdoc
     */
    public function getValidatorRole()
    {
        return null;
    }

    public function getStarValutation() {
        if($this->total_votes == 0) {
            return 0;
        }
        return number_format($this->total_rating / $this->total_votes, 1);
    }

    /**
     * @inheritdoc
     */
    public function getViewUrl()
    {
        return 'ticket/assistenza/cerca-faq';
    }

    /**
     * @inheritdoc
     */
    public function getFullViewUrl()
    {
        return Url::toRoute(['/' . $this->getViewUrl(), 'categoriaSelezionataId' => $this->ticketCategoria->id, 'faqId' => $this->id]);
    }
}
