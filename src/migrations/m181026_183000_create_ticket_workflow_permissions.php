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
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

/**
 * Class m181026_183000_create_ticket_workflow_permissions
 */
class m181026_183000_create_ticket_workflow_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return ArrayHelper::merge(
            $this->setWorkflowRulePermissions(),
            $this->setWorkflowStatusPermissions(),
            $this->setTicketRulePermissions(),
            $this->updateTicketModelPermissions()
        );
    }

    /**
     * Workflow statuses permissions
     *
     * @return array
     */
    private function setWorkflowRulePermissions()
    {
        return [
            [
                'name' => \open2\amos\ticket\rules\workflow\TicketToClosedWorkflowRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Check if you are an author or the admin',
                'ruleName' => \open2\amos\ticket\rules\workflow\TicketToClosedWorkflowRule::className(),
                'parent' => ['OPERATORE_TICKET']
            ],
            [
                'name' => \open2\amos\ticket\rules\workflow\TicketToProcessingWorkflowRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Check if you are the admin',
                'ruleName' => \open2\amos\ticket\rules\workflow\TicketToProcessingWorkflowRule::className(),
                'parent' => ['REFERENTE_TICKET']
            ],
        ];
    }

    /**
     * Workflow statuses permissions
     *
     * @return array
     */
    private function setWorkflowStatusPermissions()
    {
        return [
            [
                'name' => Ticket::TICKET_WORKFLOW_STATUS_WAITING,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso workflow ticket stato in attesa',
                'parent' => ['OPERATORE_TICKET']
            ],
            [
                'name' => Ticket::TICKET_WORKFLOW_STATUS_PROCESSING,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso workflow ticket stato in carico',
                'parent' => [\open2\amos\ticket\rules\workflow\TicketToProcessingWorkflowRule::className()]
            ],
            [
                'name' => Ticket::TICKET_WORKFLOW_STATUS_CLOSED,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso workflow ticket stato chiuso',
                'parent' => [\open2\amos\ticket\rules\workflow\TicketToClosedWorkflowRule::className()]
            ],
        ];
    }

    /**
     * Workflow statuses permissions
     *
     * @return array
     */
    private function setTicketRulePermissions()
    {
        return [
            [
                'name' => \open2\amos\ticket\rules\TicketDeleteRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Nobody can delete',
                'ruleName' => \open2\amos\ticket\rules\TicketDeleteRule::className(),
                'parent' => ['AMMINISTRATORE_TICKET']
            ],
        ];
    }

    /**
     * Ticket categories model permissions
     *
     * @return array
     */
    private function updateTicketModelPermissions()
    {
        return [
            [
                'name' => 'TICKET_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model Ticket',
                //'update' => true,
                'parent' => [\open2\amos\ticket\rules\TicketDeleteRule::className()]
            ],
        ];
    }
}
