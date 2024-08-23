<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Driver\IBMDB2\Exception\Factory;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    private TaskRepository $taskRepository;
    private UserRepository $userRepository;

    public function __construct(TaskRepository $taskRepository, UserRepository $userRepository, UserPasswordHasherInterface $hasher)
    {
        $this->userRepository = $userRepository;
        $this->taskRepository = $taskRepository;
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
        // reset autoincrement à 1
        $this->userRepository->resetIndex();
        $this->taskRepository->resetIndex();

        $users = [
            [
                "username" => "admin",
                "email" => "admin@todo.fr",
                "role" => '["ROLE_ADMIN"]',
                "password" => "admin",
            ],
            [
                "username" => "anonyme",
                "email" => "anonyme@todo.fr",
                "role" => '["ROLE_USER"]',
                "password" => "anonyme",
            ],
            [
                "username" => "user",
                "email" => "user@todo.fr",
                "role" => '["ROLE_USER"]',
                "password" => "user",
            ],
            [
                "username" => "user2",
                "email" => "user2@todo.fr",
                "role" => '["ROLE_USER"]',
                "password" => "user2",
            ],
            [
                "username" => "user3",
                "email" => "user3@todo.fr",
                "role" => '["ROLE_USER"]',
                "password" => "user3",
            ],
        ];

        $usersObj = [];

        foreach ($users as $item) {
            $user = new User();
            $user->setUsername($item['username']);
            $user->setEmail($item['email']);
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword($this->hasher->hashPassword($user, 'pass_1234'));

            $manager->persist($user);
            array_push($usersObj, $user);
        }

        for ($i = 1; $i <= 15; $i++) {
            $isDone = random_int(0, 1);
            $subTitle = $isDone ? ' réalisée' : ' à faire';

            $task = new Task();
            $user = $usersObj[1];
            $date = new \DateTime();

            $task->setTitle('Tâche numéro ' . $i . $subTitle);
            $task->setContent('testContent' . $i);
            $task->setCreatedAt($date);
            $task->toggle($isDone);
            $task->setUser($user);

            $manager->persist($task);
        }
        // $task = new Task();
        // $user = new User();
        // $task->setTitle('test');
        // $task->setContent('testContent');
        // $task->setDone(false);
        // $task->setUser($user);
        // $manager->persist($task);

        // $task = new Task();
        // $user = new User();
        // $task->setTitle('test2');
        // $task->setContent('testContent2');
        // $task->setDone(false);
        // $task->setUser($task->getUser());
        // $manager->persist($task);

        // $task = new Task();
        // $user = new User();
        // $task->setTitle('test3');
        // $task->setContent('testContent3');
        // $task->setDone(false);
        // $task->setUser($task->getUser());
        // $manager->persist($task);

        // $task = new Task();
        // $user = new User();
        // $task->setTitle('test4');
        // $task->setContent('testContent4');
        // $task->setDone(false);
        // $task->setUser($user);
        // $manager->persist($task);

        // $task = new Task();
        // $user = new User();
        // $task->setTitle('test5');
        // $task->setContent('testContent5');
        // $task->setDone(false);
        // $task->setUser($task->getUser());
        // $manager->persist($task);
        // dump('task fixture :');
        // dump($task);
        $manager->flush();
    }
}
