<?php

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