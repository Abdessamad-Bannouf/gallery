<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Image;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',  FileType::class, [
                'label' => 'Mettre une photo',
                'mapped' => true, 
                'required' => false,
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'mapped' => true,
                'expanded' => true,
                'multiple' => true,
                'required' => false,
                'choice_label' => 'name'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
