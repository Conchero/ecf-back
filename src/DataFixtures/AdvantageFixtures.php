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
        $advantages = [];

        for ($i = 0; $i < 10; $i++) {
            $advantage = new Advantage();
            $name = $faker->words(2, true);
            $advantage->setTitle($name);

            $manager->persist($advantage);
            $advantages[] = $advantage;
        }

        $manager->flush();

        foreach ($advantages as $index => $advantage) {
            // setSlug() removed => we only use getSlug() in the entity
            // No need to set slug manually, it will be generated automatically
            $this->addReference('advantage-' . $index, $advantage);
        }

        $manager->flush();
    }
}
