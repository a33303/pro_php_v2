<?php

namespace a3330\pro_php_v2\src\Console\FakeCommand;

use a3330\pro_php_v2\src\Argument\Argument;
use a3330\pro_php_v2\src\Commands\CreateUserCommandInterface;
use a3330\pro_php_v2\src\Models\User\User;
use a3330\pro_php_v2\src\Repositories\UserRepositoryInterface;
use Faker\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class PopulateDbCommand extends Command
{
    public function __construct(
        private Generator $faker,
        private CreateUserCommandInterface $createUserCommand,
        private UserRepositoryInterface $userRepository
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('fake-data:populate-db')
            ->setDescription('Populates DB with fake data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->info('Create users command started');

        $users = [];
        $count = 100;

        $io->progressStart($count);

        for ($i = 0; $i < 100; $i++)
        {
            $user = $this->createFakeUser();
            $io->info('User created: '. $user->getEmail());
            $io->progressAdvance();
        }
        $io->progressFinish();

        $io->success("User with id {$user->getId()}:  created");
        return Command::SUCCESS;
    }

    private function createFakeUser(): User
    {
        $email = uniqid('email') . $this->faker->email;
        $argument = new Argument([
            'email' => $email,
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'password' => $this->faker->password
        ]);

        $this->createUserCommand->handle($argument);

        return $this->userRepository->findUserByEmail($email);
    }
}