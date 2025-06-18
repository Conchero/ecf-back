<?php

namespace App\DataFixtures;

use App\Entity\User;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use DateTimeImmutable;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 70; $i++) {
            $user = new User();
            $user
                ->setEmail($faker->unique()->safeEmail())
                ->setPhoneNumber($faker->phoneNumber())
                ->setfirstName($faker->userName())
                ->setlastName($faker->userName())
                ->setPassword($this->hasher->hashPassword($user, 'admin'))
                ->setRoles(['ROLE_USER'])
                ->setCreatedAt(new DateTimeImmutable())
                // ->setCreatedAt($faker->dateTimeBetween('-6 months', 'now'))

            ;

            $manager->persist($user);


            $this->addReference('user-' . $i, $user);
        }

        $manager->flush();
    }
}
