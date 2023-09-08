<?php

namespace App\Form\Admin\Settings;

use App\Entity\Settings;
use App\Enum\SettingsEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('settingKey' , ChoiceType::class,[
                'multiple' => false,
                'choices' => SettingsEnum::all(),
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('settingValue' , TextType::class ,[
            'required' => true,
            'attr' => [
            'class' => 'form-control'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Settings::class,
        ]);
    }
}
