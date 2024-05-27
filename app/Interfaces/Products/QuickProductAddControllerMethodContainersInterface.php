<?php

namespace App\Interfaces\Products;

interface QuickProductAddControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Products\MethodContainerServices\ProductControllerMethodContainersService
     */

    public function createMethodContainer(): array;
    public function storeMethodContainer(object $request): object;
}
