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

namespace open2\amos\ticket\controllers;

use open2\amos\ticket\repository\TicketRepository;
use open20\attributes\annotations\Autowire;
use open20\attributes\annotations\Route;
use open20\microfrontend\core\controllers\BearerRestController;
use open20\microfrontend\core\utilities\ArrayService;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\VarDumper;
use yii\log\Logger;
use OpenApi\Attributes as OA;

/**
 * @OA\Info(title="Open20 API", version="0.1")
 */
#[OA\Tag(name: "TicketApiController", description: "1.0")]
class TicketApiController extends BearerRestController
{
    #[Autowire(TicketRepository::class)]
    public ?TicketRepository $ticketRepository = null;

    public function behaviors() {
        $behaviours = parent::behaviors();
        $behaviours = ArrayService::mergeRecursive($behaviours, [
            'authenticator' => [
                'except' => [
                    'create-guest-ticket',
                    'ask-opt',
                ]
            ],
        ]);
        return $behaviours;
    }

    #[Route('POST', 'microservices/api/v1/ticket/create-ticket', '/ticket/ticket-api/create-ticket')]
    #[OA\Post(path: 'microservices/api/v1/ticket/create-ticket', description: 'Creates a new ticket', tags: ['TicketApiController'])]
    #[OA\Response(response: 200, description: 'Returns the created ticket')]
    public function actionCreateTicket()
    {
        $toRet = [
            'status' => null,
            'message' => '',
            'data' => null,
        ];

        $request = Yii::$app->getRequest();
        $data = $request->post();

        try {
            $ticket = $this->ticketRepository->createTicket($data);

            // se il ticket è stato salvato correttamente allora ha un id reale non nullo
            if (empty($ticket->id)){
                $toRet['status'] = 'error';
                $toRet['message'] = 'Errore durante la creazione del ticket, controlla i campi inseriti';
                $toRet['data'] = $ticket->errors;
            } else {
                $toRet['status'] = 'OK';
                $toRet['message'] = 'Ticket creato con successo';
                $toRet['data'] = $ticket->attributes;
            }

        } catch (\Exception $e) {
            Yii::getLogger()->log($e->getMessage() ."\n". $e->getTraceAsString(), Logger::LEVEL_ERROR);
            $toRet['status'] = 'error';
            $toRet['message'] = 'Errore durante la creazione del ticket';
            $toRet['data'] = [];
        }

        return $toRet;
    }


    #[Route('POST', 'microservices/api/v1/ticket/create-guest-ticket', '/ticket/ticket-api/create-guest-ticket')]
    #[OA\Post(path: 'microservices/api/v1/ticket/create-guest-ticket', description: 'Creates a new ticket', tags: ['TicketApiController'])]
    #[OA\Response(response: 200, description: 'Returns the created ticket')]
    public function actionCreateGuestTicket()
    {
        $toRet = [
            'status' => null,
            'message' => '',
            'data' => null,
        ];

        $request = Yii::$app->getRequest();
        $data = $request->post();

        try {

            // CHECK OTP
            if (!isset($data['otp']) || empty($data['otp']) || ($this->ticketRepository->validateOtp($data['guest_email'], $data['otp']) == false)) {
                $toRet['status'] = 'error';
                $toRet['message'] = 'Errore durante la creazione del ticket, OTP non valido';
                $toRet['data'] = [];
                return $toRet;
            }

            $ticket = $this->ticketRepository->createTicket($data);

            // se il ticket è stato salvato correttamente allora ha un id reale non nullo
            if (empty($ticket->id)){
                $toRet['status'] = 'error';
                $toRet['message'] = 'Errore durante la creazione del ticket, controlla i campi inseriti';
                $toRet['data'] = $ticket->errors;
            } else {
                $toRet['status'] = 'OK';
                $toRet['message'] = 'Ticket creato con successo';
                $toRet['data'] = $ticket->attributes;
            }

        } catch (\Exception $e) {
            Yii::getLogger()->log($e->getMessage() ."\n". $e->getTraceAsString(), Logger::LEVEL_ERROR);
            $toRet['status'] = 'error';
            $toRet['message'] = 'Errore durante la creazione del ticket';
            $toRet['data'] = [];
        }

        return $toRet;
    }


    #[Route('POST', 'microservices/api/v1/ticket/ask-opt', '/ticket/ticket-api/ask-opt')]
    #[OA\Post(path: 'microservices/api/v1/ticket/ask-opt', description: 'Ask OTP for confirm email', tags: ['TicketApiController'])]
    #[OA\Response(response: 200, description: 'return confirm of email')]
    public function actionAskOpt()
    {
        $toRet = [
            'status' => null,
            'message' => '',
            'data' => null,
        ];

        $request = Yii::$app->getRequest();
        $data = $request->post();

        try {

            $isEmailSent = $this->ticketRepository->askOtp($data['email']);

            if (is_array($isEmailSent)) {
                $toRet['status'] = 'error';
                $toRet['message'] = 'Errore durante l\'invio dell\'email con OTP';
                $toRet['data'] = $isEmailSent;
            } else if (!$isEmailSent) {
                $toRet['status'] = 'error';
                $toRet['message'] = 'Errore durante l\'invio dell\'email con OTP';
                $toRet['data'] = [];
            } else {
                $toRet['status'] = 'OK';
                $toRet['message'] = 'Email con OTP inviata con successo';
                $toRet['data'] = [];
            }

        } catch (\Exception $e) {
            Yii::getLogger()->log($e->getMessage() ."\n". $e->getTraceAsString(), Logger::LEVEL_ERROR);
            $toRet['status'] = 'error';
            $toRet['message'] = 'Errore durante l\'invio dell\'email con OTP';
            $toRet['data'] = [];
        }

        return $toRet;
    }
}