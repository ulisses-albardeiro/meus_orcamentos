<?php

namespace App\Nucleo;

use Pecee\SimpleRouter\ClassLoader\IClassLoader;
use Psr\Container\ContainerInterface;

class DIClassLoader implements IClassLoader
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $class
     * @return object
     */
    public function loadClass(string $class)
    {
        return $this->container->get($class);
    }

    public function loadClosure(callable $closure, array $parameters)
    {
        return call_user_func_array($closure, $parameters);
    }

    public function loadClassMethod($class, string $method, array $parameters)
    {
        // $class já pode ser o objeto resolvido pelo container
        if (is_string($class)) {
            $class = $this->container->get($class);
        }

        return call_user_func_array([$class, $method], $parameters);
    }
}
