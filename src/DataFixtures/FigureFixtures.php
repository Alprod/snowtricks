<?php

namespace App\DataFixtures;

use App\Entity\Category;
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
        for($j = 1; $j <= 6; $j++ ) {
            $category = new Category();
            $category->setTitle($faker->sentence())
                ->setContent($faker->realText(150));

            $manager->persist($category);

            for($i = 1; $i < mt_rand(6, 10); $i++) {
                $figure = new Figure();
                $figure->setTitle($faker->sentence(3))
                    ->setDescription($faker->realText(200,2))
                    ->setAuthor($this->getReference('User_'.$i)->getPseudo())
                    ->setCategory($category)
                    ->setCreatedAt($faker->dateTimeBetween('-1 years'));

                $manager->persist($figure);
                $this->setReference('Figure_'.$i, $figure);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class
        ];
    }
}
