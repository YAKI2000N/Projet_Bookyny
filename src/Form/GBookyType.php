<?php

namespace App\Form;

use App\Entity\GBooky;
use App\Entity\Books;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use App\Repository\BooksRepository;


class GBookyType extends AbstractType
{
    //dump($options);
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $gbooky = $options['data'] ?? null;
        $member= $gbooky->getMember();
        $builder
            ->add('GDescription')
            ->add('Published', CheckboxType::class, [
                'required' => false,
            ])
            ->add('galeries',EntityType::class, [
                    'class' => Books::class,
                    'choice_label' => 'title', 
                    'multiple' => true, 
                    'expanded' => true, 
                    'attr' => ['class' => 'form-control'],
            ])
            ->add('member', null, [
                'disabled' => true,
                ])
            ->add('galeries',null, [
                'query_builder' => function (BooksRepository $er) use ($member) {
                return $er->createQueryBuilder('g')
                ->leftJoin('g.myBooks', 'i')
                ->andWhere('i.member = :membre')
                ->setParameter('membre', $member)
                ;
                }
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GBooky::class,
        ]);
    }
}
