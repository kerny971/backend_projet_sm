<?php

namespace App\Controller;

use App\Functions\Date as AppDate;
use App\Entity\MeetingOffer;
use App\Entity\MeetingOrder;
use App\Entity\User;
use App\Functions\CheckUser;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/dashboard', name: 'app_dashboard_')]
class DashboardController extends AbstractController
{
    private $jwtManager;

    private $tokenStorageInterface;

    private $em;

    private $errorAccess = [];

    private $checkUser;

    public function __construct(TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager, EntityManagerInterface $em)
    {
        $this->jwtManager = $jwtManager;
        $this->tokenStorageInterface = $tokenStorageInterface;
        $this->em = $em;

        # Check User
        $jwt_payload = $this->jwtManager->decode($this->tokenStorageInterface->getToken());
        $this->checkUser = new CheckUser($jwt_payload, $this->em->getRepository(User::class));
        $this->errorAccess = $this->checkUser->check();
    }



    #[Route('/meeting-offer', methods: "POST", name: 'meeting_offer_create')]
    public function createMeetingOffer(Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {

        # Check User Access
        $response = $this->_getErrorAccess();
        if (count($this->_getErrorAccess()) > 0) {
            return $this->json($response, $response['status_code']);
        }

        # Initialize Logging and Response
        $log = [];
        $current_datetime = AppDate::current();
        $response = [];
        $response['timestamp'] = $current_datetime->format('Y-m-d H:i:s');
        $response['timezone'] = $current_datetime->getTimezone()->getName();


        # Get Data from request
        $meeting_offer = new MeetingOffer();
        $meeting_offer->setId(Uuid::v4());
        $meeting_offer->setCreatedAt(\DateTimeImmutable::createFromMutable($current_datetime));
        $meeting_offer->setUpdatedAt($current_datetime);
        // $meeting_offer->setUser($user_id);
        $serializer->deserialize($request->getContent(), MeetingOffer::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $meeting_offer,
            'groups' => ['meeting_offer.create']
        ]);


        # Check Errors
        $errors = $validator->validate($meeting_offer);

        if (count($errors) > 0) {
            $response['status_code'] = Response::HTTP_BAD_REQUEST;
            $response['status'] = "errors";
            $response['error_code'] = "MEETING_OFFER_DATA_01";
            foreach ($errors as $err) {
                $response['data'][] = [
                    'message' => $err->getMessage(),
                    'propertyPath' => $err->getPropertyPath(),
                    'invalidValue' => $err->getInvalidValue(),
                ];
            }

            return $this->json($response, $response['status_code']);
        }


        # Persist into database
        try {
            $this->em->persist($meeting_offer);
            $this->em->flush();
        } catch (\Exception $e) {
            $response['status_code'] = Response::HTTP_INTERNAL_SERVER_ERROR;
            $response['status'] = "errors";
            $response['error_code'] = "MEETING_OFFER_DB_01";
            $response['message'] = 'Une erreur s\'est produite lors de l\'enregistrement de l\'offre de Meeting';
            $log[] = [
                'messageRaw' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'response' => $response
            ];
            return $this->json([$response, $log], $response['status_code']);
        }

        # send Response
        $response['status_code'] = Response::HTTP_ACCEPTED;
        $response['status'] = "success";
        $response['message'] = "Offre de meeting enregistrée";
        $response['data'] = $meeting_offer;
        return $this->json($response, $response['status_code'], [], [
            'groups' => ['meeting_offer.read']
        ]);
    }




    #[Route('/meeting-order', methods: "POST", name: 'meeting_order_create')]
    public function createMeetingOrder(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        # Check User Access
        $response = $this->_getErrorAccess();
        if (count($this->_getErrorAccess()) > 0) {
            return $this->json($response, $response['status_code']);
        }

        # Get user
        $user = $this->checkUser->getUser();

        # Initialize Logging and Response
        $log = [];
        $current_datetime = AppDate::current();
        $response = [];
        $response['timestamp'] = $current_datetime->format('Y-m-d H:i:s');
        $response['timezone'] = $current_datetime->getTimezone()->getName();


        # Récupérer l'entité MeetingOffer
        $id_meeting_offer = json_decode($request->getContent(), true);
        $meeting_offer = $em->getRepository(MeetingOffer::class)->find($id_meeting_offer);


        # Créer une commande Meeting Order
        $meeting_order = new MeetingOrder();
        $meeting_order->setId(Uuid::v4());
        $meeting_order->setCreatedAt(\DateTimeImmutable::createFromMutable($current_datetime));
        $meeting_order->setUpdatedAt($current_datetime);
        $meeting_order->setStartedAt(\DateTimeImmutable::createFromMutable($current_datetime));
        $meeting_order->setTotalOrder($meeting_offer->getPrice());
        $meeting_order->setUser($user);
        $em->persist($meeting_order);

        # Paiement avec Stripe
        try {
            $stripe = new StripeClient($_ENV['STRIPE_PRIVATE_KEY']);
            $payment_intent = $stripe->paymentIntents->create([
                'amount' => $meeting_order->getTotalOrder(),
                'currency' => $_ENV['APP_PAYMENT_CURRENCY']
            ]);

            $output = [
                'clientSecret' => $payment_intent->client_secret,
                // [DEV]: For demo purposes only, you should avoid exposing the PaymentIntent ID in the client-side code.
                'dpmCheckerLink' => "https://dashboard.stripe.com/settings/payment_methods/review?transaction_id={$payment_intent->id}",
            ];
        
            dd($output);
        } catch (\Exception $e) {
            dump($e);
            return $this->json([
                'message' => 'Erreur lors du paiement...'
            ]);
        }

        # Création de l'objet paiement
        

        # Flush DB Queries

        # Return Response



        return $this->json([]);
    }


    private function _getErrorAccess () {
        if (count($this->errorAccess) > 0) {

            # Données Response HTTP
            $response = [];
            $current_datetime = AppDate::current();
            $response['timestamp'] = $current_datetime->format('Y-m-d H:i:s');
            $response['timezone'] = $current_datetime->getTimezone()->getName();
            $response['status_code'] = $this->errorAccess['status_code'];
            $response['status'] = "errors";
            $response['error_code'] = $this->errorAccess['error_code'];
            $response['message'] = $this->errorAccess['message'];

            return $response;
        }

        return [];
    }

}
