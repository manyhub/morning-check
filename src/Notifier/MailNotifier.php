<?php

namespace App\Notifier;

class MailNotifier implements NotifierInterface
{
    private $mailer;

    private $environment;

    private $from;

    private $to;

    private $cc;


    /**
     * MailNotifier constructor.
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $environment
     * @param string $from
     * @param string[] $to
     * @param string[] $cc
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $environment, string $from, array $to = [], array $cc = [])
    {
        $this->mailer = $mailer;
        $this->environment = $environment;
        $this->mailer = $mailer;
        $this->from = $from;
        $this->to = $to;
        $this->cc = $cc;
    }


    public function send(array $results)
    {
        $body = $this->environment->render('mail.html.twig', ['date' => \date('d/m/Y'), 'apps' => $results]);
        $message = (new \Swift_Message(sprintf('[%s] Suivi Manymore du %s - Disponibilité des applications', \getenv('APP_ENV'), \date('d/m/Y'))))
            ->setFrom($this->from)
            ->setTo($this->to)
            ->setCc($this->cc)
            ->setBody(\strip_tags($body))
            ->addPart($body, 'text/html');

        $this->mailer->send($message);
    }

    public function getName()
    {
        return "Default mailer";
    }
}
