<?php

namespace App\Interfaces\Sales;

interface DraftControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Sales\MethodContainerServices\DraftControllerMethodContainersService
     */

    public function showMethodContainer(
        int $id,
        object $draftService,
        object $saleProductService
    ): ?array;

    function editMethodContainer(
        int $id,
        object $draftService,
        object $accountService,
        object $accountFilterService,
        object $priceGroupService,
        object $managePriceGroupService,
    ): array;

    function updateMethodContainer(
        int $id,
        object $request,
        object $draftService,
        object $draftProductService,
    ): ?array;
}
