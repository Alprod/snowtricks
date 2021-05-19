<?php

namespace App\Tests\Entity\Entity\Entity\Entity\Repository;

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
        self::assertEquals(6, $this->getUser()->count([]));
    }

}
