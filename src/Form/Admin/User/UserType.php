<?php

namespace App\Form\Admin\User;

use App\Entity\User\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('name', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('password', PasswordType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ])
            ->add('isEnabled', CheckboxType::class, [
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

}
