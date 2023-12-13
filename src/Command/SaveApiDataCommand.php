<?php

namespace App\Command;

use App\DataApihHelpers\ApiDataManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:save-api-data',
    description: 'Saving posts from api call'
)]
class SaveApiDataCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        //if child has constructor, parent constructor will not be called automatically, we calling it explicitly here
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Saves all posts from api.')
            // the command help shown when running the command with the "--help" option
            ->setHelp('This command saves all posts from api https://jsonplaceholder.typicode.com/posts to database')
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //api data manager used to download all the data from https://jsonplaceholder.typicode.com/posts and https://jsonplaceholder.typicode.com/users endpoint
        $apiDataManager = new ApiDataManager($this->entityManager);

        //download data and get amount of entities created in database
        $savedUsers = $apiDataManager->SaveUsersFromApi();
        $savedPosts = $apiDataManager->SavePostsFromApi();

        //output amount to the console
        $output->writeln("Saved users: $savedUsers \nSaved posts: $savedPosts");


        return Command::SUCCESS;
    }
}


?>