<?php

namespace Test\Commands;

use a3330\pro_php_v2\src\Console\FakeCommand\PopulateDbCommand;
use Monolog\Test\TestCase;
use RuntimeException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Test\Traits\ContainerTrait;

class CreateUserCommandTest extends TestCase
{
    use ContainerTrait;

    public function testItReguiresLastName(): void
    {
        $command = $this->getContainer()->get(PopulateDbCommand::class);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Not enough arguments (missing: "lastName").'
        );

        $command->run(
            new ArrayInput([
                'email' => 'ivan@ivan.ru',
                'firstName' => 'Ivan',
                'password' => '123',
            ]),
            new NullOutput()
        );
    }

    public function testItReguiresFirstName(): void
    {
        $command = $this->getContainer()->get(PopulateDbCommand::class);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Not enough arguments (missing: "firstName").'
        );

        $command->run(
            new ArrayInput([
                'email' => 'ivan@ivan.ru',
                'lastName' => 'Ivanov',
                'password' => '123',
            ]),
            new NullOutput()
        );
    }

    public function testItReguiresEmail(): void
    {
        $command = $this->getContainer()->get(PopulateDbCommand::class);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Not enough arguments (missing: "email").'
        );

        $command->run(
            new ArrayInput([
                'lastName' => 'Ivanov',
                'firstName' => 'Ivan',
                'password' => '123',
            ]),
            new NullOutput()
        );
    }

    public function testItReguiresPassword(): void
    {
        $command = $this->getContainer()->get(PopulateDbCommand::class);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Not enough arguments (missing: "password").'
        );

        $command->run(
            new ArrayInput([
                'email' => 'ivan@ivan.ru',
                'firstName' => 'Ivan',
                'lastName' => 'Ivanov',
            ]),
            new NullOutput()
        );
    }
}