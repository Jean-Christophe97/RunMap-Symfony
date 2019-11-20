<?php

namespace App\Form;

use App\Entity\Place;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class PlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'label'  => 'Nom',
            ])
            ->add('adress', TextType::class,[
                'label'  => 'Adresse',
            ])
            ->add('schedule', TextType::class,[
                'label'  => 'Horraire',
            ])
            ->add('complementinfo', TextareaType::class,[
                'label'  => 'Informations',
            ])
            // ->add('created_at')
            // ->add('longitude')
            // ->add('latitude')
            // ->add('city', TextType::class,[
            //     'label'  => 'Ville',
            // ])
            // ->add('sports')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Place::class,
        ]);
    }
}
