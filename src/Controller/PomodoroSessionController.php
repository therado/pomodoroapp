<?php

namespace App\Controller;

use App\Entity\PomodoroSession;
use App\Form\PomodoroSessionType;
use App\Repository\PomodoroSessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

class PomodoroSessionController extends AbstractController
{
    #[Route('/pomodoro/sessions', name: 'app_pomodoro_sessions')]
    public function showMySessions(EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();
        
        $sessions = $entityManager->getRepository(PomodoroSession::class)->findBy(['author' => $user]);
        
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
        $session = $entityManager
            ->getRepository(PomodoroSession::class)
            ->find($id);

        if (!$session) {
            throw $this->createNotFoundException('Session not found');
        }

        // Check if the currently logged-in user is the author of the PomodoroSession
        $currentUser = $security->getUser();
        if ($currentUser !== $session->getAuthor()) {
            $this->addFlash('error', 'Nie masz uprawnień, aby wyświetlić tę sesję pomodoro!');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('pomodoro_session/session_detail.html.twig', [
            'session' => $session,
        ]);
    }

    #[Route('/session/create', name: 'app_session_create')]
    public function createSession(
        Request $request,
        PomodoroSessionRepository $session
    ): Response {

        //Check if user is authenticated
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
            $sessionForm->setAuthor($this->getUser());
            $sessionForm->setCreated(new \DateTimeImmutable());
            $session->save($sessionForm, true);

            $this->addFlash('success', 'Your session has been created');
            return $this->redirectToRoute('app_home');
        }


        return $this->render('pomodoro_session/create_session.html.twig',
        [
            'form' => $form
        ]
        );
    }
}
