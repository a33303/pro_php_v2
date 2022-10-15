<?php

namespace a3330\pro_php_v2\src\Console\FakeCommand;

use a3330\pro_php_v2\src\Commands\CreateArticleCommandInterface;
use a3330\pro_php_v2\src\Models\Article\Article;
use a3330\pro_php_v2\src\Repositories\ArticlesRepositoryInterface;
use a3330\pro_php_v2\src\Repositories\UserRepositoryInterface;
use Faker\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ArticleDbCommand extends Command
{
    public function __construct(
        private Generator $faker,
        private CreateArticleCommandInterface $createArticleCommand,
        private ArticlesRepositoryInterface $articlesRepository,
        private UserRepositoryInterface $userRepository

    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('fake-data:article-db')
            ->setDescription('Article DB with fake data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->info('Create users command started');


        $articles = [];
        $count = 100;

        $io->progressStart($count);

        for ($i = 0; $i < 100; $i++)
        {
            $article = $this->createFakeArticle();
            $io->info('User created: '. $article->getTitle());
            $io->progressAdvance();
        }
        $io->progressFinish();

        foreach ($articles as $user)
        {
            $article = $this->createFakeArticle($user);
        }

        $io->success("Article with id {$article->getId()}:  created");
        return Command::SUCCESS;
    }

    private function createFakeArticle(mixed $user): Article
    {
        $user = $this->userRepository->get($user);
        $article = new Article(
            author: $user,
            title: $this->faker->realText,
            description: $this->faker->sentence(6,true)
        );

        $this->createArticleCommand->handle($article);

        return $this->articlesRepository->findArticleByTitle($user);
    }
}