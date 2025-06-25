<?php

namespace App\DataFixtures;

use App\Entity\Advantage;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AdvantageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $advantageNames = [
            'Accès Wi-Fi haut débit',
            'Parking privé gratuit',
            'Climatisation réversible',
            'Service de sécurité 24/7',
            'Vaisselle et couverts inclus',
            'Coin cuisine équipé',
            'Toilettes PMR accessibles',
            'Nettoyage post-événement',
            'Système de sonorisation intégré',
            'Espace vestiaire sécurisé'
        ];

        $advantages = [];

        // Étape 1 : on crée les entités sans slug
        foreach ($advantageNames as $i => $name) {
            $advantage = new Advantage();
            $advantage->setTitle($name);
            $manager->persist($advantage);
            $advantages[] = $advantage;
        }
        // on flush pour générer les IDs
        $manager->flush();

        // Étape 3 : maintenant qu'on a les ID, on peut générer les slugs
        foreach ($advantages as $i => $advantage) {
            $advantage->makeSlug();
            $manager->persist($advantage);
            $this->addReference('advantage-' . $i, $advantage);
        }
        //et ca re flush pour enregistrer les slugs
        $manager->flush();
    }
}
