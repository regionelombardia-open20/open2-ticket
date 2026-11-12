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

/**
 * Class m181031_123000_tickets_workflow_metadata
 */
class m181031_123000_tickets_workflow_metadata extends AmosMigrationWorkflow
{
    // PER OGNI WORKFLOW ID CONST
    const WORKFLOW_NAME = 'TicketWorkflow';
    const WORKFLOW_WAITING = 'WAITING';
    const WORKFLOW_PROCESSING = 'PROCESSING';
    const WORKFLOW_CLOSED = 'CLOSED';

    /**
     * @inheritdoc
     */
    protected function beforeAddConfs()
    {
        $this->delete('sw_metadata', 'workflow_id = "' . self::WORKFLOW_NAME . '"');
        return true;
    }

    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return [
            // "DRAFT" status
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_WAITING,
                'key' => 'buttonLabel',
                'value' => '#' . self::WORKFLOW_WAITING . '_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_WAITING,
                'key' => 'description',
                'value' => '#' . self::WORKFLOW_WAITING . '_description'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_WAITING,
                'key' => 'label',
                'value' => '#' . self::WORKFLOW_WAITING . '_label'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_WAITING,
                'key' => self::WORKFLOW_PROCESSING . '_buttonLabel',
                'value' => '#' . self::WORKFLOW_WAITING . '_' . self::WORKFLOW_PROCESSING . '_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_WAITING,
                'key' => self::WORKFLOW_PROCESSING . '_description',
                'value' => '#' . self::WORKFLOW_WAITING . '_' . self::WORKFLOW_PROCESSING . '_description'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_WAITING,
                'key' => self::WORKFLOW_CLOSED . '_buttonLabel',
                'value' => '#' . self::WORKFLOW_WAITING . '_' . self::WORKFLOW_CLOSED . '_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_WAITING,
                'key' => self::WORKFLOW_CLOSED . '_description',
                'value' => '#' . self::WORKFLOW_WAITING . '_' . self::WORKFLOW_CLOSED . '_description'
            ],
            // TOVALIDATE
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_PROCESSING,
                'key' => 'buttonLabel',
                'value' => '#' . self::WORKFLOW_PROCESSING . '_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_PROCESSING,
                'key' => 'description',
                'value' => '#' . self::WORKFLOW_PROCESSING . '_description'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_PROCESSING,
                'key' => 'label',
                'value' => '#' . self::WORKFLOW_PROCESSING . '_label'
            ],
            // VALIDATED
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_CLOSED,
                'key' => 'buttonLabel',
                'value' => '#' . self::WORKFLOW_CLOSED . '_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_CLOSED,
                'key' => 'description',
                'value' => '#' . self::WORKFLOW_CLOSED . '_description'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_CLOSED,
                'key' => 'label',
                'value' => '#' . self::WORKFLOW_CLOSED . '_label'
            ],
            // -----------------------------------------------------------
        ];
    }
}
