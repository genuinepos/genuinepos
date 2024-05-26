<?php

namespace App\Interfaces\Setups;

interface BranchControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Setups\MethodContainerServices\BranchControllerMethodContainersService
     */

    public function indexMethodContainer(object $request): array|object;

    public function createMethodContainer(): array;

    public function storeMethodContainer(object $request): ?array;

    public function editMethodContainer(int $id): array;

    public function updateMethodContainer(int $id, object $request): void;

    public function deleteMethodContainer(int $id): ?array;

    public function parentWithChildBranchesMethodContainer($id): ?object;

    public function branchCodeMethodContainer(?int $parentBranchId = null): string;
}
