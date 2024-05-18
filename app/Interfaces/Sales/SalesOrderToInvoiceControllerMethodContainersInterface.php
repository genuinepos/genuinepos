<?php

namespace App\Interfaces\Sales;

interface SalesOrderToInvoiceControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Sales\MethodContainerServices\SalesOrderToInvoiceControllerMethodContainersService
     */

    public function createMethodContainer(object $codeGenerator, ?int $id = null): ?array;

    public function storeMethodContainer(object $request, object $codeGenerator): ?array;
}
