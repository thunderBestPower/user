<?php

namespace Esc\User\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Esc\User\Entity\User;

final class UserFixtures extends Fixture
{
    /**
     * @param EntityManagerInterface $entityManager
     * @return void
     */
    public function load(EntityManagerInterface $entityManager): void
    {
        $userEntity = new User();
        $userEntity->setUsername('admin');
        $userEntity->setPlainPassword('admin');
        $userEntity->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $userEntity->setEmail('admin@admin.com');

        $entityManager->persist($userEntity);
        $entityManager->flush();

        $userEntity = new User();
        $userEntity->setUsername('utente');
        $userEntity->setPlainPassword('utente');
        $userEntity->setRoles(['ROLE_USER']);
        $userEntity->setEmail('utente@utente.com');
        $userEntity->setActive(false);

        $entityManager->persist($userEntity);
        $entityManager->flush();
    }
}
