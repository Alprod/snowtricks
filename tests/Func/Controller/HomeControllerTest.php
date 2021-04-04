<?php
namespace App\tests\Func\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeContollerTest extends WebTestCase
{
    public function testDisplayHomePage()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $response = $client->getResponse()->getStatusCode();

        $this->assertEquals(200, $response);
    }

}