<?php

namespace App\DataFixtures;

use App\Entity\Figure;
use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $_passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->_passwordEncoder = $passwordEncoder;   
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        
        for ($i=1; $i < 15; $i++) {
            $user = new User();
            $hash = $this->_passwordEncoder->encodePassword($user, 'password');

            $user->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setRoles($user->getRoles())
                ->setEmail($faker->freeEmail())
                ->setPseudo($faker->word())
                ->setPassword($hash)
                ->setAvatar($faker->imageUrl(90, 90, 'animals', true, 'dogs', true))
                ->setCreatedAt($faker->dateTimeBetween('-1 years'));
        
            $manager->persist($user);
            $this->addReference('User_'.$i, $user);
        }

        $manager->flush();
    }
}
