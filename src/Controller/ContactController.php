<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Récuperation de données du formulaire et les mettre dans des varibles
            $address = $form->get('email')->getData();
            $subject = $form->get('subject')->getData();
            $content = $form->get('content')->getData();

            //create email
            $email = (new Email())
                ->from($address)
                ->to('admin@admin.com')
                ->subject($subject)
                ->text($content);

            //to send email
            $mailer->send($email);

            return $this->redirectToRoute('app_success');
        }

        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'form' => $form
        ]);
    }

    #[Route('/contact/success', name: 'app_success')]
    public function success(): Response
    {
        return $this->render('success/index.html.twig', [
            'controller_name' => 'SuccessController',
        ]);
    }
}
