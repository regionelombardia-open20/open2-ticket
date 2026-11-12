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
use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\models\Ticket;

/**
 * Class TicketWaitingTechnicalAssistanceWorkflowRule
 * @package open2\amos\ticket\rules\workflow
 */
class TicketWaitingTechnicalAssistanceWorkflowRule extends BasicContentRule
{
    public $name = 'ticketWaitingTechnicalAssistanceWorkflowRule';
    
    /**
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        /** @var AmosTicket $ticketModule */
        $ticketModule = AmosTicket::instance();
        
        // If the administrative ticket category type is disabled the permission is not available
        if (!$ticketModule->enableAdministrativeTicketCategory) {
            return false;
        }
        
        /** @var Ticket $model */
        $ticketCategory = $model->ticketCategoria;
        if (is_null($ticketCategory)) {
            return false;
        }
        
        return $ticketCategory->isAdministrative();
    }
}
