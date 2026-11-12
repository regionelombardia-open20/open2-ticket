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
use open2\amos\ticket\rules\TicketDeleteRule;
use open2\amos\ticket\rules\TicketManagerInCommunityRoleRule;
use open2\amos\ticket\rules\workflow\TicketToProcessingWorkflowRule;
use yii\rbac\Permission;

/**
 * Class m190207_095324_add_permission_ticket_manager
 */
class m190207_095324_add_permission_ticket_manager extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'TICKET_MANAGER_FOR_COMMUNITY',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to manage tickets, categories and faq',
                'ruleName' => TicketManagerInCommunityRoleRule::className(),
                'parent' => ['VALIDATED_BASIC_USER'],
                'children' => [
                    'TICKET_CREATE',
                    'TICKET_READ',
                    'TICKET_UPDATE',
                    'TICKET_DELETE',
                    'TICKETCATEGORIE_CREATE',
                    'TICKETCATEGORIE_READ',
                    'TICKETCATEGORIE_UPDATE',
                    'TICKETCATEGORIE_DELETE',
                    'TICKETFAQ_CREATE',
                    'TICKETFAQ_READ',
                    'TICKETFAQ_UPDATE',
                    'TICKETFAQ_DELETE',
                    TicketToProcessingWorkflowRule::className(),
                    TicketDeleteRule::className()
                ]
            ]
        ];
    }
}
