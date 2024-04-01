<?php

namespace Modules\SAAS\Interfaces;

interface UserServiceInterface
{
    /**
     * @return \Modules\SAAS\Services\UserService
     */

    public function usersTable(): object;
    public function addUser(object $request, ?object $role, object $fileUploader): void;
    public function updateUser(int $id, object $request, ?object $role, object $fileUploader): void;
    public function deleteUser(int $id): array;
    public function singleUser(int $id, ?array $with = null): ?object;
    public function users(?array $with = null): object;
}
