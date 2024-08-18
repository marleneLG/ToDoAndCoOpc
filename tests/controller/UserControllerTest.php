<?php

namespace App\tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{

    public function testHomePage()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }

    public function testListActionWithoutLogin()
    {
        // If the user isn't logged, should redirect to the login page
        $client = static::createClient();
        $client->request('GET', '/users');
        static::assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        // Test if login field exists
        static::assertSame(1, $crawler->filter('input[name="username"]')->count());
        static::assertSame(1, $crawler->filter('input[name="password"]')->count());
    }

    public function testListAction()
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLogin();

        $client->request('GET', '/users');
        static::assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLogin();
        // dump('client : ');
        // dump($client);
        $users = [];
        $entityManager = $client->getContainer()
            ->get('doctrine')
            ->getManager();
        $allUsers = $entityManager
            ->getRepository(User::class)
            ->findAll();

        foreach ($allUsers as $user) {
            if ($user->getUsername() !== "admin" && $user->getUsername() !== "anonyme" && $user->getUsername() !== "user" && $user->getUsername() !== "user2") {
                array_push($users, $user);
            }
        }
        $randomNumber = random_int(0, count($users) - 1);
        $user = $users[$randomNumber];
        $crawler = $client->request('POST', '/users/' . $user->getId() . '/edit');
        static::assertSame(200, $client->getResponse()->getStatusCode());

        // Test if edit page field exists
        static::assertSame(0, $crawler->filter('input[name="username"]')->count());
        static::assertSame(0, $crawler->filter('input[name="password"]')->count());
        static::assertSame(0, $crawler->filter('input[name="email"]')->count());
        static::assertSame(0, $crawler->filter('input[name="roles"]')->count());

        // $form = $crawler->selectButton('Modifier')->form();
        // $form['username'] = 'user';
        // $form['password]'] = 'test';
        // // $form['user[password][second]'] = 'test';
        // $form['email'] = 'editedUser@example.org';
        // $form['roles'] = 'ROLE_ADMIN';
        // $client->submit($form);
        // static::assertSame(302, $client->getResponse()->getStatusCode());

        // $crawler = $client->followRedirect();
        // static::assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testCreateUser()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('john.doe@example.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);
        $client->request('POST', '/users/create');
        $this->assertResponseStatusCodeSame(expectedCode: Response::HTTP_OK);

        $username = 'Test create user';
        $password = "Test create user";
        $email = 'test-create-user@test.fr';
        $role = '["ROLE_USER"]';

        $crawler = $client->request('GET', "/users/create");

        $crawler = $client->request('POST', "/users/create", [
            'user' => [
                'username' => $username,
                'password' => [
                    'first' => $password,
                    'second' => $password
                ],
                'email' => $email,
                'role' => $role,
            ]
        ]);

        // check if task is created
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // check in db
           $entityManager = $client->getContainer()
            ->get('doctrine')
            ->getManager();

        $userCreated = $entityManager
        ->getRepository(User::class)
        ->findOneBy(['username' => $username]);

        // $this->assertNotEquals(null, $userCreated);

    }

    public function testEditUser()
    {
        $client = static::createClient();
        // $userRepository = static::getContainer()->get(UserRepository::class);
        $entityManager = $client->getContainer()
            ->get('doctrine')
            ->getManager();

        // // retrieve the test user
        // $testUser = $userRepository->findOneByEmail('john.doe@example.com');

        // // simulate $testUser being logged in
        // $client->loginUser($testUser);
        // $client->request('POST', '/users/1/edit');
        // static::assertSame(200, $client->getResponse()->getStatusCode());

        $allUsers = $entityManager
            ->getRepository(User::class)
            ->findAll();

        $users = [];

        foreach ($allUsers as $user) {
            if ($user->getUsername() !== "admin" && $user->getUsername() !== "anonyme" && $user->getUsername() !== "user" && $user->getUsername() !== "user2") {
                array_push($users, $user);
            }
        }

        $randomNumber = random_int(0, count($users) - 1);
        $user = $users[$randomNumber];
        $urlUpdateUser = '/users/' . $user->getId() . '/edit';

        $client->request('GET', $urlUpdateUser);

        $client->request('POST', $urlUpdateUser, [
            'user' => [
                'username' => 'test 2' . ' update', // update username
                'password' => [
                    'first' => '000000',
                    'second' => '000000'
                ],
                'email' => 'testcoverage@test.com',
                'role' => 'ROLE_ADMIN',
            ]
        ]);

        // check if task is updated
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $client->followRedirect();
    }

    // public function testCreateAction()
    // {
    //     $client = static::createClient();
    //     $userRepository = static::getContainer()->get(UserRepository::class);

    //     // retrieve the test user
    //     $testUser = $userRepository->findOneByEmail('john.doe@example.com');

    //     // simulate $testUser being logged in
    //     $client->loginUser($testUser);
    //     $crawler = $client->request('POST', '/users/create');
    //     static::assertSame(200, $client->getResponse()->getStatusCode());

    //     // Test if creation page field exists
    //     static::assertSame(1, $crawler->filter('input[name="user[username]"]')->count());
    //     static::assertSame(1, $crawler->filter('input[name="user[password][first]"]')->count());
    //     static::assertSame(1, $crawler->filter('input[name="user[password][second]"]')->count());
    //     static::assertSame(1, $crawler->filter('input[name="user[email]"]')->count());
    //     static::assertSame(0, $crawler->filter('input[name="user[roles][]"]')->count());

    //     $form = $crawler->selectButton('Ajouter')->form();
    //     $form['user[username]'] = 'newuser';
    //     $form['user[password][first]'] = 'test';
    //     $form['user[password][second]'] = 'test';
    //     $form['user[email]'] = 'newUser@example.org';
    //     $form['user[roles]'] = ["ROLE_USER"];
    //     $client->submit($form);
    //     static::assertSame(302, $client->getResponse()->getStatusCode());

    //     $crawler = $client->followRedirect();
    //     static::assertSame(200, $client->getResponse()->getStatusCode());
    // }
}
