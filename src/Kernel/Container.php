<?php

declare(strict_types=1);

namespace App\Kernel;

class Container
{
    private array $services = [];

    public function set(string $name, callable $factory): void
    {
        $this->services[$name] = $factory;
    }

    public function get(string $name)
    {
        if (!isset($this->services[$name])) {
            throw new \Exception("Service not found: {$name}");
        }

        return $this->services[$name]($this);
    }
}
