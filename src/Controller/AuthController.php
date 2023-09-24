<?php

// src/Controller/AuthController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class AuthController extends AbstractController
{
    private $authenticationUtils;

    public function __construct(AuthenticationUtils $authenticationUtils)
    {
        $this->authenticationUtils = $authenticationUtils;
    }
    /**
     * @Route("/api/login", name="login", methods={"POST"})
     */
    public function login(Request $request, UserPasswordHasherInterface $hashPassword, JWTTokenManagerInterface $jwtManager): JsonResponse
    {
        // Récupérer les informations d'authentification depuis la requête
        $email = $request->get('email');
        $password = $request->get('password');

        // Vous n'avez pas besoin de ré-hasher le mot de passe ici, car vous utilisez UserPasswordHasherInterface

        // Vérifier l'authentification
        $user = $this->getUser();

        if ($user instanceof PasswordAuthenticatedUserInterface && $hashPassword->isPasswordValid($user, $password)) {
            // L'utilisateur est authentifié, générez le JWT
            $token = $jwtManager->create($user);
            return new JsonResponse(['token' => $token]);
        }

        // Si l'authentification échoue, renvoyer une réponse d'erreur
        return new JsonResponse(['message' => 'Nom d\'utilisateur ou mot de passe incorrect'], JsonResponse::HTTP_UNAUTHORIZED);
    }

    // ...


    /**
     * @Route("/api/logout", name="logout", methods={"POST"})
     */
    public function logout(): void
    {
        // Vous pouvez implémenter ici la logique de déconnexion si nécessaire
    }
}

