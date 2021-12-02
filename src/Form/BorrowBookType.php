<?php

namespace App\Form;

use App\Entity\Borrow;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class BorrowBookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('borrowedAt',DateType::class,[
        'widget'=>'single_text',
    ])
            ->add('returnedAt',DateType::class,[
        'widget'=>'single_text'
    ])
         //   ->add('isBack')
           /*->add('users',EntityType::class
            ,['class' => 'App\Entity\User',
                'attr' => [ 'readonly' => true ]])
           */
           ->add("Borrow", SubmitType::class, [
                'attr' => ['class' => 'btn sec-bg mx-2']
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Borrow::class,
        ]);
    }
}
