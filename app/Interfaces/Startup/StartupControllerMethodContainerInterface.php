<?php

namespace App\Interfaces\Startup;

interface StartupControllerMethodContainerInterface
{
    /**
     * @return \App\Services\Startup\MethodContainerServices\StartupControllerMethodContainerService
     */
    public function startupFromContainer(): array;

    public function finishMethodContainer(object $request): void;
}
