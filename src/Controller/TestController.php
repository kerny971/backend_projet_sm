<?php

namespace App\Controller;

use App\Entity\User;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/test', name: 'app_test_')]
class TestController extends AbstractController
{

    #[Route('/', name: 'home')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TestController.php',
        ]);
    }

    #[Route('/user', methods: 'POST', name: 'save_user')]
    public function saveUser(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): JsonResponse
    {
        try {
            $user = New User();

            $user->setId(Uuid::v4());
            $user->setEmail('laguerre.kerny@gmail.com');
            $user->setRoles([]);
            $user->setPassword($hasher->hashPassword($user, 'admin'));
            $user->setPseudo('kerny');
            $user->setFirstname('Kerny');
            $user->setLastname('LAGUERRE');
            $user->setCreatedAt((New DateTimeImmutable()));
            $user->setUpdatedAt((New DateTimeImmutable()));

            $em->persist($user);
            $em->flush();
        } catch (Exception $e) {
            return $this->json([
                'message' => "Erreur lors de l'enregistrement de l'utilisateur",
                'raw_message' => $e->getMessage(),
                'status_code' => 500
            ]);
        }

        
        return $this->json([
            'message' => "L'utilisateur " . $user->getPseudo() . " à bien été enregistrer sous l'identifiant " . $user->getId(),
            'status_code' => 200
        ]);
    }

    #[Route('/user', methods: 'GET', name: 'current_user')]
    public function getCurrentUser(): JsonResponse
    {

        return $this->json([
            'message' => 'Route Setting Up !'
        ]);
    }
}
