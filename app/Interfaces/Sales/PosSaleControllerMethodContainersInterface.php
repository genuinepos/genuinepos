<?php

namespace App\Interfaces\Sales;

interface PosSaleControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Sales\MethodContainerServices\PosSaleControllerMethodContainersService
     */

    public function indexMethodContainer(object $request): array|object;

    public function createMethodContainer(): mixed;

    public function storeMethodContainer(object $request, object $codeGenerator): array|object;

    public function editMethodContainer(int $id): mixed;

    public function updateMethodContainer(int $id, object $request, object $codeGenerator): array|object;

    public function printTemplateBySaleStatusForStore(object $request, object $sale, object $customerCopySaleProducts): mixed;

    public function printTemplateBySaleStatusForUpdate(object $request, object $sale, object $customerCopySaleProducts): mixed;
}
