<?php


namespace App\Command;


use Goutte\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Twig\Environment;

class CheckCommand extends Command
{
    private $mailer;

    private $environment;

    /**
     * CheckCommand constructor.
     * @param \Swift_Mailer $mailer
     * @param Environment $environment
     */
    public function __construct(\Swift_Mailer $mailer, Environment $environment)
    {
        $this->mailer = $mailer;
        $this->environment = $environment;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:check')
            ->setDescription('Check app')
            ->addArgument('from', InputArgument::REQUIRED)
	    ->addArgument('to', InputArgument::REQUIRED)
	    ->addArgument('scenarios',InputArgument::REQUIRED, 'Scenarios: {"APP_NAME":{"uri":"https:\/\/myapp.com","login_data":{"_username":"login","_password":"pwd"},"needle":"hello world"}}')
	    ->addArgument('cc', InputArgument::OPTIONAL);
    }

    /**
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $result = [];
        $commandStatus = 0;

        foreach (\json_decode($input->getArgument('scenarios'), true) as $appName => $scenario) {
            $client = new Client();
            $output->writeln("Started $appName");
            $crawler = $client->request('GET', $scenario['uri']);
            $output->writeln($crawler->getUri());
            $form = $crawler->selectButton('commit')->form();
            $crawler = $client->submit($form, $scenario['login_data']);
            $output->writeln($crawler->getUri());
            $output->writeln(\sprintf("Code %s app %s", $client->getResponse()->getStatus(), $appName));
            if (false !== \strpos($crawler->text(), $scenario['needle'])) {
                $result[$appName] = 'ok';
            } else {
                $result[$appName] = 'ko';
                $commandStatus++;
            }

            $output->writeln('needle: ' . $result[$appName]);

        }

	$body = $this->environment->render('mail.html.twig', ['date'=>\date('d/m/Y'),'apps'=>$result]);
        $message = (new \Swift_Message(sprintf('[%s] Suivi Manymore du %s', getenv('APP_ENV') , \date('d/m/Y'))))
            ->setFrom($input->getArgument('from'))
	    ->setTo($input->getArgument('to'))
	    ->setCc($input->getArgument('cc'))
	    ->setBody(\strip_tags($body))
            ->addPart($body,'text/html');

        $this->mailer->send($message);

        $output->writeln("END");

        return $commandStatus;
    }
}
