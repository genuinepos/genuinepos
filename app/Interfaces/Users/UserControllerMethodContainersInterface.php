<?php

namespace App\Interfaces\Users;

interface UserControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Users\MethodContainerServices\UserControllerMethodContainersService
     */

    public function indexMethodContainer(object $request): array|object;

    public function showMethodContainer(int $id): ?array;

    public function createMethodContainer(): ?array;

    public function storeMethodContainer(object $request): ?array;

    public function editMethodContainer(int $id): array;

    public function updateMethodContainer(int $id, object $request): ?array;

    public function deleteMethodContainer(int $id): ?array;

    public function changeBranchMethodContainer(object $request): void;

    public function branchUsersMethodContainer(int|string $isOnlyAuthenticatedUser, int|string $allowAll, mixed $branchId = null): ?object;

    public function currentUserAndEmployeeCountMethodContainer(mixed $branchId = null): array;
}
