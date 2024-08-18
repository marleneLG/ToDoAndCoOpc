<?php

namespace App\tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{
    //URL Tasks
    public function testTaskList(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('john.doe@example.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        // test e.g. the profile page
        $crawler = $client->request('GET', '/tasks');
        static::assertSame(200, $client->getResponse()->getStatusCode());
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

    public function testCreateTask()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('john.doe@example.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);
        $client->request('POST', '/tasks/create');
        $this->assertResponseStatusCodeSame(expectedCode: Response::HTTP_OK);
    }
    public function testCreateAction()
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLogin();

        $crawler = $client->request('GET', '/tasks/create');
        static::assertSame(200, $client->getResponse()->getStatusCode());

        // Test if creation page field exists
        static::assertSame(1, $crawler->filter('input[name="task[title]"]')->count());
        static::assertSame(1, $crawler->filter('textarea[name="task[content]"]')->count());

        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'Nouvelle tâche';
        $form['task[content]'] = 'Ceci est une tâche crée par un test';
        $client->submit($form);
        static::assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        static::assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testEditAction()
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
        $crawler = $client->request('GET', '/tasks/1/edit');
        static::assertSame(200, $client->getResponse()->getStatusCode());

        // Test if creation page field exists
        static::assertSame(1, $crawler->filter('input[name="task[title]"]')->count());
        static::assertSame(1, $crawler->filter('textarea[name="task[content]"]')->count());

        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'Modification de tache';
        $form['task[content]'] = 'Je modifie une tache';
        $client->submit($form);
        static::assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        static::assertSame(200, $client->getResponse()->getStatusCode());
    }

    // public function testDeleteTaskAction()
    // {
    //     $securityControllerTest = new SecurityControllerTest();
    //     $client = $securityControllerTest->testLogin();

    //     $client->request('GET', '/tasks/1/delete');
    //     static::assertSame(302, $client->getResponse()->getStatusCode());
    //     $client->followRedirect();
    //     static::assertSame(200, $client->getResponse()->getStatusCode());

    //     // Test if success message is displayed
    //     // static::assertContains("Superbe ! La tâche a bien été supprimée.", $crawler->filter('div.alert.alert-success')->text());
    // }

    // public function testToggleTaskAction()
    // {
    //     $securityControllerTest = new SecurityControllerTest();
    //     $client = $securityControllerTest->testLogin();

    //     $client->request('GET', '/tasks/1/toggle');
    //     static::assertSame(302, $client->getResponse()->getStatusCode());

    //     $client->followRedirect();
    //     static::assertSame(200, $client->getResponse()->getStatusCode());
    // }
}
