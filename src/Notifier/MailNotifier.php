<?php

namespace App\Notifier;

use App\DTO\Result;

class MailNotifier implements NotifierInterface
{
    /** @var \Swift_Mailer  */
    private $mailer;

    /** @var \Twig_Environment  */
    private $environment;

    /** @var string  */
    private $from;

    /** @var array  */
    private $to;

    /** @var array  */
    private $cc;


    /**
     * MailNotifier constructor.
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $environment
     * @param string $from
     * @param string $to
     * @param string $cc
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $environment, string $from, string $to, string $cc)
    {
        $this->mailer = $mailer;
        $this->environment = $environment;
        $this->mailer = $mailer;
        $this->from = $from;
        $this->to = explode(';', $to);
        $this->cc = explode(';', $cc);
    }

    /**
     * @param Result[] $results
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function send(array $results)
    {
        $body = $this->environment->render('mail.html.twig', ['date' => \date('d/m/Y'), 'apps' => $results]);

        $message = (new \Swift_Message(sprintf('[%s] Suivi Manymore du %s - Disponibilité des applications', \getenv('APP_ENV'), \date('d/m/Y'))))
            ->setFrom($this->from)
            ->setCc($this->cc)
            ->setTo($this->to)
            ->setBody(\strip_tags($body))
            ->addPart($body, 'text/html');

        foreach ($results as $app => $result){
            $attachment = new \Swift_Attachment($result->getScreen(),$app . '.png','image/png');
            $message->attach($attachment);
        }

        $this->mailer->send($message);
    }

    public function getName()
    {
        return "Default mailer";
    }
}
