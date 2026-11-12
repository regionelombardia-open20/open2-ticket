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

use open20\amos\core\migration\libs\common\MigrationCommon;
use open20\amos\workflow\models\WorkflowTransitionsLog;
use open2\amos\ticket\models\Ticket;
use yii\db\ActiveQuery;
use yii\db\Migration;
use yii\db\Query;

/**
 * Class m200305_163338_fix_ticket_closed_by_closed_at
 */
class m200305_163338_fix_ticket_closed_by_closed_at extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $ticketsToFix = $this->findTicketsToFix();
        foreach ($ticketsToFix as $ticketToFix) {
            try {
                $this->update(
                    Ticket::tableName(),
                    ['closed_at' => $ticketToFix['ticket_closed_at'], 'closed_by' => $ticketToFix['ticket_closed_by']],
                    ['id' => $ticketToFix['ticket_id']]
                );
            } catch (\Exception $exception) {
                MigrationCommon::printCheckStructureError($ticketToFix, 'Errore update ticket');
                return false;
            }
        }
        MigrationCommon::printConsoleMessage('Utente e data chiusura ticket aggiornati con successo');
        return true;
    }

    private function findTicketsToFix()
    {
        $ticketTable = Ticket::tableName();
        $transitionLogTable = WorkflowTransitionsLog::tableName();

        $query = new Query();
        $query->select([
            $ticketTable . '.id AS ticket_id',
            $transitionLogTable . '.created_at AS ticket_closed_at',
            $transitionLogTable . '.created_by AS ticket_closed_by',
        ]);
        $query->from($ticketTable);
        $query->innerJoin($transitionLogTable,
            $transitionLogTable . '.owner_primary_key = ' . $ticketTable . '.id AND ' .
            $transitionLogTable . ".end_status = '" . Ticket::TICKET_WORKFLOW_STATUS_CLOSED . "' AND " .
            $transitionLogTable . ".classname LIKE '%Ticket%' AND " .
            $transitionLogTable . ".deleted_at IS NULL");
        $query->andWhere([$ticketTable . '.status' => Ticket::TICKET_WORKFLOW_STATUS_CLOSED]);
        $query->andWhere([$ticketTable . '.closed_by' => null]);
        $query->andWhere([$ticketTable . '.deleted_at' => null]);
        $ticketsToFix = $query->all();
//        MigrationCommon::printConsoleMessage($query->createCommand()->getRawSql());
//        MigrationCommon::printConsoleMessage($ticketsToFix);
//        die();
        return $ticketsToFix;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m200305_163338_fix_ticket_closed_by_closed_at cannot be reverted.\n";
        return false;
    }
}
