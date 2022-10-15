<?php

namespace Test\Arguments;

use  a3330\pro_php_v2\src\Argument\Argument;
use a3330\pro_php_v2\src\Connection\SqLiteConnector;
use  a3330\pro_php_v2\src\Exceptions\ArgumentException;
use a3330\pro_php_v2\src\Exceptions\ArticleNotFoundException;
use PHPUnit\Framework\TestCase;

class ArgumentTest extends TestCase
{
    public function testItReturnArgumentValueByName(): void
    {
        // Подготовка
        $argument = new Argument(['some_key' => "1"]);

        // Действие
        $value = $argument->get('some_key');

        // Проверка
        $this->assertSame('1', $value);
    }

    public function testItThrowAnExceptionWHenArgumentIsAbsent(): void
    {
        // Подготовка
        $argument = new Argument([]);

        // тип ожидаемого исключения
        $this->expectException(ArgumentException::class);

        // событие
        $this->expectExceptionMessage("No such argument: some_key");
        // дйействие, которое приводит  к исключению

        $argument->get('some_key');
    }

    public function argumentsProvider(): iterable
    {
        return [
            ['some_string', 'some_string'],
            [' some_string', 'some_string'],
            [' some_string ', 'some_string'],
            [123, '123'],
            [12.3, '12.3'],
        ];
    }

    /**
     * @dataProvider argumentsProvider
     */
    public function testItConvertsArgumentsToStrings($inputValue, $expectedValue): void {
        $argument = new Argument(['some_key' => $inputValue]);
        $value = $argument->get('some_key');

        $this->assertSame($expectedValue, $value);
    }
}
