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
        $equipments = [];

        for ($i = 0; $i < 10; $i++) {
            $equipment = new Equipment();

            $title = ucfirst($faker->words(2, true));
            $equipment->setTitle($title)->makeSlug();

            $manager->persist($equipment);
            $equipments[] = $equipment;
        }

        $manager->flush();

        foreach ($equipments as $index => $equipment) {
            // setSlug() removed => we only use getSlug() in the entity
            $this->addReference('equipment-' . $index, $equipment);
        }

        $manager->flush();
    }
}

