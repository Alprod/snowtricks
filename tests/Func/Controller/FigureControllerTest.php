<?php

namespace App\Tests\Func\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class FigureControllerTest extends WebTestCase
{
    private $client;
    private $defaultUser;

    public function setUp(): void
    {
        $this->client = static::createClient();
	    $userRepository = static::$container->get(UserRepository::class);
	    $this->defaultUser = $userRepository->findOneBy(['email' => 'default@gmail.com']);
    }


    public function testFormFigureRedirectToLoginIfUserIsNotConnected()
    {
        $this->client->request('POST', '/figure/new');
        self::assertResponseRedirects('/login');
    }


    public function testFormFigureUpdateRedirectToLoginIfUserIsNotConnected()
    {
        $this->client->request('POST', '/figure/\d+$/edit');
        self::assertResponseRedirects('/login');
    }


    public function testUserCanCreatedNewFigureIfUserIsConnected()
    {
        $this->client->loginUser($this->defaultUser);
        $this->client->request('POST', '/figure/new');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testUserCanUpdateFigureIfUserIsConnected()
    {
        $this->client->loginUser($this->defaultUser);
        $this->client->request('POST', '/figure/\d+$/edit');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
    }

}
