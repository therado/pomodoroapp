<?php

namespace App\Controller;

use App\Entity\PomodoroSession;
use App\Form\PomodoroSessionType;
use App\Repository\PomodoroSessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PomodoroSessionController extends AbstractController
{
    #[Route('/pomodoro/session', name: 'app_pomodoro_session')]
    public function index(): Response
    {
        return $this->render('pomodoro_session/index.html.twig');
    }

    #[Route('/pomodoro/session/create', name: 'app_pomodoro_session_create')]
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
            $session->save($sessionForm, true);
    
            $this->addFlash('success', 'Your session has been created');
            return $this->redirectToRoute('app_home');
        }


        return $this->render('pomodoro_session/index.html.twig',
        [
            'form' => $form
        ]
    );
    }
}
