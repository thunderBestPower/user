<?php

namespace BlueWeb\User\Service;

use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;

interface BlueWebUserService
{
    /**
     * @param AttributeBag $data
     */
    public function createUser(AttributeBag $data): void;

    /**
     * @param int $id
     * @param AttributeBag $data
     */
    public function updateUser(int $id, AttributeBag $data): void;

    /**
     * @param int $id
     */
    public function deleteUser(int $id): void;

    /**
     * @param int $id
     * @param string $newPassword
     * @param string $confirmPassword
     * @param string $oldPassword
     */
    public function changeUserPassword(int $id, string $newPassword, string $confirmPassword, string $oldPassword): void;
}
