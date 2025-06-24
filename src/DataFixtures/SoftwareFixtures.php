<?php

namespace App\DataFixtures;

use App\Entity\Software;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\AsciiSlugger;

class SoftwareFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $slugger = new AsciiSlugger();

        // 10 noms de logiciels réels
        $softwareNames = [
            'Spotify',
            'Virtual DJ',
            'Serato DJ Lite/Pro',
            'Traktor Pro',
            'Resolume Avenue',
            'DMXControl',
            'SoundSwitch',
            'OBS Studio',
            'KaraFun',
            'Mixxx',
        ];

        foreach ($softwareNames as $i => $name) {
            $software = new Software();
         
            $software->setTitle($name)
                     ->makeSlug($i); 

            $manager->persist($software);
            $this->addReference('software-' . $i, $software);
        }

        $manager->flush();
    }
}

