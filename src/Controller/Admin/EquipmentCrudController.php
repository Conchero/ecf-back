<?php

namespace App\Controller\Admin;

use App\Entity\Equipment;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EquipmentCrudController extends AbstractCrudController
{
    private EntityManagerInterface $em;

    // Injection de l'EntityManager via le constructeur
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getEntityFqcn(): string
    {
        return Equipment::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            TextField::new('slug'),
        ];
    }

    // Exemple d'une méthode personnalisée pour créer un équipement
    public function createEquipment(Equipment $equipment): void
    {
        // Persiste l'entité en attente d'être enregistrée
        $this->em->persist($equipment);

        // Exécute la requête SQL pour enregistrer en base
        $this->em->flush();
    }
}
