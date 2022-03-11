<?php

namespace BlueWeb\User\Command;

use Assert\AssertionFailedException;
use BlueWeb\User\Service\UserService;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';

    protected $userService;

    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->userService = $userService;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws AssertionFailedException
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $username = new Question('Username: ');
        $password = new Question('Password: ');
        $password->setHidden(true);
        $password->setHiddenFallback(false);
        $confirmPassword = new Question('Confirm Password: ');
        $confirmPassword->setHidden(true);
        $confirmPassword->setHiddenFallback(false);
        $roles = new ChoiceQuestion(
            'Which roles defines the user (ROLE_USER)? ',
            ['ROLE_USER', 'ROLE_ADMIN'],
            0
        );
        $roles->setMultiselect(true);
        $roles->setErrorMessage('Role %s is invalid.');
        $email = new Question('Email: ');
        $isActive = new ConfirmationQuestion('Should the user be active (Y/n)? ', 'Y');

        $username = $helper->ask($input, $output, $username);
        $password = $helper->ask($input, $output, $password);
        $confirmPassword = $helper->ask($input, $output, $confirmPassword);
        $roles = $helper->ask($input, $output, $roles);
        $email = $helper->ask($input, $output, $email);
        if ($helper->ask($input, $output, $isActive)) {
            $isActive = true;
        } else {
            $isActive = false;
        }

        $question = new ConfirmationQuestion('Everything has been set. Do you wish to continue (Y/n)? ', true);
        if (!$helper->ask($input, $output, $question)) {
            return;
        }

        $attributeBag = new AttributeBag();
        $userData = [
            'username' => $username,
            'password' => $password,
            'confirmPassword' => $confirmPassword,
            'email' => $email,
            'active' => $isActive,
            'roles' => $roles,
        ];
        $attributeBag->initialize($userData);

        $this->userService->createUser($attributeBag);

        return 0;
    }
}
