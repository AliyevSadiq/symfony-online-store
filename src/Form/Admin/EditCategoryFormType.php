<?php

namespace App\Form\Admin;

use App\Entity\Category;
use App\Form\DTO\EditCategoryModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditCategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,[
                'label'=>'Title ',
                'required'=>false, //default value is true
                'constraints'=>[
                    new NotBlank()
                ],
                'attr'=>[
                    'class'=>'form-control'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EditCategoryModel::class,
        ]);
    }
}
