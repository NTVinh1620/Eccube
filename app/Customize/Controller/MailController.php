<?php
    
namespace Customize\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailController extends AbstractController
{
    /**
     * @Route("/mail", methods={"GET"})
     */
    public function sendEmail(\Swift_Mailer $mailer): Response
    {
        $email = (new \Swift_Message())
            ->setSubject('Test!')
            ->setFrom('rukitori1620@gmail.com')
            ->setTo('ntvinh1620@gmail.com')
            ->setBody('
                <h1 style="color: red;">Title</h1>
            ', 'text/html');

        $count = $mailer->send($email, $failures);

        return new Response($count);
    }
}
?>