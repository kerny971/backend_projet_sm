<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController
{
    #[Route('/dashboard/login', name: 'app_login')]
    public function index(Request $request): Response
    {
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
