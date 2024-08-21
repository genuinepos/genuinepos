<?php

namespace App\Interfaces\Sales;

interface PosSaleExchangeControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Sales\MethodContainerServices\PosSaleExchangeControllerMethodContainersService
     */

    public function searchInvoiceMethodContainer(object $request): array|object;

    public function prepareExchangeMethodContainer(object $request): array;

    public function exchangeConfirmMethodContainer(object $request, object $codeGenerator): array;
}
