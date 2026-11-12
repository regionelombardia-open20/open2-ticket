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
use yii\rbac\Permission;

/**
 * Class m181019_152500_add_ticket_faq_permissions
 */
class m181019_152500_add_ticket_faq_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return $this->setTicketFaqModelPermissions();
    }

    /**
     * Ticket categories model permissions
     *
     * @return array
     */
    private function setTicketFaqModelPermissions()
    {
        return [
            [
                'name' => 'TICKETFAQ_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model TicketFaq',
                'ruleName' => null,
                'parent' => ['REFERENTE_TICKET']
            ],
            [
                'name' => 'TICKETFAQ_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model TicketFaq',
                'ruleName' => null,
                'parent' => ['REFERENTE_TICKET']
            ],
            [
                'name' => 'TICKETFAQ_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model TicketFaq',
                'ruleName' => null,
                'parent' => ['REFERENTE_TICKET']
            ],
            [
                'name' => 'TICKETFAQ_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model TicketFaq',
                'ruleName' => null,
                'parent' => ['REFERENTE_TICKET']
            ]
        ];
    }
}
