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

/**
 * Class m190312_105549_temporary_fix_referente_ticket_workflow_permissions
 */
class m250210_105549_update_user_ticket_workflow_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => Ticket::TICKET_WORKFLOW_STATUS_CLOSED,
                'update' => true,
                'newValues' => [
                    'addParents' => ['BASIC_USER']
                ]
            ]
        ];
    }
}
