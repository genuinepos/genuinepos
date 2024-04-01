<?php

namespace Modules\SAAS\Interfaces;

interface RoleServiceInterface
{
    /**
     * @return \Modules\SAAS\Services\RoleService
     */

    public function rolesTable(): object;
    public function addRole(object $request): void;
    public function updateRole(int $id, object $request): void;
    public function deleteRole(int $id): ?array;
    public function singleRole(int $id, ?array $with = null): ?object;
    public function roles(?array $with = null): ?object;
}
