<?php

namespace App\DataFixtures;

use App\Entity\User;
use Faker\Factory;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // 👤 1 admin user
        $admin = new User();
        $admin
            ->setEmail('admin@admin.com')
            ->setPhoneNumber($faker->phoneNumber())
            ->setSlug('admin')
            ->setFirstName('Admin')
            ->setLastName('User')
            ->setPassword($this->hasher->hashPassword($admin, 'adminpass'))
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']) // ⚠️ Toujours inclure ROLE_USER aussi
            ->setCreatedAt(new DateTimeImmutable());

        $manager->persist($admin);
        $this->addReference('user-admin', $admin);

        // 👥 69 users simples
        for ($i = 0; $i <= 69; $i++) {
            $user = new User();
            $user
                ->setEmail($faker->unique()->safeEmail())
                ->setPhoneNumber($faker->phoneNumber())
                ->setSlug($faker->slug())
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setPassword($this->hasher->hashPassword($user, 'userpass'))
                ->setRoles(['ROLE_USER']) // juste user
                ->setCreatedAt(new DateTimeImmutable());

            $manager->persist($user);
            $this->addReference('user-' . $i, $user);
        }

        $manager->flush();
    }
}
