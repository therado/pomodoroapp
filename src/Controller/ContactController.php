<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function addContact(
        Request $request,
        ContactRepository $contacs
    ): Response {
        
        //Check if user is authenticated
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        
        $form = $this->createForm(
            ContactFormType::class,
            new Contact()
        );
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            $contact->setAuthor($this->getUser());
            $contacs->save($contact, true);
    
            $this->addFlash('success', 'Report has been sent');
            return $this->redirectToRoute('app_home');
        }
    
        return $this->renderForm(
            'contact/index.html.twig',
            [
                'form' => $form
            ]
        );
    }
}