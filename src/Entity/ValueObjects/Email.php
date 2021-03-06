<?php

namespace Esc\User\Entity\ValueObjects;

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

    public function __toString(): string
    {
        return $this->email;
    }
}
