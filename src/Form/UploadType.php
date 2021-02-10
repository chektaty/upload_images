<?php

namespace App\Form;

use App\Entity\Images;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class UploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',FileType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Sélectionnez une image ...',
                    'accept' => 'image/*',
                    'required'=>false,
                    'class'=>'form-group'

                    
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider ',
                'attr' => [
                    'class' => 'btn btn-success btn-block form-group'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Images::class,
        ]);
    }
}
