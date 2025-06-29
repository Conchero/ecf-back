<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id') ->hideOnForm(),
             
            TextField::new('email'),
            TextField::new('password'),
            TextField::new('phoneNumber'),
            TextField::new('firstName'),
            TextField::new('lastName'),
            
           
            DateTimeField::new('created_at')->hideOnForm(),
       
        ];
    }
    
    
    
}
