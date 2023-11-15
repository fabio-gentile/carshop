<?php

namespace App\Form;

use App\Entity\Advert;
use \App\Form\AdvertImageType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('brand', TextType::class, $this->getConfiguration('Marque', 'Marque de la voiture'))
            ->add('model', TextType::class, $this->getConfiguration('Modèle', 'Modèle de la voiture'))
            ->add('price', MoneyType::class, $this->getConfiguration('Prix de la voiture', 'Prix de la voiture'))
            ->add('kilometers', IntegerType::class, $this->getConfiguration('Kilométrage', 'Kilométrage de la voiture'))
            ->add('coverImage', UrlType::class, $this->getConfiguration("Url de l'image", "Donnez l'adresse de votre image"))
            ->add('totalOwners', IntegerType::class, $this->getConfiguration('Nombre de propriétaire(s)', 'Nombre de propriétaire(s)'))
            ->add('EngineDisplacement', NumberType::class, $this->getConfiguration('Cylindrée', 'Cylindrée'))
            ->add('power', NumberType::class, $this->getConfiguration('Puissance moteur', 'Puissance'))
            ->add('fuelType', TextType::class, $this->getConfiguration('Type de carburant', 'Type de carburant de la voiture'))
            ->add('transmission', TextType::class, $this->getConfiguration('Transmission', 'Transmission de la voiture'))
            ->add('description', TextareaType::class, $this->getConfiguration('Description détaillée', 'Description de la voiture'))
            ->add('car_options', TextType::class, $this->getConfiguration('Options du véhicule', 'Option(s) disponible(s) (optionnel)', ['required' => false]))
            ->add('yearOfRegistration', DateType::class, $this->getConfiguration('Annee de mise en circulation', 'Année de mise en circulaire de la voiture' , [
                'format' => 'dd-MMMM-yyyy',
                'years' => range(2000, 2023)
            ]))
            ->add('advertImages', CollectionType::class, [
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
