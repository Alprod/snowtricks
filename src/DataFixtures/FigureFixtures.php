<?php

namespace App\DataFixtures;

use App\Entity\Figure;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class FigureFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($i = 1; $i < 15; $i++) {
            $figure = new Figure();
            $figure->setTitle($faker->sentence(3))
                ->setDescription($faker->realText(200,2))
                ->setAuthor($this->getReference('User_'.$i)->getPseudo())
                ->setCreatedAt($faker->dateTimeBetween('-1 years'));
            $manager->persist($figure);
            $this->addReference('Figure_'.$i, $figure);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
