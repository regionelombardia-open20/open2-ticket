<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\rules\workflow
 * @category   CategoryName
 */

namespace open2\amos\ticket\rules\workflow;

use open20\amos\core\rules\BasicContentRule;
use open2\amos\ticket\models\Ticket;
use open20\amos\community\models\Community;

/**
 * Class TicketToProcessingWorkflowRule
 * @package open2\amos\ticket\rules\workflow
 */
class TicketToProcessingWorkflowRule extends BasicContentRule
{
    public $name = 'TicketToProcessingWorkflowRule';

    /**
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        /** @var Ticket $model */
        $firstAnswer = $model->getFirstAnswer();

        $isCommunityManager = false;
        $moduleCwh = \Yii::$app->getModule('cwh');
        $moduleCommunity = \Yii::$app->getModule('community');
        if (isset($moduleCwh) && isset($moduleCommunity) && !empty($moduleCwh->getCwhScope())) {
            $scope = $moduleCwh->getCwhScope();
            if (isset($scope['community'])) {
                $community = Community::findOne(['id' => $scope['community']]);
                if (!empty($community)) {
                    $isCommunityManager = $community->isCommunityManager($user);
                }
            }
        }

        return !is_null($firstAnswer) and ($model->isReferente($user) or $isCommunityManager);
    }
}
