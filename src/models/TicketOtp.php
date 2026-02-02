<?php

namespace open2\amos\ticket\models;

use open2\amos\ticket\AmosTicket;
use open20\crm\exceptions\NotificationEmailException;
use yii\base\Exception;
use yii\db\ActiveRecord;
use Yii;
use yii\helpers\VarDumper;
use yii\log\Logger;

/**
 * @property int $id
 * @property string $email
 * @property string $otp_code
 * @property int $request_count
 * @property int $total_request_count
 */
class TicketOtp extends ActiveRecord
{



    public static function tableName()
    {
        return 'ticket_otp';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'email'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'otp_code' => 'OTP',
            'request_count' => 'Numero di volte in cui il codice OTP the stato richiesto per l\'indirizzo email',
            'total_request_count' => 'TOTALE Numero di volte in cui il codice OTP the stato richiesto per l\'indirizzo email',
        ];
    }

    public static function getOpt()
    {
        return (string)rand(10000, 99999);
    }

    public function sendTokenByEmail()
    {
        try {
            $token = $this->otp_code;
            if (empty($this->email) || empty($this->otp_code)) {
                throw new Exception(\Yii::t('app', 'Email e OTP non valorizzati'));
            }

            $mailModule = Yii::$app->getModule("email");
            $mailModule->defaultLayout = "layout_without_header_and_footer";

            $text = \Yii::t('app', "<h1>Validazione email per apertura ticket</h1>", ['appname' => \Yii::$app->name]);
            $text .= "<p>Per poter aprire il ticket inserisci questo OTP:</p>";
            $text .= "<div style=\"font-size: 32px; font-weight: bold; letter-spacing: 8px; margin: 20px 0;\">{$token}</div>";
            $text .= "<p>Se non hai richiesto questa operazione, ignora questa email.</p>";

            $mailModule->defaultLayout = 'layout_fancy';
            $mailModule->templatePath = \Yii::getAlias('@common') . '/mail/emails';
            $mailModule->send(Yii::$app->params['supportEmail'],
                $this->email,
                Yii::t('amosapp', '{appName}: validazione email per apertura ticket', [
                    'appName' => \Yii::$app->name
                ]),
                $text,
            );

        } catch (Exception $e){
            Yii::getLogger()->log($e->getMessage() . "\n" . $e->getTraceAsString(), Logger::LEVEL_ERROR);
            return false;
        }

        $count = $this->request_count? $this->request_count : 0;
        $this->request_count = $count + 1;

        $count = $this->total_request_count? $this->total_request_count : 0;
        $this->total_request_count = $count + 1;
        $this->updated_at = new \yii\db\Expression('NOW()');

        return $this->save();
    }

    /**
     * @return bool
     * @throws \DateMalformedStringException
     * @throws \yii\db\Exception
     * @throws \Exception
     */
    public function canAskOtp()
    {
        /** @var AmosTicket $module */
        $module = Yii::$app->getModule('ticket');

        if ($this->request_count < $module->otp_num_of_requests) {
            return true;
        } else {
            // in quest caso ha superato il limite di tentativi di richieste
            // controllo il created_at se è superiore al tempo impostato come limite
            // se sì allora imposto un nuovo created_at a now e lascio passare la richiesta
            // se no ritorno falso
            $date = new \DateTime($this->created_at);
            $now = new \DateTime('now');
            $diff = $now->diff($date);

            $minutes = $diff->days * 24 * 60;
            $minutes += $diff->h * 60;
            $minutes += $diff->i;

            if ($minutes > $module->otp_requests_time_limit) {

                $this->created_at = new \yii\db\Expression('NOW()');;
                $this->updated_at = new \yii\db\Expression('NOW()');
                $this->request_count = 0;
                $this->save();

                return true;
            } else {
                return false;
            }
        }
    }

}