<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Equipment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\AsciiSlugger;
//asciisluger facon
class EquipmentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $slugger = new AsciiSlugger();

        for ($i = 0; $i < 10; $i++) {
            $equipment = new Equipment();

            $title = ucfirst($faker->words(2, true));
            $equipment->setTitle($title);

            // Slug titre + index
            $slug = $slugger->slug($title . '-' . $i)->lower();
            $equipment->setSlug($slug);

            $manager->persist($equipment);
            $this->addReference('equipment-' . $i, $equipment);
        }

        $manager->flush();
    }
}
