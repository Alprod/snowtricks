<?php

namespace App\DataFixtures;

use App\Entity\Discussion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class DiscussionFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for($i = 1; $i <= 4 ; $i++) {
            $discute = new Discussion();
            $refFigure = $this->getReference('Figure_'.$i);
            $dateFigure = $refFigure->getCreatedAt();
            $days = (new \DateTime())->diff($dateFigure)->days;

            $discute->setAuthor($this->getReference('User_'.$i)->getFirstname())
                ->setContent($faker->realTextBetween(50, 200))
                ->setFigure($refFigure)
                ->setCreatedAt($faker->dateTimeBetween('-'.$days.' days'));

            $manager->persist($discute);
        }

        $manager->flush();
    }


    public function getDependencies(): array
    {
        return [
            FigureFixtures::class
        ];
    }
}
