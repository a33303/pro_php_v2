<?php

namespace Test\Container;

use a3330\pro_php_v2\src\Container\DiContainer;
use a3330\pro_php_v2\src\Handlers\UserSearchHandler;
use a3330\pro_php_v2\src\Handlers\UserSearchHandlerInterface;
use a3330\pro_php_v2\src\Models\Comment\ClassWithoutDependencies;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class DIContainerTest extends TestCase
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testItResolveClassWithoutClassWithoutDependencies()
    {
        $container = new DiContainer();
        $object = $container->get(ClassWithoutDependencies::class);

        $this->assertInstanceOf(ClassWithoutDependencies::class, $object);
    }

    public function testItResolveClassWithParameter()
    {
        $object = $container->get(UserSearchHandlerInterface::class);
        $this->assertInstanceOf(UserSearchHandler::class, $object);
    }
}