<?php

namespace App\Controller;

use App\Entity\User;
use App\Functions\CheckUser;
use App\Functions\Date as AppDate;
use App\Functions\ErrorAccessUserAPI;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DashboardEmailCheckingController extends AbstractController
{

    private array $errorAccess;

    private CheckUser $checkUser;

    private \DateTime $_currentDatetime;

    public function __construct(
        private readonly TokenStorageInterface $tokenStorageInterface,
        private readonly JWTTokenManagerInterface $jwtManager,
        private readonly EntityManagerInterface $em
    )
    {
        $this->_currentDatetime = AppDate::current();

        # Check User
        try {
            $jwt_payload = $this->jwtManager->decode($this->tokenStorageInterface->getToken());
            $this->checkUser = new CheckUser($jwt_payload, $this->em->getRepository(User::class));
            $this->errorAccess = $this->checkUser->check();
        }
        catch (\Exception $e) {
            $this->errorAccess[] = [
                'message' => "Une erreur s'est produite...",
                'status_code' => 500,
                'error_code' => "ERROR_SERVER"
            ];
        }
    }

    #[Route('/dashboard/email/checking', name: 'app_dashboard_email_checking')]
    public function index(): Response
    {
        # Check User Access
        # If $response have data there are some errors
        $response = ErrorAccessUserAPI::getErrorAccess(
            $this->errorAccess,
            $this->_currentDatetime
        );
        if (count($response) > 0) {
            return $this->json(
                [
                    "response" => $response
                ],
                $response['status_code']
            );
        }

        # Actions

        # Final Response
        return $this->json(
          [
              "response" => "",
          ],
            200
        );

    }

}
