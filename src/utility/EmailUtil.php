<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\ticket\utility
 * @category   CategoryName
 */

namespace open20\amos\ticket\utility;

use open20\amos\core\helpers\Html;
use open20\amos\core\utilities\Email;
use open20\amos\ticket\AmosTicket;
use open20\amos\ticket\models\Ticket;
use open20\amos\ticket\models\TicketCategorie;
use yii\log\Logger;

/**
 * Class EmailUtil
 * @package open20\amos\ticket\utility
 */
class EmailUtil
{

    /**
     * manda la mail di creazione del ticket ai referenti perché è un ticket nuovo
     *
     * @param Ticket $ticket
     * @param TicketCategorie $model_ticket_categoria
     * @param $partnershipMsg
     * @return bool
     * @throws \ReflectionException
     */
    public static function sendEmailNewTicketReferenti($ticket, $model_ticket_categoria, $partnershipMsg)
    {
        $from = \Yii::$app->params['supportEmail'];
        //$to = [$user->email];

        $noCommunity = true;
        $ticketCategoria = $ticket->ticketCategoria;
        if (!empty($ticketCategoria) && !empty($ticketCategoria->community_id)) {
            $noCommunity = false;
        }
        $emailReferenti = TicketUtility::getEmailReferentiCategoria($ticket->ticket_categoria_id, $noCommunity);
        $to = $emailReferenti;

        $subject = AmosTicket::t("amosticket", 'Nuovo ticket id. ' . $ticket->id);

        $body[] = '';
        $body[] = AmosTicket::t('amosticket', 'E\' stato aperto un nuovo ticket nel plugin assistenza');
        $body[] = '<strong>' . $ticket->getAttributeLabel('ticket_categoria_id') . ': ' . $model_ticket_categoria->nomeCompleto . '</strong>';
        $body[] = "<strong>" . $ticket->getAttributeLabel('id') . ': ' . $ticket->id . '</strong>';
        if ($partnershipMsg) {
            $body[] = '<strong>' . $partnershipMsg . '</strong>';
        }

        $body[] = $ticket->getAttributeLabel('created_by') . ': ' . $ticket->createdUserProfile->nomeCognome;
        $body[] = $ticket->getAttributeLabel('titolo') . ': ' . $ticket->titolo;
        $body[] = $ticket->getAttributeLabel('descrizione') . ': ' . $ticket->descrizione;
        $body[] = '<strong>' . Html::a(AmosTicket::t('amosticket', 'Clicca qui per visualizzarlo.'), \Yii::$app->urlManager->createAbsoluteUrl(['/ticket/ticket/view?id=' . $ticket->id])) . '</strong><br />';
        $body[] = AmosTicket::t('amosticket', 'Ricordati di controllarlo.');

        $params = [
            //'userProfile' => $user->userProfile->toArray(),
            'body' => $body,
        ];

        $layout = [
            'html' => '@vendor/open20/amos-ticket/src/mail/generic/generic-html',
        ];

        return self::sendEmail($to, $from, $subject, $params, $layout);
    }

    /**
     * manda la mail di inoltro del ticket ai referenti perché è un ticket inoltrato alla loro categoria
     *
     * @param Ticket $ticket
     * @param $partnershipMsg
     * @return bool
     * @throws \ReflectionException
     */
    public static function sendEmailForwardReferenti($ticket, $partnershipMsg)
    {
        $from = \Yii::$app->params['supportEmail'];

        $ticketPrecedente = Ticket::findOne($ticket->forwarded_from_id);

        $emailReferenti = TicketUtility::getEmailReferentiCategoria($ticket->ticket_categoria_id);
        $to = $emailReferenti;

        $creatorUserProfile = $ticket->createdUserProfile;
        $nameSurname = (!is_null($creatorUserProfile) ? $creatorUserProfile->getNomeCognome() : '-');

        $subject = AmosTicket::t("amosticket", 'Inoltro ticket id. ' . $ticket->id);

        $body[] = '<br>';
        $body[] = AmosTicket::t('amosticket', "E' stato inoltrato un ticket che era stato aperto in un'altra categoria");
        $body[] = '<strong>' . $ticket->getAttributeLabel('ticket_categoria_id') . ': ' . $ticket->ticketCategoria->nomeCompleto . '</strong>';
        $body[] = '<strong>' . $ticket->getAttributeLabel('id') . ': ' . $ticket->id . '</strong>';
        if ($partnershipMsg) {
            $body[] = '<strong>' . $partnershipMsg . '</strong>';
        }

        $body[] = $ticket->getAttributeLabel('created_by') . ': ' . $nameSurname;
        $body[] = $ticket->getAttributeLabel('titolo') . ': ' . $ticket->titolo;
        $body[] = $ticket->getAttributeLabel('descrizione') . ': ' . $ticket->descrizione;
        $body[] = $ticket->getAttributeLabel('forward_message') . ': ' . $ticket->forward_message;
        $body[] = AmosTicket::t('amosticket', 'Ticket ID da cui è stato fatto l\'inoltro') . ': ' . $ticketPrecedente->id;
        $body[] = AmosTicket::t('amosticket', 'Categoria da cui è stato fatto l\'inoltro') . ': ' . $ticketPrecedente->ticketCategoria->nomeCompleto;
        $body[] = '<strong>' . Html::a(AmosTicket::t('amosticket', 'Clicca qui per visualizzarlo.'), \Yii::$app->urlManager->createAbsoluteUrl(['/ticket/ticket/view?id=' . $ticket->id])) . '</strong><br />';
        $body[] = AmosTicket::t('amosticket', 'Ricordati di controllarlo.');

        $params = [
            //'userProfile' => $user->userProfile->toArray(),
            'body' => $body,
        ];

        $layout = [
            'html' => '@vendor/open20/amos-ticket/src/mail/generic/generic-html',
        ];

        return self::sendEmail($to, $from, $subject, $params, $layout);
    }

    /**
     * manda la mail di inoltro del ticket all'operatore per informarlo che è stato
     * aperto un nuovo ticket in un'altra categoria
     *
     * @param Ticket $ticket
     * @param $partnershipMsg
     * @return bool
     * @throws \ReflectionException
     */
    public static function sendEmailForwardOperatore($ticket, $partnershipMsg)
    {
        $from = \Yii::$app->params['supportEmail'];

        $ticketPrecedente = Ticket::findOne($ticket->forwarded_from_id);

        $creatorUserProfile = $ticket->getCreatedUserProfile()->one();
        $to = $creatorUserProfile->user->email;

        $subject = AmosTicket::t('amosticket', 'Inoltro ticket id. ' . $ticket->id);

        $body[] = '';
        $body[] = AmosTicket::t('amosticket', 'Il ticket id.<strong> ' . $ticketPrecedente->id . '</strong>, aperto nella categoria ' . $ticketPrecedente->ticketCategoria->nomeCompleto . ", è stato inoltrato ad un'altra categoria e perciò chiuso.");
        $body[] = AmosTicket::t('amosticket', "E' stato aperto un nuovo ticket id.<strong>" . $ticket->id . '</strong> nella categoria ' . $ticket->ticketCategoria->nomeCompleto . ' che è in attesa di essere preso in carico.');
        $body[] = AmosTicket::t('amosticket', 'Di seguito i dettagli del nuovo ticket:');
        $body[] = '<strong>' . $ticket->getAttributeLabel('ticket_categoria_id') . ': ' . $ticket->ticketCategoria->nomeCompleto . '</strong>';
        $body[] = '<strong>' . $ticket->getAttributeLabel('id') . ': ' . $ticket->id . '</strong>';
        if ($partnershipMsg) {
            $body[] = '<strong>' . $partnershipMsg . '</strong>';
        }

        $body[] = $ticket->getAttributeLabel('titolo') . ': ' . $ticket->titolo;
        $body[] = $ticket->getAttributeLabel('descrizione') . ': ' . $ticket->descrizione;
        if ($ticket->forward_message_to_operator) {
            $body[] = $ticket->getAttributeLabel('forward_message') . ': ' . $ticket->forward_message;
        }

        $body[] = '<strong>' . Html::a(AmosTicket::t('amosticket', 'Clicca qui per visualizzarlo.'), \Yii::$app->urlManager->createAbsoluteUrl(['/ticket/ticket/view?id=' . $ticket->id])) . '</strong>';

        $params = [
            'userProfile' => $creatorUserProfile->toArray(),
            'body' => $body,
        ];

        $layout = [
            'html' => '@vendor/open20/amos-ticket/src/mail/generic/generic-user-html',
        ];

        return self::sendEmail($to, $from, $subject, $params, $layout);
    }

    /**
     * manda la mail di chiusura del ticket all'operatore
     *
     * @param Ticket $ticket
     * @param $partnershipMsg
     * @return bool
     * @throws \ReflectionException
     */
    public static function sendEmailChiusuraOperatore($ticket, $partnershipMsg)
    {
        $from = \Yii::$app->params['supportEmail'];

        $creatorUserProfile = $ticket->getCreatedUserProfile()->one();
        $to = $creatorUserProfile->user->email;

        $subject = AmosTicket::t("amosticket", 'Chiusura ticket id. ' . $ticket->id);

        $body[] = '';
        $body[] = AmosTicket::t('amosticket', 'Il ticket id.<strong>' . $ticket->id . "</strong> \"$ticket->titolo\" è stato chiuso");
        if ($partnershipMsg) {
            $body[] = '<strong>' . $partnershipMsg . '</strong>';
        }

        $params = [
            'userProfile' => $creatorUserProfile->toArray(),
            'body' => $body,
        ];

        $layout = [
            'html' => '@vendor/open20/amos-ticket/src/mail/generic/generic-user-html',
        ];

        return self::sendEmail($to, $from, $subject, $params, $layout);
    }

    /**
     * manda la mail all'operatore che la sua richiesta è stata girata a una gestione esterna
     *
     * @param Ticket $ticket
     * @return bool
     * @throws \ReflectionException
     */
    public static function sendEmailTecnicaOperatore($ticket)
    {
        $from = \Yii::$app->params['supportEmail'];

        $creatorUserProfile = $ticket->getCreatedUserProfile()->one();
        $to = $creatorUserProfile->user->email;

        $subject = AmosTicket::t("amosticket", 'Gestione esterna ticket id. ' . $ticket->id);

        $body[] = '';
        $body[] = AmosTicket::t('amosticket', 'Il ticket id.<strong>' . $ticket->id . "</strong> \"$ticket->titolo\" è stato affidato a una gestione esterna");

        $params = [
            'userProfile' => $creatorUserProfile->toArray(),
            'body' => $body,
        ];

        $layout = [
            'html' => '@vendor/open20/amos-ticket/src/mail/generic/generic-user-html',
        ];

        return self::sendEmail($to, $from, $subject, $params, $layout);
    }

    /**
     * manda la mail di creazione di un ticket tecnico (sia nuovo che inoltrato)
     *
     * @param Ticket $ticket
     * @param TicketCategorie $model_ticket_categoria
     * @param $partnershipMsg
     * @return bool
     * @throws \ReflectionException
     */
    public static function sendEmailCategoriaTecnica($ticket, $model_ticket_categoria, $partnershipMsg)
    {
        $from = \Yii::$app->params['supportEmail'];

        $emailReferenti = $model_ticket_categoria->email_tecnica;
        $to = $emailReferenti;

        $creatorUserProfile = $ticket->createdUserProfile;
        $nameSurname = (!is_null($creatorUserProfile) ? $creatorUserProfile->getNomeCognome() : '-');
        $creatorUser = $creatorUserProfile->user;
        $creatorEmail = $creatorUser->email;

        $subject = AmosTicket::t("amosticket", 'Nuovo ticket id. ' . $ticket->id);

        $body[] = '';
        $body[] = AmosTicket::t('amosticket', 'E\' stato aperto un nuovo ticket nel plugin assistenza');
        $body[] = '<strong>' . $ticket->getAttributeLabel('ticket_categoria_id') . ': ' . $model_ticket_categoria->nomeCompleto . '</strong>';
        $body[] = '<strong>' . $ticket->getAttributeLabel('id') . ': ' . $ticket->id . '</strong>';
        if ($partnershipMsg) {
            $body[] = '<strong>' . $partnershipMsg . '</strong>';
        }

        $body[] = $ticket->getAttributeLabel('created_by') . ': ' . $nameSurname;
        $body[] = $ticket->getAttributeLabel('titolo') . ': ' . $ticket->titolo;

        $body[] = $creatorUser->getAttributeLabel('email') . ': ' . $creatorEmail;

        $body[] = $ticket->getAttributeLabel('descrizione') . ': ' . $ticket->descrizione;
        $body[] = AmosTicket::t('amosticket', 'Questo ticket ci risulta di vostra competenza.');
        $body[] = '<strong>' . Html::a(
                AmosTicket::t('amosticket', 'Clicca qui per visualizzarlo.'),
                \Yii::$app->urlManager->createAbsoluteUrl(['/ticket/ticket/view?id=' . $ticket->id])
            ) . '</strong>';

        $params = [
            'body' => $body,
        ];

        $layout = [
            'html' => '@vendor/open20/amos-ticket/src/mail/generic/generic-html',
        ];

        return self::sendEmail($to, $from, $subject, $params, $layout, null, $creatorEmail, $creatorEmail);
    }

    /**
     * @param string $to
     * @param string $from
     * @param string $subject
     * @param array $params
     * @param array|null|string $template
     * @param null $layoutHtml
     * @param array|string $replyTo
     * @param array|string $cc
     * @return bool
     * @throws \ReflectionException
     */
    public static function sendEmail($to, $from, $subject, $params, $template, $layoutHtml = null, $replyTo = [], $cc = [])
    {
        if ($layoutHtml) {
            \Yii::$app->mailer->htmlLayout = $layoutHtml;
        }

        $from = isset(\Yii::$app->params['email-assistenza']) ? \Yii::$app->params['email-assistenza'] : 'assistenza@open20.it';

        if (is_string($to)) {
            $to = [$to];
        }

        $controller = \Yii::$app->controller;
        $userDefault = null;
        $message = $controller->renderPartial($template['html'], $params);

        /** @var \open20\amos\core\utilities\Email $email */
        $email = new Email();
        return $email->sendMail($from, $to, $subject, $message, [], [], [], 0, false, $cc, $replyTo);


//        $mail = \Yii::$app->mailer->compose(
//            $template,
//            $params
//        )
//            ->setFrom($from)
//            ->setTo($to)
//            ->setSubject($subject);
//        if (!empty($replyTo)) {
//            $mail->setReplyTo($replyTo);
//        }
//        if (!empty($cc)) {
//            $mail->setCc($cc);
//        }
//
//        if ($mail instanceof \yii\swiftmailer\Message && isset($_GET['preview'])) {
//            $swiftMessage = $mail->getSwiftMessage();
//            $r = new \ReflectionObject($swiftMessage);
//            $parentClassThatHasBody = $r->getParentClass()
//                ->getParentClass()
//                ->getParentClass(); //\Swift_Mime_SimpleMimeEntity
//            $body = $parentClassThatHasBody->getProperty('immediateChildren');
//            $body->setAccessible(true);
//            $children = $body->getValue($swiftMessage);
//            foreach ($children as $child) {
//                if ($child instanceof \Swift_MimePart &&
//                    $child->getContentType() == 'text/html'
//                ) {
//                    $html = $child->getBody();
//                    break;
//                }
//            }
//
//            echo $html;
//            die;
//        }

//        return $mail->send();
    }

    protected static function splitMailFrom($from)
    {
        $fromName = \Yii::$app->name;
        $fromEmail = \Yii::$app->params['supportEmail'];

        $posInFrom = strpos($from, ' ');
        $posInSupp = strpos($fromEmail, ' ');

        if ($posInFrom > 0) {
            $elements = str_split($from, $posInFrom);
        } else if ($posInSupp > 0) {
            $elements = str_split($from, $posInSupp);
        }

        if (!empty($elements[0]) && !empty($elements[1])) {
            return $elements[0] . ' '. trim($elements[1]);
        } else {
            return $from . ' ' . $fromName;
        }
    }
}