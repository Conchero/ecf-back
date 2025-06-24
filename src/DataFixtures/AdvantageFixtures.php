<?php

namespace App\DataFixtures;

use App\Entity\Advantage;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AdvantageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Liste de 10 avantages pour une salle de fête
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

        foreach ($advantageNames as $i => $name) {
            $advantage = new Advantage();
            
            $advantage->setTitle($name)->makeSlug($i);

            $manager->persist($advantage);
            $advantages[] = $advantage;
        }

        $manager->flush();

        // Génération des slugs maintenant que les IDs existent
        foreach ($advantages as $i => $advantage) {
            $advantage->setSlug($advantage->toSlug());
            $manager->persist($advantage);
            $this->addReference('advantage-' . $i, $advantage);
        }

        // Deuxième flush pour enregistrer les slugs grosse galère
        $manager->flush();
    }
}

