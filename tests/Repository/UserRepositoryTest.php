<?php

namespace App\Tests\Repository;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    public function getUser()
    {
        self::bootKernel();
        return self::$container->get(UserRepository::class);
    }

    public function testCountUsers(): void
    {
        self::assertEquals(5, $this->getUser()->count([]));
    }

}
