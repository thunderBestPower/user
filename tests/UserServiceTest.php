<?php

namespace Test\Esc\User;

use Assert\AssertionFailedException;
use Doctrine\ORM\EntityManagerInterface;
use Esc\User\Entity\User;
use Esc\User\Repository\UserRepository;
use Esc\User\Service\UserService;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophet;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;

class UserServiceTest extends TestCase
{
    private $prophet;
    private $user;
    private $userRepository;
    private $entityManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->prophet = new Prophet();
        $this->user = $this->prophet->prophesize(User::class);
        $this->userRepository = $this->prophet->prophesize(UserRepository::class);
        $this->entityManager = $this->prophet->prophesize(EntityManagerInterface::class);
    }

    protected function tearDown(): void
    {
        $this->addToAssertionCount(count($this->prophet->getProphecies()));
    }
    /**
     * @throws AssertionFailedException
     */
    public function testCreateUser(): void
    {
        $this->user->setUsername(Argument::is('foo'))
            ->shouldBeCalled();

        $this->user->setPlainPassword(Argument::is('bar'))
            ->shouldBeCalled();

        $this->user->setEmail(Argument::exact('baz@baz.it'))
            ->willReturn($this->user)
            ->shouldBeCalled();

        $this->user->setActive(Argument::is(true))
            ->willReturn($this->user)
            ->shouldBeCalled();

        $this->user->setRoles(Argument::is([1]))
            ->shouldBeCalled();

        $userBag = new AttributeBag();
        $userData = [
            'username' => 'foo',
            'password' => 'bar',
            'confirmPassword' => 'bar',
            'email' => 'baz@baz.it',
            'active' => true,
            'roles' => [1],
        ];
        $userBag->initialize($userData);
        $service = new UserService($this->entityManager->reveal(), $this->userRepository->reveal(), $this->user->reveal());
        $service->createUser($userBag);


        $this->entityManager->persist(Argument::exact($this->user))
            ->shouldHaveBeenCalledTimes(1);

        $this->entityManager->flush()
            ->shouldHaveBeenCalled();

    }

    /**
     * @throws AssertionFailedException
     */
    public function testIfPasswordExistAndIsNotEmpty(): void
    {
        $this->userRepository->getOneById(Argument::exact(1))
            ->willReturn($this->user)
            ->shouldBeCalled();

        $this->user->setPlainPassword(Argument::is('bar'))
            ->shouldNotBeCalled();

        $this->user->setEmail(Argument::exact('baz@baz.it'))
            ->willReturn($this->user)
            ->shouldBeCalled();

        $this->user->setActive(Argument::is(true))
            ->willReturn($this->user)
            ->shouldBeCalled();

        $this->user->setRoles(Argument::is([1]))
            ->shouldBeCalled();

        $userBag = new AttributeBag();
        $userData = [
            'username' => 'foo',
            'confirmPassword' => 'bar',
            'email' => 'baz@baz.it',
            'active' => true,
            'roles' => [1],
        ];
        $userBag->initialize($userData);

        $service = new UserService($this->entityManager->reveal(), $this->userRepository->reveal(), $this->user->reveal());
        $service->updateUser(1, $userBag);

        $this->entityManager->persist(Argument::exact($this->user))
            ->shouldHaveBeenCalledTimes(1);

        $this->entityManager->flush()
            ->shouldHaveBeenCalled();
    }

    /**
     * @throws AssertionFailedException
     */
    public function testIfEmailActiveAndRolesExists(): void
    {
        $this->userRepository->getOneById(Argument::exact(1))
            ->willReturn($this->user)
            ->shouldBeCalled();

        $this->user->setEmail(Argument::exact('baz@baz.it'))
            ->willReturn($this->user)
            ->shouldBeCalled();

        $this->user->setActive(Argument::is(true))
            ->willReturn($this->user)
            ->shouldBeCalled();

        $this->user->setRoles(Argument::is([1]))
            ->shouldBeCalled();

        $userBag = new AttributeBag();
        $userData = [
            'username' => 'foo',
            'confirmPassword' => 'bar',
            'email' => 'baz@baz.it',
            'active' => true,
            'roles' => [1],
        ];
        $userBag->initialize($userData);

        $service = new UserService($this->entityManager->reveal(), $this->userRepository->reveal(), $this->user->reveal());
        $service->updateUser(1, $userBag);
    }

    public function testDeleteUser(): void
    {

        $this->userRepository->getOneById(Argument::exact(1))
            ->willReturn($this->user)
            ->shouldBeCalled();

        $service = new UserService($this->entityManager->reveal(), $this->userRepository->reveal(), $this->user->reveal());
        $service->deleteUser(1);

        $this->entityManager->remove(Argument::exact($this->user))
            ->shouldHaveBeenCalledTimes(1);

        $this->entityManager->flush()
            ->shouldHaveBeenCalled();
    }

    public function testChangePassword(): void
    {
        $this->userRepository->getOneById(Argument::exact(1))
            ->willReturn($this->user);

        $this->user->getPassword()
            ->willReturn('$2y$13$rcYC2TRkTg8//bJ.f7UKMuD2T8o3IAt.1kdGDLH3f2USa1PEwl9tq');

        $this->user->setPlainPassword('foo');

        $service = new UserService($this->entityManager->reveal(), $this->userRepository->reveal(), $this->user->reveal());
        $service->changeUserPassword(1, 'foo', 'foo', 'admin');

        $this->entityManager->persist(Argument::exact($this->user))
            ->shouldHaveBeenCalledTimes(1);

        $this->entityManager->flush()
            ->shouldHaveBeenCalled();

        $this->addToAssertionCount(count($this->prophet->getProphecies()));
    }

    public function testDeleteUserThrowException(): void
    {
        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->getMock();

        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $userRepository->method('getOneById')
            ->with(1)
            ->willThrowException(new \RuntimeException());

        $this->expectException(\RuntimeException::class);

        $service = new UserService($entityManager, $userRepository, new User());

        $service->deleteUser(1);
    }
}