<?php

namespace BlueWeb\User\Entity\ValueObjects;

use Assert\Assertion;
use Assert\AssertionFailedException;

class Username
{
    private $username;

    /**
     * Username constructor.
     * @param string $username
     * @throws AssertionFailedException
     */
    public function __construct(string $username)
    {
        Assertion::notEmpty($username);
        $this->username = $username;
    }

    public function __toString()
    {
        return $this->username;
    }
}
