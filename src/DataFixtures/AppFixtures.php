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
        $task = new Task();
        $task->setTitle('test');
        $task->setContent('testContent');
        $task->setDone(false);
        $this->userRepository->addTask($task);
        $manager->persist($task);

        $task = new Task();
        $task->setTitle('test2');
        $task->setContent('testContent2');
        $task->setDone(false);
        $this->userRepository->addTask($task);
        $manager->persist($task);

        $task = new Task();
        $task->setTitle('test3');
        $task->setContent('testContent3');
        $task->setDone(false);
        $this->userRepository->addTask($task);
        $manager->persist($task);

        $task = new Task();
        $task->setTitle('test4');
        $task->setContent('testContent4');
        $task->setDone(false);
        $this->userRepository->addTask($task);
        $manager->persist($task);

        $task = new Task();
        $task->setTitle('test5');
        $task->setContent('testContent5');
        $task->setDone(false);
        $this->userRepository->addTask($task);
        $manager->persist($task);
        // dump('task fixture :');
        // dump($task);
        $manager->flush();
    }
}
