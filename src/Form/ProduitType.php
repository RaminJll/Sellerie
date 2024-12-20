<?php

namespace App\Form;

use App\Entity\Produit;
use App\Enum\ProduitEtat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $typesProduit = [
            'selle', 'mors', 'couverture', 'pansage', 'licol', 'harnais', 'corde', 'bouchon',
            'nourriture', 'clôture', 'accessoire', 'paillasse', 'seau', 'râteau', 'brosse', 'panier',
            'polos', 'sweet', 'chaussette', 'botte', 'casque', 'gants', 'pantalon', 'chemise',
            'casquette', 't-shirt', 'doudoune'
        ];

        $etats = [
            'Neuf' => ProduitEtat::Neuf,
            'Bon état' => ProduitEtat::BonEtat,
            'Usé' => ProduitEtat::USE,
            'Hors Service' => ProduitEtat::HorsService,
            'En réparation' => ProduitEtat::Reparation,
        ];

        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le nom est obligatoire.']),
                ],
            ])
            ->add('categorie', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => [
                    'Cheval' => 'cheval',
                    'Écurie' => 'écurie',
                    'Cavalier' => 'cavalier',
                    'Cavalière' => 'cavalière',
                    'Enfant' => 'enfant',
                    'Poney' => 'poney',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La catégorie est obligatoire.']),
                ],
            ])
            ->add('etat', ChoiceType::class, [
                'label' => 'État',
                'choices' => $etats,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'état est obligatoire.']),
                ],
            ])
            ->add('categorie_rayon', ChoiceType::class, [
                'label' => 'Catégorie Rayon',
                'choices' => [
                    'Cheval' => 'cheval',
                    'Écurie' => 'écurie',
                    'Cavalier' => 'cavalier',
                    'Cavalière' => 'cavalière',
                    'Enfant' => 'enfant',
                    'Poney' => 'poney',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La catégorie rayon est obligatoire.']),
                ],
            ])
            ->add('type_produit', ChoiceType::class, [
                'label' => 'Type de produit',
                'choices' => array_combine($typesProduit, $typesProduit),
                'placeholder' => 'Choisir un type de produit',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le type de produit est obligatoire.']),
                ],
            ])
            ->add('etagere', IntegerType::class, [
                'label' => 'Étagère',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le numéro d\'étagère est obligatoire.']),
                    new Assert\Positive(['message' => 'L\'étagère doit être un nombre positif.']),
                ],
            ])
            ->add('planning', DateType::class, [
                'label' => 'Planning',
                'widget' => 'single_text',
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d'),
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La date de planning est obligatoire.']),
                    new GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date de planning doit être une date future.',
                    ]),
                ],
            ])
            ->add('date_achat', DateType::class, [
                'label' => 'Date d\'achat',
                'widget' => 'single_text',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La date d\'achat est obligatoire.']),
                    new Assert\LessThanOrEqual([
                        'value' => 'today', // Date maximale : aujourd'hui
                        'message' => 'La date d\'achat doit être dans le passé ou aujourd\'hui.',
                    ]),
                ],
                'attr' => [
                    'max' => (new \DateTime())->format('Y-m-d'),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
