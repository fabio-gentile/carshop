<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, $this->getConfiguration("Email", "Votre adresse e-mail"))
            ->add('password', PasswordType::class, $this->getConfiguration("Mot de passe", "Votre mot de passe"))
            ->add('passwordConfirm', PasswordType::class, $this->getConfiguration("Confirmation du mot de passe", "Veuillez confirmer votre mot de passe"))
            ->add('firstName', TextType::class, $this->getConfiguration("Prénom", "Votre prénom"))
            ->add('lastName', TextType::class, $this->getConfiguration("Nom", "Votre nom de famille..."))
            ->add('picture', FileType::class, [
                'label' => 'Avatar(jpg, png, gif)',
                'required' => false
            ])
            ->add('description', TextareaType::class, $this->getConfiguration("Description détaillée", "Présentez vous avec un peu plus de détails"));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
