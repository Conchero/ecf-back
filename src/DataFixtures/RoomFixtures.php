<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Room;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use App\DataFixtures\UserFixtures;


class RoomFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $rooms = [];

        for ($i = 0; $i < 20; $i++) {
            $room = new Room();

            /** @var \App\Entity\User $owner */
            $owner = $this->getReference('user-' . $faker->numberBetween(0, 9), \App\Entity\User::class);
            $room = new Room();
            $room->setTitle($faker->company . ' Salle')
                ->makeSlug()
                ->setImage('default.jpg')
                ->setLocalisation($faker->address())
                ->setKeywords(implode(', ', $faker->words(5)))
                ->setDescription($faker->paragraph())
                ->setIsAvailable($faker->boolean(90))
                ->setCapacity($faker->numberBetween(10, 200))
                ->setOwner($owner)
                ->addAdvantage($this->getReference('advantage-' . $faker->numberBetween(0, 3), \App\Entity\Advantage::class))
                ->addAdvantage($this->getReference('advantage-' . $faker->numberBetween(0, 3), \App\Entity\Advantage::class))
                ->addAdvantage($this->getReference('advantage-' . $faker->numberBetween(0, 3), \App\Entity\Advantage::class))
                ->addEquipment($this->getReference('equipment-' . $faker->numberBetween(0, 3), \App\Entity\Equipment::class))
                ->addEquipment($this->getReference('equipment-' . $faker->numberBetween(0, 3), \App\Entity\Equipment::class))
                ->addEquipment($this->getReference('equipment-' . $faker->numberBetween(0, 3), \App\Entity\Equipment::class))
                ->addSoftware($this->getReference('software-' . $faker->numberBetween(0, 3), \App\Entity\Software::class))
                ->addSoftware($this->getReference('software-' . $faker->numberBetween(0, 3), \App\Entity\Software::class))
                ->addSoftware($this->getReference('software-' . $faker->numberBetween(0, 3), \App\Entity\Software::class));



            // for ($i = 0; $i<4;$i++)
            // {
            //     $room->addAdvantage($this->getReference('advantage-' . $faker->numberBetween(0, 3), \App\Entity\Advantage::class))
            //     ->addEquipment($this->getReference('equipment-' . $faker->numberBetween(0, 3), \App\Entity\Equipment::class))
            //     ->addSoftware($this->getReference('software-' . $faker->numberBetween(0, 3), \App\Entity\Software::class));
            // }

            $manager->persist($room);
            $this->addReference('room-' . $i, $room);
            $manager->persist($room);
            $rooms[] = $room;
        }

        // flush pour générer les ID dans toutes les entités
        $manager->flush();

        foreach ($rooms as $index => $room) {
            // Slug titre + index
            $this->addReference('room-' . $index, $room);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
