<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open2\amos\ticket\models\Ticket;
use open2\amos\ticket\rules\TicketManagerInCommunityRoleRule;

/**
 * Class m190312_105549_temporary_fix_referente_ticket_workflow_permissions
 */
class m250210_175549_update_cm_ticket_workflow_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'TICKET_MANAGER_FOR_COMMUNITY',
                'update' => true,
                'newValues' => [
                    'addParents' => ['BASIC_USER']
                ]
            ]
        ];
    }
}
