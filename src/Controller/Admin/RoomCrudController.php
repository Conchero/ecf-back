<?php

namespace App\Controller\Admin;

use App\Entity\Room;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RoomCrudController extends AbstractCrudController
{
    public const ROOM_BASE_PATH = 'uploads/images';
    public const ROOM_UPLOAD_DIR = 'public/uploads/images';

    public static function getEntityFqcn(): string
    {
        return Room::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig'); // Corrigé : .html.twig
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            TextField::new('title'),
           SlugField::new('slug')->setTargetFieldName('title'),

            ImageField::new('image')
                ->setBasePath(self::ROOM_BASE_PATH)
                ->setUploadDir(self::ROOM_UPLOAD_DIR)
                ->setSortable(false),

            TextField::new('localisation'),
            TextField::new('keywords')->hideOnForm(),

            IntegerField::new('capacity'),

            AssociationField::new('equipments')
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'multiple' => true,
                    'expanded' => true,
                ]),

            AssociationField::new('advantages')
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'multiple' => true,
                    'expanded' => true,
                ]),

            AssociationField::new('softwares') // anciennement "logiciels"
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'multiple' => true,
                    'expanded' => true,
                ]),

            TextEditorField::new('description')
                ->setFormType(CKEditorType::class),

            BooleanField::new('is_available'),
        ];
    }
}
