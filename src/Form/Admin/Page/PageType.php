<?php

namespace App\Form\Admin\Page;

use App\Entity\Page\Page;
use App\Enum\Page\PageStatusEnum;

use App\Enum\Page\PageTagsEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('content', TextareaType::class, [
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

            ->add('tag', ChoiceType::class,[
                'multiple' => false,
                'choices' => PageTagsEnum::all(),
                'attr' => [
                    'class' => 'form-control'
                ]
            ])

            ->add('status', ChoiceType::class, [
                'multiple' => false,
                'choices' => PageStatusEnum::all(),
                'attr' => [
                    'class' => 'form-control'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
        ]);
    }
}
