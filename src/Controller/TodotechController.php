<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodotechController extends AbstractController
{
    #[Route('/todotech', name: 'app_todotech')]
    public function index(): Response
    {
        return $this->render('todotech/index.html.twig');
    }
}
