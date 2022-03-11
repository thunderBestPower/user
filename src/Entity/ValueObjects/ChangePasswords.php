<?php

namespace BlueWeb\User\Entity\ValueObjects;

use Assert\Assertion;
use Assert\AssertionFailedException;

class ChangePasswords
{
    private $password;

    /**
     * ChangePasswords constructor.
     * @param string $oldPassword
     * @param string $newPassword
     * @param string $confirmPassword
     * @param string $savedPassword
     * @throws AssertionFailedException
     */
    public function __construct(string $oldPassword, string $newPassword, string $confirmPassword, string $savedPassword)
    {
        Assertion::notEmpty($oldPassword, 'Old Password is mandatory');
        Assertion::notEmpty($newPassword, 'Password is mandatory');
        Assertion::same($newPassword, $confirmPassword, 'Passwords do not match');
        Assertion::true(password_verify($oldPassword, $savedPassword), 'Old Passwords do not match');
        $this->password = $newPassword;
    }

    public function __toString()
    {
        return $this->password;
    }
}
