<?php

namespace a3330\pro_php_v2\src\Console;

use a3330\pro_php_v2\src\Argument\Argument;
use a3330\pro_php_v2\src\Commands\CreateUserCommandInterface;
use a3330\pro_php_v2\src\Exceptions\UserNotFoundException;
use a3330\pro_php_v2\src\Repositories\UserRepositoryInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateUserCommand extends Command
{
    public function __construct(
        private CreateUserCommandInterface $createUserCommand,
        private UserRepositoryInterface $userRepository
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('user:create')
            ->setDescription('Create new user')
            ->addArgument('firstName',InputArgument::REQUIRED)
            ->addArgument('lastName',InputArgument::REQUIRED)
            ->addArgument('email',InputArgument::REQUIRED)
            ->addArgument('password',InputArgument::REQUIRED)
            ->addOption(
                'email_start_with_fad',
                'eswf',
                InputOption::VALUE_NONE,
                'Check user email'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->info('Create user command started');

        $question = new ConfirmationQuestion(
            'Ты точно хочешь создать пользователя?',
            false
        );

        $isSuccess = $io->askQuestion($question);

        if (!$isSuccess)
        {
            $io->warning('User has cancelled');
            return  Command::SUCCESS;
        }

        $email = $input->getArgument('email');

        if (
            !$input->getOption('email_start_with_fad') &&
            str_starts_with($email, 'fad')
        )
        {
            $io->warning('Фамилии, которые начинаются с fad не поддерживаются нашей системой');
            return Command::SUCCESS;
        }

        try{
            $argument = new Argument([
                'email' => $input->getArgument('email'),
                'firstName' => $input->getArgument('firstName'),
                'lastName' => $input->getArgument('lastName'),
                'password' => $input->getArgument('password'),
                'author' => null
            ]);

            $this->createUserCommand->handle($argument);

        }catch (Exception $exception)
        {
            $io->error($exception->getMessage());
            return Command::FAILURE;
        }

        try {
            $user = $this->userRepository->findUserByEmail($email);
        }catch (UserNotFoundException $exception)
        {
            $io->error($exception->getMessage());
            return Command::FAILURE;
        }

        $io->success("User with id {$user->getId()}:  created");
        return Command::SUCCESS;
    }
}