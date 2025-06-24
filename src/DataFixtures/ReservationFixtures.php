<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Reservation;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\RoomFixtures;

class ReservationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $reservations = [];

        for ($i = 0; $i < 30; $i++) {
            $reservation = new Reservation();

            $start = $faker->dateTimeBetween('+0 days', '+30 days');
            $end = (clone $start)->modify('+' . rand(1, 120) . ' hours');

            /** @var \App\Entity\User $client */
            $client = $this->getReference('user-' . $faker->numberBetween(0, 69), \App\Entity\User::class);

            /** @var \App\Entity\Room $room */
            $room = $this->getReference('room-' . $faker->numberBetween(0, 9), \App\Entity\Room::class);

            $reservation
                ->setClient($client)
                ->setRentedRoom($room)
                ->setReservationStart($start)
                ->setReservationEnd($end)
                ->setStatus($faker->randomElement(['pending', 'accepted', 'rejected']))->makeSlug($i);


            $manager->persist($reservation);
            $reservations[] = $reservation;
        }


        foreach ($reservations as $index => $reservation) {
            $this->addReference('reservation-' . $index, $reservation);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            RoomFixtures::class,
        ];
    }
}

