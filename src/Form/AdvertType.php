<?php

namespace App\Form;

use App\Entity\Advert;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('brand', TextType::class, $this->getConfiguration('Introduction', 'Marque de la voiture'))
            ->add('model', TextType::class, $this->getConfiguration('Introduction', 'Modèle de la voiture'))
            ->add('price')
            ->add('kilometers')
            ->add('coverImage')
            ->add('totalOwners')
            ->add('EngineDisplacement')
            ->add('power')
            ->add('fuelType')
            ->add('transmission')
            ->add('description')
            ->add('car_options')
            ->add('yearOfRegistration')
            ->add('seller')
            ->add('images', CollectionType::class, [
                'entry_type' => AdvertImageType::class,
                'allow_add' => true,
                'allow_delete' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Advert::class,
        ]);
    }
}
