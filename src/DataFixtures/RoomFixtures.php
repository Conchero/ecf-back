<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Room;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\DataFixtures\Collection;


use App\DataFixtures\UserFixtures;
//manque la route pour images


class RoomFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $rooms = [];

        //         for ($i = 0; $i < 20; $i++) {

        //             $room = new Room();

        //             /** @var \App\Entity\User $owner */
        //             $owner = $this->getReference('user-' . $faker->numberBetween(0, 9), \App\Entity\User::class);
        //             $room = new Room();
        //             $room->setTitle($faker->company . ' Salle')
        //                 ->setImage('default.jpg')
        // 10 noms de salle avec leurs images correspondantes
        $roomTemplates = [
            'Loft Industriel'     => 'loft_industriel.jpg',
            'Salle Victor Hugo'   => 'victor_hugo.jpg',
            'Studio Concorde'     => 'studio_concorde.jpg',
            'Espace Montmartre'   => 'montmartre.jpg',
            'Salon Saint-Germain' => 'saint_germain.jpg',
            'Atelier Bastille'    => 'atelier_bastille.jpg',
            'Galerie Louvre'      => 'louvre.jpg',
            'Salle Opéra'         => 'opera.jpg',
            'Carré du Palais'     => 'carre_palais.jpg',
            'Terrasse Bastille'   => 'terrasse_bastille.jpg',
        ];

        $descriptions = [
            "Salle moderne entièrement équipée, idéale pour vos réunions d’affaires ou vos sessions de formation.",
            "Espace lumineux avec vue sur la ville, parfait pour des ateliers créatifs ou des séminaires.",
            "Salle calme et climatisée, équipée d’un vidéoprojecteur, tableau blanc et Wi-Fi haut débit.",
            "Grande salle polyvalente pouvant accueillir jusqu’à 50 personnes pour conférences, événements ou team-building.",
            "Salle cosy au design épuré, idéale pour des réunions en petit comité ou des séances de coworking.",
            "Espace professionnel avec matériel audiovisuel complet, adapté aux visios et présentations commerciales.",
            "Salle modulable avec tables amovibles, idéale pour des cours, réunions d’équipe ou formations techniques.",
            "Ambiance chaleureuse et éclairage naturel pour cette salle parfaite pour brainstorming ou interviews.",
            "Espace de travail ergonomique, parfait pour les freelances ou les sessions de travail en groupe.",
            "Salle connectée, dotée de prises réseau, écrans HDMI et tableau interactif dernière génération."
        ];

        $i = 0;
        foreach ($roomTemplates as $title => $image) {

            /** @var \App\Entity\User $owner */

            $owner = $this->getReference('user-' . $faker->numberBetween(0, 9), \App\Entity\User::class);
            $room = new Room();
            $room
                ->setTitle($title)
                ->setImage('uploads/images/rooms/' . $image)
                ->setLocalisation($faker->address())
                ->setKeywords(implode(', ', $faker->words(5)))
                ->setDescription($descriptions[$i])
                ->setIsAvailable($faker->boolean(90))
                ->setCapacity($faker->numberBetween(10, 200))
                ->setOwner($owner)
                ->addAdvantage($this->getReference('advantage-' . $faker->numberBetween(0, 3), \App\Entity\Advantage::class))
                ->addAdvantage($this->getReference('advantage-' . $faker->numberBetween(0, 3), \App\Entity\Advantage::class))
                ->addAdvantage($this->getReference('advantage-' . $faker->numberBetween(0, 3), \App\Entity\Advantage::class))
                ->addEquipment($this->getReference('equipment-' . $faker->numberBetween(0, 3), \App\Entity\Equipment::class))
                ->addEquipment($this->getReference('equipment-' . $faker->numberBetween(0, 3), \App\Entity\Equipment::class))
                ->addEquipment($this->getReference('equipment-' . $faker->numberBetween(0, 3), \App\Entity\Equipment::class))
                ->addSoftware($this->getReference('software-' . $faker->numberBetween(0, 3), \App\Entity\Software::class))
                ->addSoftware($this->getReference('software-' . $faker->numberBetween(0, 3), \App\Entity\Software::class))
                ->addSoftware($this->getReference('software-' . $faker->numberBetween(0, 3), \App\Entity\Software::class))->makeSlug($i);

            $manager->persist($room);
            $rooms[] = $room;

            $this->addReference('room-' . $i, $room);
            $i++;
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
