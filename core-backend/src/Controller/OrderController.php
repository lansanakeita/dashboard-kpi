<?php

namespace App\Controller;

use App\Entity\ComplaintsAndReturns;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends AbstractController
{
    /**
     * Récupère les commandes associées à un nom d'utilisateur donné.
     *
     * @param OrderRepository $orderRepository L'objet repository des commandes.
     * @param string $clientName Le nom d'utilisateur de l'utilisateur.
     * @return Some_Return_Value La réponse JSON contenant les commandes.
     */
    #[Route('/orders/get-by-customer', name: 'get_customer_orders', methods: ['GET'])]
    public function getOrdersByCustomerNumber(UserRepository $userRepository)
    {
        $user = $this->getUser();
        $retrievedUser = $userRepository->find($user);

        // Vérifiez si l'utilisateur est un client
        if ($retrievedUser instanceof \App\Entity\Customer) {

            // Récupérer les commandes avec pagination
            $data = $retrievedUser->getOrders();


            // Récupérer les commandes avec pagination
            $orders = $retrievedUser->getOrders();

            // Récupérer le nombre total de commandes

            return $this->json(
                [
                    'orders' => $orders,
                ],
                Response::HTTP_OK
            );
        }
        return $this->json(['message' => 'No orders found for this client name.'], Response::HTTP_NOT_FOUND);
    }

    #[Route('/orders/complaint-return', name: 'create_complaint_return_order', methods: ['POST'])]
    public function createComplaintReturn(Request $request, EntityManagerInterface $manager, OrderRepository $orderRepository)
    {
        $data = json_decode($request->getContent(), true);
        $type = $data["type"];
        $order = $data["orderId"];
        $description = $data["description"];

        $orderId = $orderRepository->find($order);

        // Créez une nouvelle instance de ComplaintsAndReturns
        $complaint = new ComplaintsAndReturns();

        $complaint->setType($type);
        $complaint->setCreatedAt(new \DateTimeImmutable());
        $complaint->setDescription($description);
        $complaint->setOrders($orderId);

        // Persistez l'entité dans la base de données
        $manager->persist($complaint);
        $manager->flush();

        // Retournez une réponse appropriée
        return $this->json([
            'Complaint created successfully', Response::HTTP_CREATED
        ]);
    }
}
