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

use open20\amos\core\migration\AmosMigrationWorkflow;
use yii\helpers\ArrayHelper;

/**
 * Class m211011_073928_add_ticket_workflow_status_waiting_technical_assistance
 */
class m211011_073928_add_ticket_workflow_status_waiting_technical_assistance extends AmosMigrationWorkflow
{
    const WORKFLOW_NAME = 'TicketWorkflow';
    const WORKFLOW_WAITING_TECHNICAL_ASSISTANCE = 'WAITINGTECHNICALASSISTANCE';
    
    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return ArrayHelper::merge(
            parent::setWorkflow(),
            $this->workflowStatusConf(),
            $this->workflowTransitionsConf(),
            $this->workflowMetadataConf()
        );
    }
    
    /**
     * In this method there are the new workflow statuses configurations.
     * @return array
     */
    private function workflowStatusConf()
    {
        return [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => self::WORKFLOW_WAITING_TECHNICAL_ASSISTANCE,
                'workflow_id' => self::WORKFLOW_NAME,
                'label' => 'In attesa di assistenza tecnica',
                'sort_order' => '3'
            ]
        ];
    }
    
    /**
     * In this method there are the new workflow status transitions configurations.
     * @return array
     */
    private function workflowTransitionsConf()
    {
        return [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'WAITING',
                'end_status_id' => self::WORKFLOW_WAITING_TECHNICAL_ASSISTANCE
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'PROCESSING',
                'end_status_id' => self::WORKFLOW_WAITING_TECHNICAL_ASSISTANCE
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => self::WORKFLOW_WAITING_TECHNICAL_ASSISTANCE,
                'end_status_id' => 'CLOSED'
            ]
        ];
    }
    
    /**
     * In this method there are the new workflow metadata configurations.
     * @return array
     */
    private function workflowMetadataConf()
    {
        return [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_WAITING_TECHNICAL_ASSISTANCE,
                'key' => 'label',
                'value' => '#' . self::WORKFLOW_WAITING_TECHNICAL_ASSISTANCE . '_label'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_WAITING_TECHNICAL_ASSISTANCE,
                'key' => 'buttonLabel',
                'value' => '#' . self::WORKFLOW_WAITING_TECHNICAL_ASSISTANCE . '_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_WAITING_TECHNICAL_ASSISTANCE,
                'key' => 'description',
                'value' => '#' . self::WORKFLOW_WAITING_TECHNICAL_ASSISTANCE . '_description'
            ]
        ];
    }
}
