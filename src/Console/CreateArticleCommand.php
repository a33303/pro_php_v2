<?php

namespace a3330\pro_php_v2\src\Console;

use a3330\pro_php_v2\src\Argument\Argument;
use a3330\pro_php_v2\src\Commands\CreateArticleCommandInterface;
use a3330\pro_php_v2\src\Exceptions\ArticleNotFoundException;
use a3330\pro_php_v2\src\Repositories\ArticlesRepositoryInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateArticleCommand extends Command
{
    public function __construct(
        private CreateArticleCommandInterface $createArticleCommand,
        private ArticlesRepositoryInterface $articlesRepository
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('article:create')
            ->setDescription('Create new article')
            ->addArgument('author', InputArgument::REQUIRED)
            ->addArgument('title', InputArgument::REQUIRED)
            ->addArgument('description', InputArgument::REQUIRED)
            ->addOption(
                'title_start_with_fad',
                'tswf',
                InputOption::VALUE_NONE,
                'Check user title'
            );

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->info('Create article command started');

        $question = new ConfirmationQuestion(
            'Ты точно хочешь создать сообщение?',
            false
        );

        $isSuccess = $io->askQuestion($question);

        if (!$isSuccess)
        {
            $io->warning('Сообщение не создано');
            return  Command::SUCCESS;
        }

        $title = $input->getArgument('title');
        $description = $input->getArgument('description ');

        try{
            $argument = new Argument([
                'title' => $input->getArgument('title'),
                'description' => $input->getArgument('description'),
                'author' => $input->getArgument('author')
            ]);

            $this->createArticleCommand->handle($argument);

        }catch (Exception $exception)
        {
            $io->error($exception->getMessage());
            return Command::FAILURE;
        }

        try {
            $article = $this->articlesRepository->findArticleByTitle($title);
        }catch (ArticleNotFoundException $exception)
        {
            $io->error($exception->getMessage());
            return Command::FAILURE;
        }

        try {
            $article = $this->articlesRepository->findArticleByDescription($description);
        }catch (ArticleNotFoundException $exception)
        {
            $io->error($exception->getMessage());
            return Command::FAILURE;
        }


        $io->success("User {$article->getAuthor()} created article: with id {$article->getId()}");
        return Command::SUCCESS;
    }
}