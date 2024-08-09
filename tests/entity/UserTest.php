<?php

namespace App\tests\entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserTest extends TestCase
{

    public function testId()
    {
        $user = new User();
        $id = null;

        $this->assertEquals($id, $user->getId());
    }

    public function testUsername()
    {
        $user = new User();
        $username = "Test username";

        $user->setUsername($username);
        $this->assertEquals($username, $user->getUsername());
    }

    public function testPassword()
    {
        $user = new User();

        $user->setPassword('$2y$13$G17YYNFCvqh6GHvKp3XPsuKeMekWHT1V3xuhTYFOo.res1SDJlQha');
        $this->assertEquals('$2y$13$G17YYNFCvqh6GHvKp3XPsuKeMekWHT1V3xuhTYFOo.res1SDJlQha', $user->getPassword());
    }

    public function testEmail()
    {
        $user = new User();
        $email = "test@test.fr";

        $user->setEmail($email);
        $this->assertEquals($email, $user->getEmail());
    }

    public function testCreateTask()
    {
        $user = new User();
        $task = new Task();

        $user->addTask($task);
        $this->assertEquals($task, $user->getTasks()[0]);
    }

    public function testTasksEmpty()
    {
        $user = new User();

        $collection = $user->getTasks();
        $this->assertEquals(true, $collection->isEmpty());
    }

    public function testRemoveTask()
    {
        $user = new User();
        $task = new Task();

        $user->addTask($task);
        $this->assertEquals($task, $user->getTasks()[0]);

        $user->removeTask($task);
        $this->assertEquals([], $user->getTasks()->toArray());
    }

    public function testRole()
    {
        $user = new User();
        $role = ["ROLE_USER"];

        $user->setRoles($role);
        $this->assertEquals($role, $user->getRoles());
    }
}
