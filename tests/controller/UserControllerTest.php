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

        $crawler = $client->request('GET', '/users');
        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertSame("Liste des utilisateurs", $crawler->filter('h1')->text());
    }

    public function testEditUser()
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLogin();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('john.doe@example.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        // simulate $testUser being logged in
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/users/45/edit');
        static::assertSame(200, $client->getResponse()->getStatusCode());

        // Test if creation page field exists
        static::assertSame(1, $crawler->filter('input[name="user[username]"]')->count());
        static::assertSame(1, $crawler->filter('input[name="user[email]"]')->count());

        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = 'newuser';
        $form['user[password][first]'] = 'test';
        $form['user[password][second]'] = 'test';
        $form['user[email]'] = 'newUser@example.org';
        $form['user[roles]'] = 'ROLE_USER';
        $client->submit($form);
        static::assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        static::assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLogin();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('john.doe@example.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);
        $crawler = $client->request('POST', '/users/create');
        static::assertSame(200, $client->getResponse()->getStatusCode());

        // Test if creation page field exists
        static::assertSame(1, $crawler->filter('input[name="user[username]"]')->count());
        static::assertSame(1, $crawler->filter('input[name="user[email]"]')->count());

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'newuser';
        $form['user[password][first]'] = 'test';
        $form['user[password][second]'] = 'test';
        $form['user[email]'] = 'newUser2@example.org';
        $form['user[roles]'] = ["ROLE_USER"];
        $client->submit($form);
        static::assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        static::assertSame(200, $client->getResponse()->getStatusCode());
    }
}
