<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:add-user',
    description: 'Add new user',
)]
class AddUserCommand extends Command
{

    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $userPasswordHasher;
    private UserRepository $repository;

    public function __construct(string $name = null,
                                EntityManagerInterface $entityManager,
                                UserPasswordHasherInterface $userPasswordHasher,
                                UserRepository $repository )
    {
        parent::__construct($name);
        $this->entityManager=$entityManager;
        $this->userPasswordHasher=$userPasswordHasher;
        $this->repository=$repository;
    }


    protected function configure(): void
    {
        $this
            ->addOption('email', 'email',InputArgument::REQUIRED, 'Email')
            ->addOption('password','password', InputArgument::REQUIRED, 'Password')
            ->addOption('is_admin','is_admin', InputArgument::OPTIONAL, 'Is admin' ,0)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);


        $email=$input->getOption('email');
        $password=$input->getOption('password');
        $is_admin=$input->getOption('is_admin');


        $io->title('Creation new user');


        if (!$email){
         $email=$io->ask('Enter email');
        }

        if (!$password){
            $password=$io->askHidden('Enter password');
        }

        if (!$is_admin){
            $question=new Question('Created user is admin(1) or user(0)',0);
            $is_admin=$io->askQuestion($question);
        }

        try {
            $this->createUser($email,$password,(bool) $is_admin);
        }catch (RuntimeException $e){
            $io->comment($e->getMessage());
            return Command::FAILURE;
        }


        $io->success(sprintf("The %s is created",$is_admin ? "Admin" : "User" ));


        return Command::SUCCESS;
    }

    private function createUser(string $email, string $password, bool $isAdmin)
    {
        $userExists=$this->repository->findOneBy(['email'=>$email]);

        if ($userExists){
            throw new RuntimeException('USER ALREADY EXISTS');
        }


        $user=new User();

        $user->setEmail($email);
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $password
            )
        );
        $user->setRoles([$isAdmin ? 'ROLE_ADMIN' : 'ROLE_USER']);
        $user->setIsVerified(true);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }


}
