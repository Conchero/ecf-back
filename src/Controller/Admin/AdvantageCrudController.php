<?php

namespace App\Controller\Admin;

use App\Entity\Advantage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AdvantageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Advantage::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            TextField::new('slug')->hideOnForm(),
        ];
    }
    
}
