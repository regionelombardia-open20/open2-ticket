<?php

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