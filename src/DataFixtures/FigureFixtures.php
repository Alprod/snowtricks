<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Figure;
use App\Entity\Image;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Provider\Youtube;

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

            for($i = 1; $i <= 5; $i++) {
                $figure = new Figure();
                $figure->setTitle($faker->sentence(3))
                    ->setDescription($faker->realText(400,2))
                    ->setAuthor($this->getReference('User_'.$i)->getFirstname())
                    ->setCategory($category)
                    ->setCreatedAt($faker->dateTimeBetween('-1 years'));

                $manager->persist($figure);
                $this->setReference('Figure_'.$i, $figure);

                $faker->addProvider(new \Faker\Provider\Image($faker));
                for($img = 1; $img <= 5; $img++) {
                    $image = new Image();
                    $image->setTitle($faker->words(3, true))
                        ->setLink($faker->imageUrl(350,150))
                        ->setFigure($figure);
                    $manager->persist($image);
                }

                $faker->addProvider(new Youtube($faker));
                for($vdo = 1; $vdo <= 4; $vdo++) {
                    $video = new Video();
                    $video->setTitle($faker->words(2,true))
                        ->setLink($faker->youtubeUri())
                        ->setFigure($figure);

                    $manager->persist($video);
                }
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
