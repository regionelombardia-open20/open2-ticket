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

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;

/**
 * Class m181023_152000_update_widgets_tickets
 */
class m181023_152000_update_widgets_tickets extends AmosMigrationWidgets
{
    const MODULE_NAME = 'ticket';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = array_merge(
            $this->initIconWidgetsConf(),
            $this->initGraphicWidgetsConf()
        );
    }

    /**
     * Init the icon widgets configurations
     * @return array
     */
    private function initIconWidgetsConf()
    {
        return [
            [
                'classname' => open2\amos\ticket\widgets\icons\WidgetIconTicketFaq::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open2\amos\ticket\widgets\icons\WidgetIconTicketDashboard::className(),
                'default_order' => 10,
            ],
            [
                'classname' => open2\amos\ticket\widgets\icons\WidgetIconTicketWaiting::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open2\amos\ticket\widgets\icons\WidgetIconTicketDashboard::className(),
                'default_order' => 20,
            ],
            [
                'classname' => open2\amos\ticket\widgets\icons\WidgetIconTicketProcessing::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open2\amos\ticket\widgets\icons\WidgetIconTicketDashboard::className(),
                'default_order' => 30,
            ],
            [
                'classname' => open2\amos\ticket\widgets\icons\WidgetIconTicketClosed::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open2\amos\ticket\widgets\icons\WidgetIconTicketDashboard::className(),
                'default_order' => 40,
            ],
            [
                'classname' => open2\amos\ticket\widgets\icons\WidgetIconTicketAll::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open2\amos\ticket\widgets\icons\WidgetIconTicketDashboard::className(),
                'default_order' => 50,
            ],
            [
                'classname' => open2\amos\ticket\widgets\icons\WidgetIconTicketCategorie::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open2\amos\ticket\widgets\icons\WidgetIconTicketDashboard::className(),
                'default_order' => 100,
            ],
            [
                'classname' => open2\amos\ticket\widgets\icons\WidgetIconTicketAdminFaq::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open2\amos\ticket\widgets\icons\WidgetIconTicketDashboard::className(),
                'default_order' => 110,
            ],
        ];
    }

    /**
     * Init the graphic widgets configurations
     * @return array
     */
    private function initGraphicWidgetsConf()
    {
        return [
        ];
    }
}
