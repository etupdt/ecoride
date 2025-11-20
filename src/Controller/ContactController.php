<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ContactFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class ContactController extends AbstractController
{

    #[Route('/contact', name: 'app_contact')]
    public function contact(
        Request $request, 
        MailerInterface $mailer,
    ): Response
    {

        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $email = (new Email())
                ->from($form->get('email')->getData())
                ->to('ecoride@ecoride.fr')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('demande de contact')
                ->text($form->get('message')->getData());
                // ->html('<p>See Twig integration for better HTML integration!</p>');

            $mailer->send($email);

            // return $this->redirectToRoute('app_covoiturages');

        }

        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'contactForm' => $form,
            'user' => $user,
        ]);

    }
    
}
