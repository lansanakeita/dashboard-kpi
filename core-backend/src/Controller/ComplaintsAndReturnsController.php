<?php

namespace App\Controller;

use App\Repository\ComplaintsAndReturnsRepository;
use App\Repository\DeliveryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ComplaintsAndReturnsController extends AbstractController
{
    #[Route('/returns/rate', name: 'app_complaints_and_returns', methods: ['GET'])]
    public function calculateReturnRate(
        ComplaintsAndReturnsRepository $cmRepository,
        DeliveryRepository $deliveryRepository
    ): JsonResponse {
        $totalDeliveries = $deliveryRepository->count([]);
        $totalReturns = $cmRepository->count(['type' => 'return']);

        $rate = ($totalDeliveries > 0) ? ($totalReturns / $totalDeliveries) * 100 : 0;
        $formattedRate = (float)number_format($rate, 2);

        return $this->json(['return_rate' => $formattedRate], JsonResponse::HTTP_OK);
    }
}
