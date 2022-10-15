<?php

namespace a3330\pro_php_v2\src\Console;

use a3330\pro_php_v2\src\Argument\Argument;
use a3330\pro_php_v2\src\Commands\CreateArticleCommandInterface;
use a3330\pro_php_v2\src\Commands\CreateCommentCommandInterface;
use a3330\pro_php_v2\src\Exceptions\ArticleNotFoundException;
use a3330\pro_php_v2\src\Repositories\ArticlesRepositoryInterface;
use a3330\pro_php_v2\src\Repositories\CommentRepositoryInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateCommentCommand extends Command
{
    public function __construct(
        private CreateCommentCommandInterface $createCommentCommand,
        private CommentRepositoryInterface $commentRepository
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('comment:create')
            ->setDescription('Create new comment')
            ->addArgument('author', InputArgument::REQUIRED)
            ->addArgument('article', InputArgument::REQUIRED)
            ->addArgument('text', InputArgument::REQUIRED);

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->info('Create article command started');

        $question = new ConfirmationQuestion(
            'Создать комментарий??',
            false
        );

        $isSuccess = $io->askQuestion($question);

        if (!$isSuccess)
        {
            $io->warning('Сообщение не создано');
            return  Command::SUCCESS;
        }

        $text = $input->getArgument('text');

        try{
            $argument = new Argument([
                'text' => $input->getArgument('text'),
                'article' => $input->getArgument('article'),
                'author' => $input->getArgument('author')
            ]);

            $this->createCommentCommand->handle($argument);

        }catch (Exception $exception)
        {
            $io->error($exception->getMessage());
            return Command::FAILURE;
        }

        try {
            $comment = $this->commentRepository->findCommentByText($text);
        }catch (ArticleNotFoundException $exception)
        {
            $io->error($exception->getMessage());
            return Command::FAILURE;
        }



        $io->success("User {$comment->getAuthor()} created comment: with id {$comment->getId()}");
        return Command::SUCCESS;
    }
}