<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Equipment; 
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class EquipmentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $equipment = new Equipment(); 
            $title = ucfirst($faker->word());
            $slug = strtolower(str_replace(' ', '-', $title)) . '-' . $i;

            $equipment
                ->setTitle($title)
                ->setSlug($slug);

            $manager->persist($equipment);
            $this->addReference('equipment-' . $i, $equipment);
        }

        $manager->flush();
    }
}