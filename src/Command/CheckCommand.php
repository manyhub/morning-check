<?php


namespace App\Command;


use App\DTO\Senario;
use App\Factory\ResultFactory;
use App\Notifier\NotifierInterface;
use Goutte\Client;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class CheckCommand extends Command
{
    private $notifer;

    private $serializer;

    public function __construct(NotifierInterface $notifer, SerializerInterface $serializer)
    {
        $this->notifer = $notifer;
        $this->serializer = $serializer;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:check')
            ->setDescription('Check app')
            ->addArgument('scenarios', InputArgument::REQUIRED, 'Scenarios: {"APP_NAME":{"uri":"https:\/\/myapp.com","login_data":{"_username":"login","_password":"pwd"},"needle":"hello world"}}');
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
        $results = [];
        $commandStatus = 0;

        $senarios = $this->serializer->deserialize($input->getArgument('scenarios'), "array <string," . Senario::class . ">", 'json');

        /** @var  Senario $scenario */
        foreach ($senarios as $appName => $scenario) {
            $start = new \DateTime();
            $client = new Client();
            $output->writeln("Started $appName");
            $crawler = $client->request('GET', $scenario->getUri());
            $output->writeln($crawler->getUri());
            $form = $crawler->selectButton('commit')->form();
            $crawler = $client->submit($form, $scenario->getFormData());
            $output->writeln($crawler->getUri());
            $output->writeln(\sprintf("Code %s app %s", $client->getResponse()->getStatus(), $appName));

            $find = false !== \strpos($crawler->text(), $scenario->getNeedle());

            $commandStatus += !$find;

            $end = new \DateTime();

            $result = ResultFactory::create($appName, $find, $start->diff($end));

            $results[] = $result;

            $output->writeln("Duration: " . $result->getDuration()->format("%s"));
            $output->writeln('Needle: ' . $result->isStatus());
        }

        $this->notifer->send($results);

        $output->writeln("END");

        return $commandStatus;
    }
}
