<?php

namespace App\Form;

use App\Entity\Maintenances;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class MaintenancesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextType::class, [
                'label' => 'Description du problème',
                'required' => true,
            ])
            ->add('cout_maintenance', NumberType::class, [
                'label' => 'Coût de la maintenance',
                'scale' => 2,
            ])
            ->add('date_fin_maintenance', DateType::class, [
                'label' => 'Date de fin de maintenance',
                'widget' => 'single_text',
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d'),
                ],
                'constraints' => [
                    new Assert\GreaterThan([
                        'value' => 'today',
                        'message' => 'La date de fin de maintenance doit être dans le futur.',
                    ]),
                ],
            ])
            ->add('future_date_maintenance', DateType::class, [
                'label' => 'Prochaine date de maintenance',
                'widget' => 'single_text',
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d'),
                ],
                'constraints' => [
                    new Assert\GreaterThan([
                        'value' => 'today',
                        'message' => 'La date de fin de maintenance doit être dans le futur.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Maintenances::class,
        ]);
    }
}
