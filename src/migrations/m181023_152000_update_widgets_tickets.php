<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\ticket\migrations
 * @category   CategoryName
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
                'classname' => open20\amos\ticket\widgets\icons\WidgetIconTicketFaq::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\ticket\widgets\icons\WidgetIconTicketDashboard::className(),
                'default_order' => 10,
            ],
            [
                'classname' => open20\amos\ticket\widgets\icons\WidgetIconTicketWaiting::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\ticket\widgets\icons\WidgetIconTicketDashboard::className(),
                'default_order' => 20,
            ],
            [
                'classname' => open20\amos\ticket\widgets\icons\WidgetIconTicketProcessing::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\ticket\widgets\icons\WidgetIconTicketDashboard::className(),
                'default_order' => 30,
            ],
            [
                'classname' => open20\amos\ticket\widgets\icons\WidgetIconTicketClosed::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\ticket\widgets\icons\WidgetIconTicketDashboard::className(),
                'default_order' => 40,
            ],
            [
                'classname' => open20\amos\ticket\widgets\icons\WidgetIconTicketAll::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\ticket\widgets\icons\WidgetIconTicketDashboard::className(),
                'default_order' => 50,
            ],
            [
                'classname' => open20\amos\ticket\widgets\icons\WidgetIconTicketCategorie::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\ticket\widgets\icons\WidgetIconTicketDashboard::className(),
                'default_order' => 100,
            ],
            [
                'classname' => open20\amos\ticket\widgets\icons\WidgetIconTicketAdminFaq::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\ticket\widgets\icons\WidgetIconTicketDashboard::className(),
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
