<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\PomodoroSession;
use App\Repository\PomodoroSessionRepository;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/api/pomodoro-sessions')]
class PomodoroSessionApiController extends AbstractController
{
    private $entityManager;
    private $pomodoroSessionRepository;

    public function __construct(EntityManagerInterface $entityManager, PomodoroSessionRepository $pomodoroSessionRepository)
    {
        $this->entityManager = $entityManager;
        $this->pomodoroSessionRepository = $pomodoroSessionRepository;
    }

    #[Route('/{id}', name: 'api_pomodoro_session_show', methods: ['GET'])]
    public function show(PomodoroSession $pomodoroSession): JsonResponse
    {
        return $this->json([
            'sessionLength' => $pomodoroSession->getSessionLength(),
            'breakLength' => $pomodoroSession->getBreakLength(),
            'sessionCount' => $pomodoroSession->getSessionCount(),
        ]);
    }

    #[Route('/api/pomodoro-sessions', name: 'api_pomodoro_sessions_list', methods: ['GET'])]
    public function list(PomodoroSessionRepository $repository): JsonResponse
    {
        $pomodoroSessions = $repository->findAllWithUser();

        $result = array_map(function ($pomodoroSession) {
            return [
                'sessionLength' => $pomodoroSession->getSessionLength(),
                'breakLength' => $pomodoroSession->getBreakLength(),
                'sessionCount' => $pomodoroSession->getSessionCount(),
                'author' => $pomodoroSession->getAuthor() ? $pomodoroSession->getAuthor()->getUsername() : null,
            ];
        }, $pomodoroSessions);

        return $this->json($result);
    }
}