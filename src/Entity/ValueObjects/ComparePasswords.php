<?php

namespace BlueWeb\User\Entity\ValueObjects;

use Assert\Assertion;
use Assert\AssertionFailedException;

class ComparePasswords
{
    private $password;

    /**
     * ComparePasswords constructor.
     * @param string $password
     * @param string $newPassword
     * @throws AssertionFailedException
     */
    public function __construct(string $password, string $newPassword)
    {
        Assertion::same($password, $newPassword);
        $this->password = $password;
    }

    public function __toString()
    {
        return $this->password;
    }
}
