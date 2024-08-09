<?php

namespace App\tests\entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{

    public function testId()
    {
        $task = new Task();
        $id = null;

        $this->assertEquals($id, $task->getId());
    }

    public function testCreatedAt()
    {
        $task = new Task();
        $date = new \DateTime();
        $createdAt = $date;

        $task->setCreatedAt($createdAt);
        $this->assertEquals($date, $task->getCreatedAt());
    }


    public function testTitle()
    {
        $task = new Task();
        $title = "Test titre";

        $task->setTitle($title);
        $this->assertEquals($title, $task->getTitle());
    }

    public function testContent()
    {
        $task = new Task();
        $content = "Test content";

        $task->setContent($content);
        $this->assertEquals($content, $task->getContent());
    }


    public function testIsDone()
    {
        $task = new Task();
        $isDone = true;

        $task->toggle($isDone);
        $this->assertEquals($isDone, $task->isDone());
    }
 
    public function testUser()
    {
        $task = new Task();
        $user = new User();

        $task->setUser($user);
        $this->assertEquals($user, $task->getUser());
    }
}
