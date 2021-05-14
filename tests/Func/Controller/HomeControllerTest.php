<?php
namespace App\Tests\Entity\Func\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testDisplayHomePage()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $response = $client->getResponse()->getStatusCode();

        self::assertEquals(200, $response);
    }

}