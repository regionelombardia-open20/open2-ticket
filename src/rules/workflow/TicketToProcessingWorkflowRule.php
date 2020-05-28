<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\ticket\rules\workflow
 * @category   CategoryName
 */

namespace open20\amos\ticket\rules\workflow;

use open20\amos\core\rules\BasicContentRule;
use open20\amos\ticket\models\Ticket;

/**
 * Class TicketToProcessingWorkflowRule
 * @package open20\amos\ticket\rules\workflow
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
        return !is_null($firstAnswer) and $model->isReferente($user);
    }
}
