<?php

namespace App\Interfaces\Services;

interface ServiceInvoiceControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Services\MethodContainerServices\ServiceInvoiceControllerMethodContainersService
     */
    public function indexMethodContainer(object $request): array|object;

    public function deleteMethodContainer(int $id): array|object;
}
