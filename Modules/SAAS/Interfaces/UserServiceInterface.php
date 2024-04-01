<?php

namespace Modules\SAAS\Interfaces;

interface UserServiceInterface
{
    /**
     * @return \Modules\SAAS\Services\UserService
     */

    public function usersTable(): object;
    public function addUser(object $request): void;
}
