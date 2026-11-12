<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open2\amos-ticket
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

namespace open2\amos\ticket\repository;

use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\models\Ticket;
use open2\amos\ticket\models\TicketOtp;
use Yii;
use yii\base\Component;
use yii\helpers\VarDumper;

class TicketRepository extends Component
{
    const EVENT_BEFORE_TICKET_SAVE = 'beforeTicketSave';
    const EVENT_AFTER_TICKET_SAVE = 'afterTicketSave';

    /**
     * Saves or updates a ticket
     *
     * @param array $ticketData Array containing ticket data
     * @return Ticket The saved or updated ticket
     * @throws \Exception If the ticket cannot be saved
     */
    public function createTicket($ticketData)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $ticket = new Ticket();
            $ticket->setAttributes($ticketData);

            if (!$ticket->validate()) {
                return $ticket;
            }

            $this->trigger(self::EVENT_BEFORE_TICKET_SAVE, new \yii\base\Event([
                'sender' => $ticket,
            ]));

            if (!$ticket->save()) {
                return $ticket;
            }

            $transaction->commit();

            $this->trigger(self::EVENT_AFTER_TICKET_SAVE, new \yii\base\Event([
                'sender' => $ticket,
            ]));

            return $ticket;
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * @param $email
     * @return array|bool
     * @throws \yii\db\Exception
     * @throws \DateMalformedStringException
     */
    public function askOtp($email)
    {
        /** @var AmosTicket $module */
        $module = Yii::$app->getModule('ticket');
        $ticketOtp = TicketOtp::findOne(['email' => $email]);

        if (!$ticketOtp) {
            $ticketOtp = new TicketOtp();
            $ticketOtp->created_at = new \yii\db\Expression('NOW()');
            $ticketOtp->email = $email;
        }

        $canAskOtp = $ticketOtp->canAskOtp();
        if (!$canAskOtp) {
            $nextTry = new \DateTime($ticketOtp->created_at);
            $nextTry->add(new \DateInterval('PT'.$module->otp_requests_time_limit.'M'));
            $nextTryStr =Yii::$app->formatter->asDatetime($nextTry->format('Y-m-d H:i:s'), 'php:H:i');
            return [
                'alert' => 'Non puoi richiedere altri OTP, ritenta dopo le ore: ' . $nextTryStr,
            ];
        }

        $ticketOtp->otp_code = TicketOtp::getOpt();
        $ticketOtp->updated_at = new \yii\db\Expression('NOW()');

        if ($ticketOtp->validate() && $ticketOtp->save()) {
            return $ticketOtp->sendTokenByEmail();
        } else {
            Yii::error($ticketOtp->getErrors(), __METHOD__);
            return $ticketOtp->errors;
        }
    }

    public function validateOtp($email, $otpCode)
    {
        $ticketOtp = TicketOtp::find()->andWhere(['email' => $email, 'otp_code' => $otpCode])->one();

        if (!$ticketOtp) {
            return false;
        }

        $ticketOtp->otp_code = null;
        $ticketOtp->updated_at = new \yii\db\Expression('NOW()');
        $ticketOtp->save();

        return true;
    }

}