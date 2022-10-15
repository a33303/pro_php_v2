<?php

namespace Test\Traits;

use a3330\pro_php_v2\src\Container\DiContainer;

trait ContainerTrait
{
    private DiContainer $container;

    private function getContainer(): DiContainer
    {
        $this->container = $this->container ?? require __DIR__ . '/../../public/autoload_runtime.php';
        return $this->container;
    }
}