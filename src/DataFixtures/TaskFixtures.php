<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TaskFixture extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $task = new Task();
        $user = new User();
        $task->setTitle('test');
        $task->setContent('testContent');
        $task->setDone(false);
        $task->setUser($user);
        $manager->persist($task);

        $task = new Task();
        $user = new User();
        $task->setTitle('test2');
        $task->setContent('testContent2');
        $task->setDone(false);
        $task->setUser($user);
        $manager->persist($task);

        $task = new Task();
        $user = new User();
        $task->setTitle('test3');
        $task->setContent('testContent3');
        $task->setDone(false);
        $task->setUser($user);
        $manager->persist($task);

        $task = new Task();
        $user = new User();
        $task->setTitle('test4');
        $task->setContent('testContent4');
        $task->setDone(false);
        $task->setUser($user);
        $manager->persist($task);

        $task = new Task();
        $user = new User();
        $task->setTitle('test5');
        $task->setContent('testContent5');
        $task->setDone(false);
        $task->setUser($user);
        $manager->persist($task);
        dump('task fixture :');
        dump($task);
        $manager->flush();
    }
}
