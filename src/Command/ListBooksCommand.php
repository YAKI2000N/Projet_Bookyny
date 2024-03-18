<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\BooksRepository;
use App\Entity\Books;
use Doctrine\Persistence\ManagerRegistry;

#[AsCommand(
    name: 'app:list-books',
    description: 'Add a short description for your command',
)]
class ListBooksCommand extends Command
{
    /**
     *  @var BooksRepository data access repository
     */
    private $bookRepository;
    
    /**
     * Plugs the database to the command
     *
     * @param ManagerRegistry $doctrineManager
     */
    public function __construct(ManagerRegistry $doctrineManager)
    {
        $this->bookRepository = $doctrineManager->getRepository(Books::class);
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
        $books = $this->bookRepository->findAll();

        if (!empty($books)) {
            $io->title('list of books:');
            $io->listing($books);
        }else {
            $io->error('no books found!');
            return Command::FAILURE;
            
        }

        return Command::SUCCESS;
    }
}
