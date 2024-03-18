<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\MyBooksRepository;
use App\Entity\MyBooks;
use Doctrine\Persistence\ManagerRegistry;

#[AsCommand(
    name: 'app:list-mybooks',
    description: 'Add a short description for your command',
)]
class ListMybooksCommand extends Command
{
    /**
     *  @var MyBooksRepository data access repository
     */
    private $mybookRepository;
    
    /**
     * Plugs the database to the command
     *
     * @param ManagerRegistry $doctrineManager
     */
    public function __construct(ManagerRegistry $doctrineManager)
    {
        $this->mybookRepository = $doctrineManager->getRepository(MyBooks::class);
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $mybooks = $this->mybookRepository->findAll();

        if (!empty($mybooks)) {
            $io->title('list of books collection:');
            
            $io->listing($mybooks);
        }else {
            $io->error('no collection found!');
            return Command::FAILURE;
        }

       
        return Command::SUCCESS;
    }
}
