<?php

namespace Esc\User\Service;

use Assert\AssertionFailedException;
use Doctrine\ORM\EntityManagerInterface;
use Esc\User\Entity\ValueObjects\ChangePasswords;
use Esc\User\Entity\ValueObjects\ComparePasswords;
use Esc\User\Entity\ValueObjects\Email;
use Esc\User\Entity\ValueObjects\Username;
use Esc\User\Entity\User;
use Esc\User\Entity\ValueObjects\Roles;
use Esc\User\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;

class UserService implements EscUserService
{
    private $entityManager;
    private $userRepository;
    private $user;

    public function __construct(EntityManagerInterface $manager, UserRepository $userRepository, User $user)
    {
        $this->entityManager = $manager;
        $this->user = $user;
        $this->userRepository = $userRepository;
    }

    /**
     * @param AttributeBag $data
     * @throws AssertionFailedException
     */
    public function createUser(AttributeBag $data): void
    {

        $email = new Email($data->get('email'));

        $this->user->setUsername(new Username($data->get('username')));
        $this->user->setPlainPassword(new ComparePasswords($data->get('password', ''), $data->get('confirmPassword', '')));
        $this->user->setEmail($email->getEmail());
        $this->user->setActive((bool)$data->get('active', false));
        $roles = new Roles($data->get('roles'));
        $this->user->setRoles($roles->get());

        $this->entityManager->persist($this->user);
        $this->entityManager->flush();
    }

    /**
     * @param int $id
     * @param AttributeBag $data
     * @throws AssertionFailedException
     */
    public function updateUser(int $id, AttributeBag $data): void
    {
        $user = $this->userRepository->getOneById($id);

        if ($data->has('password') && !empty($data->get('password'))) {
            $user->setPlainPassword(new ComparePasswords($data->get('password', ''), $data->get('confirmPassword', '')));
        }

        if ($data->has('email')) {
            $email = new Email($data->get('email'));
            $user->setEmail($email->getEmail());
        }

        if ($data->has('active')) {
            $user->setActive((bool)$data->get('active', false));
        }

        if ($data->has('roles')) {
            $roles = new Roles($data->get('roles'));
            $user->setRoles($roles->get());
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @param int $id
     */
    public function deleteUser(int $id): void
    {
        $user = $this->userRepository->getOneById($id);

        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    /**
     * @param int $id
     * @param string $newPassword
     * @param string $confirmPassword
     * @param string $oldPassword
     * @throws AssertionFailedException
     */
    public function changeUserPassword(int $id, string $newPassword, string $confirmPassword, string $oldPassword): void
    {
        $user = $this->userRepository->getOneById($id);

        $savedPassword = $user->getPassword();

        $user->setPlainPassword(new ChangePasswords($oldPassword, $newPassword, $confirmPassword, $savedPassword));

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
