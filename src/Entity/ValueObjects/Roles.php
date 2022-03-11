<?php


namespace BlueWeb\User\Entity\ValueObjects;


use Assert\Assertion;
use Assert\AssertionFailedException;

class Roles
{
    private $roles;

    /**
     * Roles constructor.
     * @param array $roles
     * @throws AssertionFailedException
     */
    public function __construct(array $roles)
    {
        Assertion::notEmpty($roles);
        $this->roles = $roles;
    }

    public function __toString(): string
    {
        return implode(',', $this->roles);
    }

    public function get(): array
    {
        return $this->roles;
    }
}
