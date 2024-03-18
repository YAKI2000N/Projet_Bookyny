<?php

namespace App\Controller\Admin;

use App\Entity\MyBooks;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;

class MyBooksCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MyBooks::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('Name_INV'),
            AssociationField::new('books', 'Books'), 
            TextEditorField::new('InvDesc'), 
            DateField::new('created')->setLabel('Date of Creation')->setFormat('yyyy-MM-dd'),
        ];
    }
}