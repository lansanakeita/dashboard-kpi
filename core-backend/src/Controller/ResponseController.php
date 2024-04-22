<?php

namespace App\Controller;

use App\Repository\ResponseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ResponseController extends AbstractController
{
    /**
     * Récupère la note moyenne globale à partir du référentiel des réponses.
     *
     * @param ResponseRepository $responseRepository Le référentiel des réponses pour récupérer la note moyenne.
     * @return JsonResponse La réponse JSON contenant la note moyenne.
     * @throws \Exception Si une erreur se produit lors de la récupération de la note moyenne.
     */
    #[Route('/responses/get-average-rating', name: 'api_average_rating')]
    public function getAverageRating(ResponseRepository $responseRepository): JsonResponse
    {
        try {
            $averageRating = $responseRepository
                ->findAverageRating();

            return $this->json(['averageRating' => (float)number_format($averageRating, 2)], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['error' => 'An error occurred ' . $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Récupère la note moyenne par client à partir du dépôt de réponses.
     *
     * @param ResponseRepository $responseRepository Le dépôt de réponses pour récupérer les données.
     * @throws \Exception S'il y a une erreur lors du processus de récupération.
     * @return JsonResponse La réponse JSON contenant les notes moyennes par client.
     */

    #[Route('/responses/average-rating-over-time/{year?}/{month?}', name: 'api_average_rating_over_time')]
    public function getAverageRatingOverTime(?int $year, ?int $month, ResponseRepository $responseRepository): JsonResponse
    {
        try {
            $averageRatingOverTime = $responseRepository
                ->findAverageRatingOverTime($year, $month);

            return $this->json(['ratingsOverTime' => $averageRatingOverTime]);
        } catch (\Exception $e) {
            return $this->json(['error' => 'An error occurred ' + $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
