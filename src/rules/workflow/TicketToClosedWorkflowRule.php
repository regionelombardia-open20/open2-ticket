<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open2\amos\ticket\rules\workflow
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

namespace open2\amos\ticket\rules\workflow;

//use yii\rbac\Rule;
use open20\amos\core\rules\BasicContentRule;
use open2\amos\ticket\models\Ticket;
use open2\amos\ticket\models\TicketCategorie;

/**
 * Class TicketToClosedWorkflowRule
 * @package open2\amos\ticket\rules\workflow
 */
class TicketToClosedWorkflowRule extends BasicContentRule
{
    public $name = 'ticketToClosedWorkflowRule';

    /**
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        /** @var Ticket $model */
        if (!$model->id) {
            return true;    // TODO errore nella creazione in una categoria tecnica nella chiusura del ticket appena creato sistemare diversamente
        }
        $isCategoriaTecnica = $model->ticketCategoria->tecnica;
//        pr($model->attributes);
//        pr((($model->created_by == $user) && $isCategoriaTecnica) ? "primo if true" : "primo if false");
//        pr(($model->isReferente($user, true, true)) ? "isReferente true" : "isReferente false");
//        pr(($isCategoriaTecnica || ($model->status == Ticket::TICKET_WORKFLOW_STATUS_PROCESSING)) ? "tecnica processing true" : "tecnica processing false");
//        pr((($model->status == Ticket::TICKET_WORKFLOW_STATUS_WAITING) && $model->isAncestor()) ? "waiting ancestor true" : "waiting ancestor false");
//        die();
        return (
            (($model->created_by == $user) && $isCategoriaTecnica) || // creato in una categoria tecnica
//            () || // Permesso di chiusura se referente della vecchia categoria
            (
                $model->isReferente($user) &&
                ($isCategoriaTecnica || ($model->status == Ticket::TICKET_WORKFLOW_STATUS_PROCESSING)) ||
                (($model->status == Ticket::TICKET_WORKFLOW_STATUS_WAITING) && $model->isAncestor())   // è stato forwadato
            )
        );
    }
}
