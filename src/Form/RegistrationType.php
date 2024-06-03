<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', null, ['attr' => ['class' => 'form-control']])
            ->add('lastname', null, ['attr' => ['class' => 'form-control']])
            ->add('email', null, ['attr' => ['class' => 'form-control']])
            ->add('telephone', null, ['attr' => ['class' => 'form-control']])
            ->add('street', null, ['attr' => ['class' => 'form-control']])
            ->add('streetnumber', null, ['attr' => ['class' => 'form-control']])
            ->add('town', null, ['attr' => ['class' => 'form-control']])
            ->add('zipcode', null, ['attr' => ['class' => 'form-control']])
            ->add('country', null, ['attr' => ['class' => 'form-control']])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
