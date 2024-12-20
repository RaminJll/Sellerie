<?php
// src/Form/ReparationsType.php

namespace App\Form;

use App\Entity\Reparations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ReparationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description_probleme', TextType::class, [
                'label' => 'Description du problème',
                'required' => true,
            ])
            ->add('cout_reparation', NumberType::class, [
                'label' => 'Coût de la réparation',
                'scale' => 2,
            ])
            ->add('date_fin_reparation', DateType::class, [
                'label' => 'Date de fin de réparation',
                'widget' => 'single_text',
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d'),
                ],
                'constraints' => [
                    new Assert\GreaterThan([
                        'value' => 'today',
                        'message' => 'La date de fin de réparation doit être dans le futur.',
                    ]),
                ],
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reparations::class,
        ]);
    }
}