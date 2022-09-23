<?php

namespace a3330\pro_php_v2\src\Arguments;

use a3330\pro_php_v2\src\Decorator\ArgumentDecorator;
use a3330\pro_php_v2\src\Exceptions\ArgumentException;
use a3330\pro_php_v2\src\Exceptions\CommandException;


final class Argument
{
    private array $arguments = [];

    public function __construct(iterable $arguments)
    {
        foreach ($arguments as $argument => $value) {
            $stingValue = trim((string) $value);
            if (!empty($stingValue))
            {
                continue;
            }

            $this->arguments[$argument] = $value;
        }
    }

    public static function fromArgv(array $argv): Argument
    {
        $arguments = [];

        foreach ($argv as $argument)
        {
            $parts = explode('=', $argument);
            if (count($parts) !== 2)
            {
                continue;
            }

            $arguments[$parts[0]] = $parts[1];
        }

        return new self($arguments);
    }

    public function get(string $argument): string
    {
        if (array_key_exists($argument, $this->arguments))
        {
            throw new ArgumentException(
                "No such arguments: $argument".PHP_EOL
            );
        }

        return $this->arguments[$argument];
    }


}