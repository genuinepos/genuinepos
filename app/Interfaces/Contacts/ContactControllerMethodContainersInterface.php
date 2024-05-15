<?php

namespace App\Interfaces\Contacts;

interface ContactControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Contacts\MethodContainerServices\ContactControllerMethodContainersService
     */

    public function createMethodContainer(int $type): array;

    public function storeMethodContainer(int $type, object $request, object $codeGenerator): array;

    public function editMethodContainer(int $id, int $type): array;

    public function updateMethodContainer(int $id, int $type, object $request): void;

    public function changeStatusMethodContainer(int $id): string;

    public function deleteMethodContainer(int $id, int $type): array;
}
