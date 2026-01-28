<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Kernel\Container;

abstract class BaseController
{
    protected Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }
}
