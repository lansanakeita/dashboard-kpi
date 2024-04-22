<?php

namespace App\Controller;

use App\Repository\ResponseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RecommandationController extends AbstractController
{
    #[Route('/recommandation/rate', name: 'api_recommandation_rate')]
    public function index(ResponseRepository $responseRepository): JsonResponse
    {
        try {
            $data = $responseRepository->getAverageRecommandationScore();
            $totalValue = $data['totalValue'];
            $totalCount = $data['totalCount'];

            // calcul du taux de recommandation
            $maxPossibleScore = $totalCount * 5;
            $recommendationRate = (float)number_format(($totalValue / $maxPossibleScore) * 100, 2);

            return $this->json(['recommendedRate' => $recommendationRate], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
