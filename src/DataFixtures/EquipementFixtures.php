<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Equipement;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class EquipementFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $equipement = new Equipement();
            $equipement->setTitle($faker->word());

            $manager->persist($equipement);
            $this->addReference('equipement-' . $i, $equipement);
        }

        $manager->flush();
    }
}
