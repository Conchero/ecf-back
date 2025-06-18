<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Advantage;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AdvantageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $advantage = new Advantage();
            $advantage->setTitle($faker->words(2, true));

            $manager->persist($advantage);
            $this->addReference('advantage-' . $i, $advantage);
        }

        $manager->flush();
    }
}
