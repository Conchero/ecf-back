<?php

namespace App\Controller\Admin;

use Dom\Entity;
use App\Entity\Room;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RoomCrudController extends AbstractCrudController
{
    public const ROOM_BASE_PATH = 'uploads/images';
    public const ROOM_UPLOAD_DIR = 'public/uploads/images';
    public static function getEntityFqcn(): string
    {
        return Room::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            TextField::new('slug')->hideOnForm(),
            ImageField::new('image')
                ->setBasePath(self::ROOM_BASE_PATH)
                ->setUploadDir(self::ROOM_UPLOAD_DIR)
                ->setSortable(false),
            TextField::new('localisation'),

            TextField::new('keywords')->hideOnForm(),
                IntegerField::new('capacity'),
            TextEditorField::new('description'),
            BooleanField::new('is_available'),
        
        ];
    }
   
}
