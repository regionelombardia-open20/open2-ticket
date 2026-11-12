<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open2\amos\ticket\migrations
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

use open20\amos\core\migration\AmosMigrationPermissions;
use open2\amos\ticket\models\Ticket;
use open2\amos\ticket\rules\workflow\TicketWaitingTechnicalAssistanceWorkflowRule;
use yii\rbac\Permission;

/**
 * Class m211011_100753_add_ticket_workflow_status_waiting_technical_assistance_permissions
 */
class m211011_100753_add_ticket_workflow_status_waiting_technical_assistance_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => TicketWaitingTechnicalAssistanceWorkflowRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso regola workflow ticket stato in attesa di assistenza tecnica',
                'ruleName' => TicketWaitingTechnicalAssistanceWorkflowRule::className(),
                'parent' => ['OPERATORE_TICKET']
            ],
            [
                'name' => Ticket::TICKET_WORKFLOW_STATUS_WAITING_TECHNICAL_ASSISTANCE,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso workflow ticket stato in attesa di assistenza tecnica',
                'parent' => [TicketWaitingTechnicalAssistanceWorkflowRule::className()]
            ],
        ];
    }
}
