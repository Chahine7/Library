<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('title')
            ->add('isbn')
            ->add('price')
            ->add('StockQuantity')
            ->add('editedAt')
            ->add('isAvailable')
            ->add('editor')
            ->add('categorie')
            ->add('authors')
            ->add('photo', FileType::class, [

                'required' => false,

                'mapped' => false,

                'constraints' => [

                    new Image(['maxSize' => '1024k'])

],

])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
