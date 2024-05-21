<?php

namespace App\Interfaces\Purchases;

interface PurchaseOrderToInvoiceControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Purchases\MethodContainerServices\PurchaseOrderToInvoiceControllerMethodContainersService
     */

    public function createMethodContainer(object $codeGenerator, ?int $id = null): array;

    public function storeMethodContainer(object $request, object $codeGenerator): array;
}
