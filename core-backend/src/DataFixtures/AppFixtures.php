<?php

namespace App\DataFixtures;

use App\Entity\ComplaintsAndReturns;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Customer;
use App\Entity\Order;
use App\Entity\Delivery;
use App\Entity\Question;
use App\Entity\Response;
use App\Services\AppService;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;
    private $appService;

    public function __construct(
        UserPasswordHasherInterface $passwordEncoder,
        AppService $appService
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->appService = $appService;
    }

    public function load(ObjectManager $manager): void
    {
        // Création des utilisateurs administrateurs
        $this->createAdmins($manager);

        // Création des utilisateurs clients et customers
        $this->createCustomersAndUsers($manager);

        // Création des commandes et livraisons
        $this->createOrdersAndDeliveries($manager);

        // Création des questions
        $this->createQuestions($manager);

        // Création des réponses avec commentaires aléatoires
        $this->createResponses($manager, $this->appService);

        // Création des plaintes et retours
        $this->createComplaintsAndReturns($manager);
    }

    private function createAdmins(ObjectManager $manager): void
    {
        // Liste des admins
        $admins = [
            ['lansana@rapidvax.com', 'ROLE_ADMIN', 'lansana', 'admin'],
            ['brandon@rapidvax.com', 'ROLE_ADMIN', 'brandon', 'admin'],
            ['erica@rapidvax.com', 'ROLE_ADMIN', 'erica', 'admin'],
            ['laura@rapidvax.com', 'ROLE_ADMIN', 'laura', 'admin'],
            ['mariama@rapidvax.com', 'ROLE_ADMIN', 'mariama', 'admin'],
            ['redouane@rapidvax.com', 'ROLE_ADMIN', 'redouane', 'admin'],
        ];

        foreach ($admins as $adminData) {
            $admin = new User();
            $admin->setEmail($adminData[0])
                ->setRoles([$adminData[1]])
                ->setPassword($this->passwordEncoder->hashPassword($admin, 'adminpass'))
                ->setUsername($adminData[2]);
            $manager->persist($admin);
        }

        $manager->flush();
        $manager->clear();
    }

    private function createCustomersAndUsers(ObjectManager $manager): void
    {
        // Liste des customers et users
        $customers = [
            ['GlobalTech', 'contact@globaltech.com', 'globaltech_user'],
            ['InnovateInc', 'info@innovateinc.com', 'innovateinc_user'],
            ['AlphaSystems', 'contact@alphasystems.com', 'alphasystems_user'],
            ['BetaSolutions', 'info@betasolutions.com', 'betasolutions_user'],
            ['TechFrontiers', 'contact@techfrontiers.com', 'techfrontiers_user'],
            ['PharmaCore', 'contact@pharmacore.pharmaexample.com', 'pharmacore_user'],
            ['BioHealSolutions', 'info@biohealsolutions.pharmaexample.com', 'bioheal_user'],
            ['MediTechLabs', 'contact@meditechlabs.pharmaexample.com', 'meditech_user'],
            ['HealthGenics', 'info@healthgenics.pharmaexample.com', 'healthgenics_user'],
            ['CureSynthetics', 'contact@curesynthetics.pharmaexample.com', 'curesynth_user']
        ];


        foreach ($customers as $customerData) {
            $customer = new Customer();
            $customer->setName($customerData[0])
                ->setCustomerNumber($this->generateCustomerNumber($customerData[0]));
            $customer
                ->setEmail($customerData[1])
                ->setRoles(['ROLE_CLIENT'])
                ->setPassword($this->passwordEncoder->hashPassword($customer, 'clientpass'))
                ->setUsername($customerData[2]);

            $manager->persist($customer);
        }

        $manager->flush();
        $manager->clear();
    }

    private function createOrdersAndDeliveries(ObjectManager $manager): void
    {
        // Statuts possibles pour les livraisons
        $deliveryStatuses = ['preparing', 'in_transit', 'out_for_delivery', 'delivered'];
        $dayTimes = ['nocturne', 'diurne'];
        $weekTimes = ['weekday', 'weekend'];

        // Récupérer tous les clients
        $customers = $manager->getRepository(Customer::class)->findAll();

        for ($i = 1; $i <= 100; $i++) {
            $order = new Order();
            $delivery = new Delivery();

            // Choisir un client aléatoire pour la commande
            $customer = $customers[array_rand($customers)];
            // Date de commande entre 1 Jan 2023 et 11 Jan 2024
            $orderedAt = new \DateTimeImmutable('2023-01-01 +' . rand(0, 365 * 24 * 60 * 60) . ' seconds');
            $order
                ->setCustomer($customer)
                ->setAmount(rand(5000, 100000))
                ->setOrderNumber('ORD' . str_pad((string)$i, 4, '0', STR_PAD_LEFT))
                ->setOrderedAt($orderedAt);

            $manager->persist($order);

            // Calculer un délai aléatoire entre 1 et 7 jours
            $daysToAdd = rand(0, 7);
            $deliveryExpected = (clone $orderedAt)->modify('+' . $daysToAdd . ' days');

            // S'assurer que l'heure est comprise entre 8h et 22h
            $hour = rand(8, 22);
            $deliveryExpected = $deliveryExpected->setTime($hour, 0);

            // Décider si la livraison sera à l'heure, en avance ou en retard
            $deliveryOutcome = rand(0, 2);
            $deliveredAt = clone $deliveryExpected;

            if ($deliveryOutcome === 1) { // Livraison en avance
                $deliveredAt = $deliveredAt->modify('-' . rand(1, 24) . ' hours');
            } elseif ($deliveryOutcome === 2) { // Livraison en retard
                $deliveredAt = $deliveredAt->modify('+' . rand(1, 24) . ' hours');
            }

            // Définir les détails de la livraison
            $delivery->setOrders($order)
                //$delivery
                ->setDistance(rand(1, 1000))
                ->setDeliveryNumber('DEL' . str_pad((string)$i, 4, '0', STR_PAD_LEFT))
                ->setDeliveredAt($deliveredAt)
                ->setDeliveryExpected($deliveryExpected)
                ->setDayTime($dayTimes[array_rand($dayTimes)])
                ->setWeekTime($weekTimes[array_rand($weekTimes)]);

            // Pour les 100 premières livraisons, assigner des statuts spécifiques
            if ($i <= 100) {
                $delivery->setStatus($deliveryStatuses[array_rand($deliveryStatuses)]);
            } else {
                $delivery->setStatus($deliveryOutcome === 2 ? 'delayed' : 'delivered');
            }

            $manager->persist($delivery);
        }

        $manager->flush();
        $manager->clear();
    }

    private function createQuestions(ObjectManager $manager): void
    {
        $questions = [
            'Êtes-vous satisfait de la rapidité de la livraison ?',
            'Recommanderiez-vous notre entreprise à vos amis ou à votre famille ?',
            'Êtes-vous satisfait de votre expérience avec notre entreprise ?'
        ];

        foreach ($questions as $qText) {
            $question = new Question();
            $question->setDescription($qText);
            $manager->persist($question);
        }

        $manager->flush();
        $manager->clear();
    }

    private function createResponses(
        ObjectManager $manager,
        AppService $appService
    ): void {
        // Récupérer toutes les questions et livraisons
        $questions = $manager->getRepository(Question::class)->findAll();
        $deliveries = $manager->getRepository(Delivery::class)->findAll();
        $customerComments = [
            'Livraison rapide et produit en parfait état. Service clientèle exceptionnel!',
            'Incroyablement efficace, la commande est arrivée plus tôt que prévu. Très satisfait!',
            'Service impeccable, livraison soignée et ponctuelle. Je recommande vivement!',
            'Livraison dans les délais, mais l\'emballage aurait pu être mieux.',
            'Correct, mais le suivi de commande n\'était pas très clair.',
            'Délais respectés, mais communication avec le service client à améliorer.',
            'Livraison tardive et colis endommagé. Peut mieux faire.',
            'Délai trop long et service client peu réactif. Déçu de cette expérience.',
            'Commande incomplète à l\'arrivée et difficulté pour joindre le service client.',
            'Colis reçu endommagé et processus de remplacement lent et compliqué.'
        ];


        foreach ($deliveries as $delivery) {
            foreach ($questions as $question) {
                $response = new Response();
                $randomComment = $customerComments[array_rand($customerComments)];
                $classification = $appService->classifierCommentaire($randomComment);

                $value = 0;
                if ($classification === 'Excellent') {
                    $value = random_int(9, 10) / 2; // Génère une note entre 4.5 et 5
                } elseif ($classification === 'Satisfaisant') {
                    $value = random_int(6, 8) / 2; // Génère une note entre 3 et 4
                } else {
                    $value = random_int(2, 5) / 2; // Génère une note entre 1 et 2.5
                }

                $response->setQuestion($question)
                    ->setDelivery($delivery)
                    ->setValue($value)
                    ->setComment($randomComment);

                $manager->persist($response);
            }
        }

        $manager->flush();
        $manager->clear();
    }

    private function createComplaintsAndReturns(ObjectManager $manager): void
    {
        $types = ['complaint', 'return'];
        $complaintDescriptions = [
            "Vaccin reçu avec un emballage endommagé",
            "Retard dans la livraison du vaccin, dépassement de la date prévue",
            "Température de conservation inadéquate à la réception du vaccin",
            "Vaccins reçus avec des étiquettes manquantes ou incorrectes",
        ];
        $returnDescriptions = [
            "Retour en raison de vaccins expirés",
            "Retour pour surplus de commande de vaccins",
            "Retour de vaccins suite à une erreur de commande",
        ];
        $orders = $manager->getRepository(Order::class)->findAll();
        $description = '';

        for ($i = 0; $i < 2531; $i++) {
            $complaintAndReturn = new ComplaintsAndReturns();
            $type = $types[array_rand($types)];

            if ($i % 20 == 0) {
                $description = $type === 'complaint' ? $complaintDescriptions[array_rand($complaintDescriptions)] : $returnDescriptions[array_rand($returnDescriptions)];
            }

            $complaintAndReturn->setType($type);
            $complaintAndReturn->setDescription($description);

            // Obtenir une commande aléatoire
            $order = $orders[array_rand($orders)];
            $complaintAndReturn->setOrders($order);

            // Fixer la date de création à une date postérieure à la date de commande
            $createdAt = clone $order->getOrderedAt();
            $createdAt->modify('+' . rand(7, 30) . ' days');
            $complaintAndReturn->setCreatedAt($createdAt);

            $manager->persist($complaintAndReturn);
        }

        $manager->flush();
        $manager->clear();
    }

    private function generateCustomerNumber(string $name): string
    {
        return 'CLI' . str_pad((string)rand(0, 9999), 4, '0', STR_PAD_LEFT) . substr($name, 0, 2);
    }
}
