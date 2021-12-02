<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchBorrowedBookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', EntityType::class, [
                "class" => User::class,
                "choice_label" => "id",
                'attr' => ['class' => 'form-control']
            ])
           /* ->add('book',EntityType::class, [
                "class" =>Book::class,
                'choice_label' =>"id",
                'attr' => ['class' => 'form-control']
            ])*/
            ->add("Rechercher", SubmitType::class, [
                'attr' => ['class' => 'btn sec-bg mx-2']
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => ['class' => 'form-inline my-3']
        ]);
    }
}