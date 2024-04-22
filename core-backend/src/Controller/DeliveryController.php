<?php

namespace App\Controller;

use App\Repository\DeliveryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Routing\Annotation\Route;

class DeliveryController extends AbstractController
{
    /**
     * Undocumented function
     *
     * @param DeliveryRepository $deliveryRepository
     * @return void
     */
    #[Route('/delivery-by-delay', name: 'delivery_by_delay', methods: ['GET'])]
    public function getByDelai(DeliveryRepository $deliveryRepository)
    {
        $dataAll = $deliveryRepository->findAll();
        $dataDelay = $deliveryRepository->findByDelay();

        $countDataAll = count($dataAll);
        $countDataDelay = count($dataDelay);

        $delay = ($countDataAll > 0) ? $countDataDelay / $countDataAll : 0;
        $data = $delay * 100;

        return $this->json(['le % est ' => $data]);
    }


    #[Route('/delivery-by-advance', name: 'delivery_by_advance', methods: ['GET'])]
    public function getByAdvance(DeliveryRepository $deliveryRepository)
    {
        $dataAll = $deliveryRepository->findAll();
        $dataAdvance = $deliveryRepository->findByAdvance();

        $countDataAll = count($dataAll);
        $countDataAdvance = count($dataAdvance);

        $delay = ($countDataAll > 0) ? $countDataAdvance / $countDataAll : 0;
        $data = $delay * 100;

        return $this->json(['le % est' => $data]);
    }


    /**
     * Retour la liste de commandes livrées a temps, en retard et en avance
     *
     * @param DeliveryRepository $deliveryRepository
     * @return void
     */
    #[Route('/delivery-by-status', name: 'delivery_by_status', methods: ['GET'])]
    public function getDeliveryByStatus(DeliveryRepository $deliveryRepository)
    {
        $dataAll        = $deliveryRepository->findAll();
        $listDelay      = $deliveryRepository->findByDelay();
        $listAdvance    = $deliveryRepository->findByAdvance();
        $listOnTime     = $deliveryRepository->findByOnTime();

        $countDataAll       = count($dataAll);
        $countDataDelay     = count($listDelay);
        $countDataAdvance   = count($listAdvance);
        $countDataOnTime    = count($listOnTime);

        $dataDelay      = ($countDataAll > 0) ? ($countDataDelay / $countDataAll) * 100 : 0;
        $dataAdvance    = ($countDataAll > 0) ? ($countDataAdvance / $countDataAll) * 100 : 0;
        $dataOneTime    = ($countDataAll > 0) ? ($countDataOnTime / $countDataAll) * 100 : 0;


        return $this->json(
            [
                'onTime' => $dataOneTime,
                ' delay: ' => $dataDelay,
                'advance :' => $dataAdvance,
            ]
        );
    }



    // récupérer la liste des livraisons par nuits
    #[Route('/delivery-by-diurne', name: 'delivery_by_diurne', methods: ['GET'])]
    public function getByDiune(DeliveryRepository $deliveryRepository)
    {
        $data = $deliveryRepository->findBy(["dayTime" => "diurne"]);
        return $this->json(['data' => $data]);
    }

    // récupérer la liste des livraisons
    #[Route('/delivery', name: 'app_delivery', methods: ['GET'])]
    public function getDelivery(DeliveryRepository $deliveryRepository)
    {
        $data = $deliveryRepository->findAll();
        return $this->json(['data' => $data]);
    }


    #[Route('/deliveries/daytime-rate', name: 'deliveries_daytime', methods: ['GET'])]
    public function getDaytimeDeliveriesRate(DeliveryRepository $deliveryRepository)
    {
        try {
            $deliveries = $deliveryRepository->count([]);
            $diurneCount = $deliveryRepository->countByDayTime("diurne");
            $noctureCount = $deliveryRepository->countByDayTime("nocturne");

            return new JsonResponse([
                'dayTimeDelivery' => [
                    'diurne' => ($diurneCount / $deliveries) * 100,
                    'nocturne' => ($noctureCount / $deliveries) * 100
                ]
            ], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/deliveries/trends', name: 'deliveries_trends', methods: ['GET'])]
    public function getDeliveryTrends(Request $request, DeliveryRepository $deliveryRepository)
    {
        try {
            $startDate = new \DateTime($request->query->get('start'));
            $endDate = new \DateTime($request->query->get('end'));

            if (!$startDate || !$endDate) {
                return $this->json(['error' => 'Les paramètres de date sont invalides.'], Response::HTTP_BAD_REQUEST);
            }

            $trends = $deliveryRepository->getDeliveryTrends($startDate, $endDate);

            return $this->json(['deliveriesTrends' => $trends]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/delivery-rates', name: 'delivery_rates')]
    public function getDeliveryRates(DeliveryRepository $deliveryRepository): Response
    {
        $rates = $deliveryRepository->getDeliveryRates();

        return $this->json([
            'deliveryRates' => $rates
        ]);
    }

}
