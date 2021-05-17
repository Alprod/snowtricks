<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class UserValidationEntityTest extends KernelTestCase
{
    use ErrorValidationTestTrait;

    private const EMAIL_CONSTRAINT_MESSAGE = "Désolé mais \"alain@gmail\" n'est pas valide";
    private const EMAIL_INVALID_VALUE = "alain@gmail";
    private const PASSWORD_REGEX_CONSTRAINT_MESSAGE = "Il vous faut au moins 1 chiffre, 1 majuscule, 1 minuscule et 1 caractère spécial";
    private const EMAIL_VALID_VALUE = "alain@gmail.com";
    private const VALID_PASSWORD_VALUE = "@Password81";

    public function setUp() : void
    {
        $kernel = self::bootKernel();
        $this->validator = $kernel->getContainer()->get('validator');
    }

    public function getEntity() : User
    {
        return new User();
    }


    public function testUserEntityIsValid() : void
    {
       $user = $this->getEntity()
            ->setEmail(self::EMAIL_VALID_VALUE)
            ->setPassword(self::VALID_PASSWORD_VALUE);

        $this->getErrorValidation($user, 0);
    }


    public function testUserEntityIsInvalidBecauseNotEmailValid() : void
    {
       $user = $this->getEntity()
            ->setPassword(self::VALID_PASSWORD_VALUE)
            ->setEmail(self::EMAIL_INVALID_VALUE);
        $error = $this->getErrorValidation($user, 1);
        self::assertEquals(self::EMAIL_CONSTRAINT_MESSAGE, $error[0]->getMessage());
    }


    public function providerInvalidPassword() : array
    {
        return [
            ['password'],
            ['Password'],
            ['Password81'],
            ['@password81'],
            ['password81'],
            ['PASSWORD81'],
        ];
    }


    /**
     *@dataProvider providerInvalidPassword
     */
    public function testUserEntityIsInvalidBecauseNotPasswordValueValid(string $invalidPassword) : void
    {
        $user = $this->getEntity()
            ->setEmail(self::EMAIL_VALID_VALUE)
            ->setPassword($invalidPassword);

        $error = $this->getErrorValidation($user, 1);
        self::assertEquals(self::PASSWORD_REGEX_CONSTRAINT_MESSAGE, $error[0]->getMessage());
    }
}
