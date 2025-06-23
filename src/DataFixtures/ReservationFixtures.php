<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Reservation;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
// facon toslug
class ReservationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 30; $i++) {
            $reservation = new Reservation();

            $start = $faker->dateTimeBetween('+0 days', '+30 days');
            $end = (clone $start)->modify('+' . rand(1, 120) . ' hours');

            $client = $this->getReference('user-' . $faker->numberBetween(0, 69), \App\Entity\User::class);
            $room = $this->getReference('room-' . $faker->numberBetween(0, 19), \App\Entity\Room::class);

            $reservation
                ->setClient($client)
                ->setRentedRoom($room)
                ->setReservationStart($start)
                ->setReservationEnd($end)
                ->setIsPending($faker->boolean(30));

            $manager->persist($reservation);
            $manager->flush(); 

            $reservation->setSlug($reservation->toSlug());
            $manager->persist($reservation);
            $this->addReference('reservation-' . $i, $reservation);
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
