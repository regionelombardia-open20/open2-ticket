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
use yii\helpers\ArrayHelper;

/**
 * Class m181119_103000_modify_ticket_and_ticket_categorie_permissions
 */
class m181119_103000_modify_ticket_and_ticket_categorie_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return ArrayHelper::merge(
            $this->updateTicketModelPermissions(),
            $this->updateTicketCategorieModelPermissions()
        );
    }

    /**
     * @return array
     */
    private function updateTicketModelPermissions()
    {
        return [
            [
                'name' => 'TICKET_DELETE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['AMMINISTRATORE_TICKET']
                ]
            ],
            [
                'name' => 'TICKET_READ',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['OPERATORE_TICKET']
                ]
            ],
            [
                'name' => 'TICKET_UPDATE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['OPERATORE_TICKET']
                ]
            ]
        ];
    }

    /**
     * Ticket categories model permissions
     *
     * @return array
     */
    private function updateTicketCategorieModelPermissions()
    {
        return [
            [
                'name' => 'TICKETCATEGORIE_DELETE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['REFERENTE_TICKET']
                ]
            ],
            [
                'name' => 'TICKETCATEGORIE_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['OPERATORE_TICKET']
                ]
            ]
        ];
    }
}
