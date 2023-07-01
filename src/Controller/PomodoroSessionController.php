<?php

namespace App\Controller;

use App\Entity\PomodoroSession;
use App\Form\PomodoroSessionType;
use App\Repository\PomodoroSessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

class PomodoroSessionController extends AbstractController
{
    #[Route('/pomodoro/sessions', name: 'app_pomodoro_sessions')]
    public function showMySessions(EntityManagerInterface $entityManager, Security $security): Response
    {
        // Check if user is authenticated
        if (!$security->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        
        // Retrieve sessions created by the logged-in user
        $sessions = $entityManager->getRepository(PomodoroSession::class)->findBy(['author' => $security->getUser()]);
        
        return $this->render('pomodoro_session/index.html.twig', [
            'sessions' => $sessions,
        ]);
    }

    #[Route('/pomodoro/session/{id}', name: 'app_pomodoro_session_id')]
    public function showMySession(
        int $id,
        EntityManagerInterface $entityManager,
        Security $security
    ): Response {
        // Retrieve the PomodoroSession specified by the id
        $session = $entityManager->getRepository(PomodoroSession::class)->find($id);

        if (!$session) {
            throw $this->createNotFoundException('Session not found');
        }

        // Check if the currently logged-in user is the author of the PomodoroSession
        if ($security->getUser() !== $session->getAuthor()) {
            $this->addFlash('error', 'Nie masz uprawnień, aby wyświetlić tę sesję pomodoro!');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('pomodoro_session/session_detail.html.twig', [
            'session' => $session,
        ]);
    }

    #[Route('/pomodoro/session/{id}/delete', name: 'app_pomodoro_session_delete')]
    public function deleteSession(
        int $id,
        EntityManagerInterface $entityManager,
        Security $security
    ): Response {
        // Retrieve the PomodoroSession specified by the id
        $session = $entityManager->getRepository(PomodoroSession::class)->find($id);
    
        if (!$session) {
            throw $this->createNotFoundException('Session not found');
        }
    
        // Check if the currently logged-in user is the author of the PomodoroSession
        if ($security->getUser() !== $session->getAuthor()) {
            $this->addFlash('error', 'Nie masz uprawnień, aby usunąć tę sesję pomodoro!');
    
            return $this->redirectToRoute('app_home');
        }
    
        // Remove the PomodoroSession from the database
        $entityManager->remove($session);
        $entityManager->flush();
    
        $this->addFlash('success', 'Sesja pomodoro została usunięta.');
    
        return $this->redirectToRoute('app_home');
    }

    #[Route('/session/create', name: 'app_session_create')]
    public function createSession(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        // Check if user is authenticated
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        
        $form = $this->createForm(
            PomodoroSessionType::class,
            new PomodoroSession()
        );
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $sessionForm = $form->getData();
            
            // Set the author to the logged-in user and the creation date to the current date
            $sessionForm->setAuthor($this->getUser());
            $sessionForm->setCreated(new \DateTimeImmutable());
            
            $entityManager->persist($sessionForm);
            $entityManager->flush();

            $this->addFlash('success', 'Your session has been created');
            return $this->redirectToRoute('app_home');
        }


        return $this->render('pomodoro_session/create_session.html.twig',
        [
            'form' => $form
        ]
        );
    }
    
    #[Route('/restfulapi/export', name: 'app_restfulapi')]
    public function jsonExport(PomodoroSessionRepository $repository): JsonResponse
    {
        $sessions = $repository->findAll();

        $data = [];

        foreach ($sessions as $session) {
            $data[] = [
                'author' => $session->getAuthor(),
                'sessionLength' => $session->getSessionLength(),
                'breakLength' => $session->getBreakLength(),
                'sessionCount' => $session->getSessionCount(),
            ];
        }

        $json = json_encode($data);

        return new JsonResponse($json, 200, [], true);
    }
}

