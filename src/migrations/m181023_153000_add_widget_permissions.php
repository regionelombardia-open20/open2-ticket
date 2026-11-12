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
 * Class m181023_153000_add_widget_permissions
 */
class m181023_153000_add_widget_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => open2\amos\ticket\widgets\icons\WidgetIconTicketDashboard::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconTicketDashboard',
                'ruleName' => null,
                'parent' => ['OPERATORE_TICKET']
            ],
            [
                'name' => open2\amos\ticket\widgets\icons\WidgetIconTicketFaq::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconTicketFaq',
                'parent' => ['OPERATORE_TICKET']
            ],
            [
                'name' => open2\amos\ticket\widgets\icons\WidgetIconTicketWaiting::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconTicketWaiting',
                'parent' => ['OPERATORE_TICKET']
            ],
            [
                'name' => open2\amos\ticket\widgets\icons\WidgetIconTicketProcessing::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconTicketProcessing',
                'parent' => ['OPERATORE_TICKET']
            ],
            [
                'name' => open2\amos\ticket\widgets\icons\WidgetIconTicketClosed::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconTicketClosed',
                'parent' => ['OPERATORE_TICKET']
            ],
            [
                'name' => open2\amos\ticket\widgets\icons\WidgetIconTicketAll::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconTicketAll',
                'parent' => ['OPERATORE_TICKET']
            ],
            [
                'name' => open2\amos\ticket\widgets\icons\WidgetIconTicketCategorie::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconTicketCategorie',
                'parent' => ['REFERENTE_TICKET']
            ],
            [
                'name' => open2\amos\ticket\widgets\icons\WidgetIconTicketAdminFaq::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconTicketAdminFaq',
                'parent' => ['REFERENTE_TICKET']
            ],
        ];
    }
}
