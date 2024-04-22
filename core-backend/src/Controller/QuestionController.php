<?php

namespace App\Controller;

use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class QuestionController extends AbstractController
{
    /**
     * Récupère une question depuis la base de données.
     *
     * @param QuestionRepository $questionRepository Le repository pour accéder aux données des questions.
     * @throws None
     * @return JsonResponse La réponse JSON contenant les données de la question récupérée.
     */
    #[Route('/questions/get-questions', methods: ['GET'])]
    public function getQuestions(
        QuestionRepository $questionRepository,
        Request $request
    ): JsonResponse {
        try {
            // Récupérer les paramètres de pagination de la requête
            $page = $request->query->get('page', 1); // Page par défaut est 1
            $limit = $request->query->get('limit', 10); // Taille de la page par défaut

            // Calculer l'offset
            $offset = ($page - 1) * $limit;

            // Récupérer les questions avec pagination
            $questions = $questionRepository->findBy([], null, $limit, $offset);

            // Récupérer le nombre total de questions
            $totalQuestions = $questionRepository->count([]);
            $data = [];

            foreach ($questions as $question) {
                $questionData = [
                    'id' => $question->getId(),
                    'description' => $question->getDescription(),
                ];

                $questionData['_links'] = [
                    'self' => [
                        'href' => $this->generateUrl('question_detail', ['id' => $question->getId()])
                    ],
                ];

                $data[] = $questionData;
            }

            $paginationData = [
                'currentPage' => $page,
                'pageSize' => $limit,
                'totalItems' => $totalQuestions,
                'totalPages' => ceil($totalQuestions / $limit),
            ];

            return $this->json(
                [
                    'questions' => $data,
                    'pagination' => $paginationData
                ],
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /** */
    #[Route('/questions/get-questions/{id}', name: 'question_detail', methods: ['GET'])]
    public function getQuestionDetail(QuestionRepository $questionRepository, int $id): JsonResponse
    {
        try {
            $question = $questionRepository->find($id);

            if (!$question) {
                return $this->json(
                    ['message' => 'Question not found'],
                    Response::HTTP_NOT_FOUND
                );
            }

            $questionData = [
                'id' => $question->getId(),
                'description' => $question->getDescription(),
            ];

            return $this->json(
                [
                    'questions' => $questionData,
                    'pagination' => []
                ],
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
