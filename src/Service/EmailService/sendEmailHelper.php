<?php /** @noinspection ALL */


namespace App\Service\EmailService;


use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\User\UserInterface;

class sendEmailHelper extends AbstractController
{
    protected MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param string $subject
     * @param string $render
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function sendMail(string $subject, string $render)
    {
        $email = (new TemplatedEmail())
            ->to($this->getUser()->getEmail())
            ->subject($subject)
            ->htmlTemplate($render);

        $this->mailer->send($email);
    }

}