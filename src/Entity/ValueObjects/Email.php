<?php

namespace BlueWeb\User\Entity\ValueObjects;

use Assert\Assertion;
use Assert\AssertionFailedException;

class Email
{
    private $email;

    /**
     * Email constructor.
     * @param string|null $email
     * @throws AssertionFailedException
     */
    public function __construct(?string $email)
    {
        $this->email = $email;
        if (!empty($email)) {
            Assertion::email($email);
        }
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
}
