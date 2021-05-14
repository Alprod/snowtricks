<?php
namespace App\Tests\Func\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomeControllerTest extends WebTestCase
{
    public function testHomePage()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testTitleH1HomePage()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        self::assertSelectorTextContains('h1', 'Bienvenu à SnowTricks');
    }

}