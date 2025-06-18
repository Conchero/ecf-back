<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Room;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class RoomFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

for ($i = 0; $i < 20; $i++) {
    /** @var \App\Entity\User $owner */
    $owner = $this->getReference('user-' . $faker->numberBetween(0, 9));

    $room = new Room();
    $room->setTitle($faker->company . ' Salle')
        ->setSlug('salle-' . $i)
        ->setImage('default.jpg')
        ->setLocalisation($faker->address())
        ->setKeywords(implode(', ', $faker->words(5)))
        ->setDescription($faker->paragraph())
        ->setAvailable($faker->boolean(90))
        ->setCapacity($faker->numberBetween(10, 200))
        ->setOwner($owner)
    ;

    $manager->persist($room);
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
