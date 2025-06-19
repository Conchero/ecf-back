<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Notification;
use App\Entity\User;
use App\Entity\Reservation;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\ReservationFixtures;

class NotificationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 30; $i++) {
            $notification = new Notification();

            /** @var Reservation $reservation */
            $reservation = $this->getReference('reservation-' . $faker->numberBetween(0, 29), Reservation::class);

            /** @var User $receiver */
            $receiver = $this->getReference('user-' . $faker->numberBetween(0, 69), User::class);

            $notification
                ->setReservation($reservation)
                ->setReceiver($receiver)
                ->setMessage($faker->sentence());

            $manager->persist($notification);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ReservationFixtures::class,
            UserFixtures::class,
        ];
    }
}