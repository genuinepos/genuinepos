<?php

namespace App\Interfaces\Accounts;

interface AccountControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Accounts\MethodContainerServices\AccountControllerMethodContainersService
     */

    public function indexMethodContainer(object $request): array|object;

    public function createMethodContainer(int $type): array;

    public function storeMethodContainer(object $request, object $codeGenerator): object;

    public function editMethodContainer(int $id, int $type): array;

    public function updateMethodContainer(int $id, object $request, object $codeGenerator): ?array;

    public function deleteMethodContainer(int $id): array;
}
