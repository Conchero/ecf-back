<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Equipment; 
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class EquipementFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $equipment = new Equipment(); 
            $equipment->setTitle($faker->word());

            $manager->persist($equipment);
            $this->addReference('equipement-' . $i, $equipment);
        }

        $manager->flush();
    }
}
