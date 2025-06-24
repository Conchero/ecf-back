<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Software;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class SoftwareFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $software = new Software();
            $software
                ->setTitle($faker->word())
                ->makeSlug($i); 

            $manager->persist($software);
            $this->addReference('software-' . $i, $software);
        }

        $manager->flush();
    }
}

