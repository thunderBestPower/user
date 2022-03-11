<?php

namespace BlueWeb\User\Repository;

use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;

interface BlueWebUserRepository
{
    /**
     * @param AttributeBag $parameters
     * @return mixed
     */
    public function findByCriteria(AttributeBag $parameters);

    /**
     * @param array $filters
     * @return int
     */
    public function countByCriteria(array $filters): int;

    /**
     * @param int $id
     * @return mixed
     */
    public function readOneById(int $id);

    /**
     * @param int $id
     * @return mixed
     */
    public function getOneById(int $id);

}
