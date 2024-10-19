<?php

namespace App\Controller;

use App\Document\Response as ControllerResponse;
use App\Document\ResponseData as ControllerResponseData;
use App\Entity\User;
use App\Functions\Date as AppDate;
use App\Message\EmailCodeConfirmationMessage;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{

    private ControllerResponse $_responseController;
    private DateTime $_currentDatetime;

    public function __construct()
    {
        # Initialisation de la date et de l'objet RESPONSE DOCUMENT
        $this->_currentDatetime = AppDate::current();
        $this->_responseController = new ControllerResponse();
        $this->_responseController->setTimestamp($this->_currentDatetime)
                    ->setTimezone($this->_currentDatetime->getTimezone()->getName());
    }


    #[Route('/register', name: 'app_register', methods: 'POST')]
    public function register(
        Request $request,
        SerializerInterface $serializer,
        UserPasswordHasherInterface $hasher,
        ValidatorInterface $validator,
        EntityManagerInterface $em,
        MessageBusInterface $messageBus
    ): JsonResponse
    {

        # user
        $email = json_decode($request->getContent())->email;
        $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($user) {
            if ($user->isConfirmed()) {
                $this->_onRegisterUserExist();
                return $this->json(
                    [
                        'response' => $this->_responseController
                    ],
                    $this->_responseController->getStatusCode()
                );
            } else {
                $user->setUpdatedAt($this->_currentDatetime);
                $serializer->deserialize($request->getContent(), User::class, 'json', [
                    AbstractNormalizer::OBJECT_TO_POPULATE => $user,
                    'groups' => ['user.create']
                ]);
            }
        }
        else {

            # Create User Entity
            $user = new User();
            $user->setId(Uuid::v4())
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($this->_currentDatetime))
                ->setUpdatedAt($this->_currentDatetime);

            $serializer->deserialize($request->getContent(), User::class, 'json', [
                AbstractNormalizer::OBJECT_TO_POPULATE => $user,
                'groups' => ['user.create']
            ]);
        }
        

        # check User Entity Data
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            $this->_onRegisterUserErrorsResponse($errors);
            return $this->json(
                [
                    'response' => $this->_responseController
                ],
                $this->_responseController->getStatusCode()
            );
        }

        # Hashing MDP
        $user->setPassword($hasher->hashPassword($user, $user->getPassword()));

        # Persist User
        $em->persist($user);

        # Flush Queries
        try {
            $em->flush();
        }
        catch (\Exception) {
            $this->_onRegisterDatabaseErrors();
            return $this->json(
                [
                    'response' => $this->_responseController,
                ], 
                $this->_responseController->getStatusCode()
            );
        }


        # TODO : EMAIL CODE CONFIRMATION ASYNCHRONE AVEC SYMFONY MESSENGER
        $messageBus->dispatch(new EmailCodeConfirmationMessage($user->getId()));


        # Send HTTP Response and Data
        $this->_onRegisterSuccessResponse();
        return $this->json(
            [
                'data' => $user,
                'response' => $this->_responseController
            ], 
            $this->_responseController->getStatusCode(),
        );
    }


    private function _onRegisterUserErrorsResponse (ConstraintViolationListInterface $errors): void {
        $this->_responseController->setStatusCode(Response::HTTP_BAD_REQUEST)
                        ->setStatus('errors')
                        ->setErrorCode('USER_REGISTRATION_01')
                        ->setMessage('Une erreur est présente dans le formulaire.');

            foreach ($errors as $err) {
                $data = new ControllerResponseData();
                $data->setMessage($err->getMessage())
                                ->setPropertyPath($err->getPropertyPath())
                                ->setInvalidValue($err->getInvalidValue());
                $this->_responseController->addResponseDatas($data);
            }
    }

    private function _onRegisterDatabaseErrors (): void {
        $this->_responseController->setStatus('errors')
            ->setErrorCode('USER_REGISTRATION_DB_01')
            ->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->setMessage('Une erreur s\'est produite lors de l\'enregistrement de l\'utilisateur');
    }

    private function _onRegisterSuccessResponse(): void {
        $this->_responseController->setStatusCode(Response::HTTP_ACCEPTED)
            ->setStatus('success')
            ->setMessage('Utilisateur Enregistré');
    }

    private function _onRegisterUserExist (): void {
        $this->_responseController->setStatusCode(Response::HTTP_BAD_REQUEST)
            ->setStatus('errors')
            ->setErrorCode('USER_REGISTRATION_01')
            ->setMessage('Une erreur s\'est produite lors de l\'enregistrement de l\'utilisateur');
    }
}