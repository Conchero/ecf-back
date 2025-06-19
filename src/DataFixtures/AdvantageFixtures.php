<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Advantage;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\AsciiSlugger;

class AdvantageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $slugger = new AsciiSlugger();

        for ($i = 0; $i < 10; $i++) {
            $advantage = new Advantage();
            $name = $faker->words(2, true);
            $slug = $slugger->slug($name)->lower();

            $advantage
                ->setTitle($name)
                ->setSlug($slug); 

            $manager->persist($advantage);
            $this->addReference('advantage-' . $i, $advantage);
        }

        $manager->flush();
    }
}