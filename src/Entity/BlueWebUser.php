<?php

namespace BlueWeb\User\Entity;

interface BlueWebUser
{
    /**
     * @return mixed
     */
    public function getID();

    /**
     * @return mixed
     */
    public function getActive();

    /**
     * @return mixed
     */
    public function setPassword(string $password);

    /**
     * @return mixed
     */
    public function setPlainPassword(string $plainPassword);
}
