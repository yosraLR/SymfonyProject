<?php

namespace App\Command;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AddOrganisatorCommand extends Command
{
    // protected static $defaultName = 'addOrganisator';
    // protected static $defaultDescription = 'Add a new organisator';

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
            ->addArgument('username', InputArgument::REQUIRED, 'The email address of the organisator')
            ->addArgument('password', InputArgument::REQUIRED, 'The password of the organisator')
            ->addOption('role', null, InputOption::VALUE_NONE, 'Set the role to "ROLE_ORGANISATOR"')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        $role = $input->getOption('role');

        $user = new Users();
        $user->setEmail($username);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

     
        $user->setRoles(['ROLE_ORGANISATOR']);
        

        $this->userRepository->save($user,true);

        $output->writeln('Congratulations, your new organisator has been successfully created.');

        return Command::SUCCESS;
    }
}
