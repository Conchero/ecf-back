<?php

namespace App\DataFixtures;

use App\Entity\Equipment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class EquipmentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Liste de 10 équipements pour une salle de fête
        $equipmentNames = [
            'Système audio professionnel',
            'Enceintes Bluetooth portables',
            'Table de mixage DJ',
            'Projecteur multimédia',
            'Machine à fumée',
            'Boule disco LED',
            'Barres lumineuses RGB',
            'Microphone sans fil',
            'Éclairage stroboscopique',
            'Table haute cocktail'
        ];

        foreach ($equipmentNames as $i => $name) {
            $equipment = new Equipment();
            $equipment->setTitle($name);

            $manager->persist($equipment);
            $this->addReference('equipment-' . $i, $equipment);
        }

        $manager->flush();
    }
}
