<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MeController extends AbstractController
{

    #[Route('me', name: 'get_me', methods: ['GET'])]
    public function __invoke(UserRepository $userRepository): Response
    {
        $userFromPayload = $this->getUser();
        $retrievedUser = $userRepository->find($userFromPayload);

        if (!$retrievedUser) {
            return $this->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        if ($retrievedUser instanceof \App\Entity\Customer) {
            return $this->json([
                'users' => [
                    'id' => $retrievedUser->getId(),
                    'username' => $retrievedUser->getUsername(),
                    'email' => $retrievedUser->getEmail(),
                    'roles' => $retrievedUser->getRoles(),
                    'customer_name' => $retrievedUser->getName(),
                    'customer_number' => $retrievedUser->getCustomerNumber(),
                ]
            ], Response::HTTP_OK);
        }

        $user = [
            'users' => [
                'id' => $retrievedUser->getId(),
                'username' => $retrievedUser->getUsername(),
                'email' => $retrievedUser->getEmail(),
                'roles' => $retrievedUser->getRoles(),
            ]
        ];

        return $this->json($user, Response::HTTP_OK);
    }
}
