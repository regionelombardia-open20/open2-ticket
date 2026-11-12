<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open2\amos\ticket\rules\workflow
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
