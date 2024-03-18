<?php

namespace App\Controller\Admin;

use App\Entity\GBooky;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Doctrine\ORM\QueryBuilder;

class GBookyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return GBooky::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('member', 'creator'),
            BooleanField::new('Published')
                ->setLabel('Published') 
                ->renderAsSwitch() 
                ->setFormTypeOption('required', false), 
            TextField::new('GDescription', 'Description'),
            AssociationField::new('galeries', 'Books')
                ->onlyOnForms()
                ->hideWhenCreating()
                //->setTemplatePath('admin/fields/inventaire_objets.html.twig')
                ->setQueryBuilder(function (QueryBuilder $queryBuilder) {
                    $currentGalerie = $this->getContext()->getEntity()->getInstance();
                    $creator = $currentGalerie->getMember();
                    $memberId = $creator->getId();
                    $queryBuilder
                        ->leftJoin('entity.MyBooks', 'i')
                        ->leftJoin('i.member', 'm')
                        ->andWhere('m.id = :member_id')
                        ->setParameter('member_id', $memberId);
                    return $queryBuilder;
                }),
        ];
    }
}
