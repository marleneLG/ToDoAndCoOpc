<?php

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    public function testDisplayLogin()
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertResponseStatusCodeSame(expectedCode: Response::HTTP_OK);
    }

    public function testLogin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
        static::assertSame(200, $client->getResponse()->getStatusCode());

        // Test if login field exists
        static::assertSame(1, $crawler->filter('input[name="username"]')->count());
        static::assertSame(1, $crawler->filter('input[name="password"]')->count());

        $form = $crawler->selectButton('Se connecter')->form();
        $form['username'] = 'testFixture';
        $form['password'] = 'pass_1234';
        $client->submit($form);

        $crawler = $client->followRedirect();
        static::assertSame(200, $client->getResponse()->getStatusCode());

        return $client;
    }

    public function testLoginWithBadCredentials()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();
        $form['username'] = 'badUser';
        $form['password'] = 'fakePassword';
        $client->submit($form);
        $this->assertResponseRedirects('/login');

        $crawler = $client->followRedirect();

        $this->assertSelectorExists('.alert.alert-danger');
    }
}
