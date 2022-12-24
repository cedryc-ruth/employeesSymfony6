<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerController extends AbstractController
{
    #[Route('/email', name: 'app_email')]
    public function sendEmail(MailerInterface $mailer): Response
    {   
        $email = (new Email())
            ->from('contact@employee.com')
            ->to('bob@sull.com')
            ->subject('Test email 2')
            //->text('Sending emails is fun again!');
            ->html('<p>See <b>Twig integration</b> for better HTML integration!</p>');

            
        $mailer->send($email);
        
        $this->addFlash('notice','Your mail has been sent successfully.');

        return $this->redirectToRoute('app_employee_index');
    }
}
