<?php

namespace App\Command;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AddOrganisatorCommand extends Command
{
    private $userRepository;
    private $passwordHasher;

    public function __construct(UsersRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('addOrganisator')
            ->setDescription('Add a new organisator')
            ->addArgument('username', InputArgument::REQUIRED, 'The email address of the organisator')
            ->addArgument('password', InputArgument::REQUIRED, 'The password of the organisator')
            ->addArgument('firstName', InputArgument::REQUIRED, 'The first name of the organisator')
            ->addArgument('lastName', InputArgument::REQUIRED, 'The last name of the organisator')
            ->addArgument('phone', InputArgument::REQUIRED, 'The phone number of the organisator')
            ->addOption('role', null, InputOption::VALUE_NONE, 'Set the role to "ROLE_ORGANISATOR"')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        $firstName = $input->getArgument('firstName');
        $lastName = $input->getArgument('lastName');
        $phone = $input->getArgument('phone');
        $role = $input->getOption('role');

        $user = new Users();
        $user->setEmail($username);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setPhone($phone);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        $user->setRoles(['ROLE_ORGANISATOR']);

        $this->userRepository->save($user,true);

        $output->writeln('Congratulations, your new organisator has been successfully created.');

        return Command::SUCCESS;
    }
}
