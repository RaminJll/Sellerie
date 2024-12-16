<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use App\Enum\ProduitEtat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use DateTime;

class ProduitFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Données des catégories et types de produit
        $categories = [
            'cheval' => ['selle', 'mors', 'couverture', 'pansage'],
            'écurie' => ['nourriture', 'clôture', 'accessoire'],
            'cavalier' => ['polos', 'sweet', 'chaussette', 'botte'],
            'cavalière' => ['polos', 'sweet', 'chaussette', 'botte'],
            'enfant' => ['polos', 'sweet', 'chaussette', 'botte'],
        ];

        $etats = [
            ProduitEtat::Neuf,
            ProduitEtat::BonEtat,
            ProduitEtat::USE,
            ProduitEtat::Reparation,
            ProduitEtat::HorsService,
        ];

        foreach ($categories as $categorie => $types) {
            $etagereCounter = []; // Compteur pour l'étagère en cours pour chaque type
            $articlesPerEtagere = 5; // Nombre maximum d'articles par étagère

            foreach ($types as $type) {
                if (!isset($etagereCounter[$type])) {
                    $etagereCounter[$type] = 1; // Initialiser le numéro d'étagère à 1 pour chaque type
                }

                $articleCount = 0; // Compteur d'articles dans l'étagère actuelle

                for ($i = 1; $i <= 200; $i++) {
                    $produit = new Produit();

                    // Nom du produit
                    $produit->setNom($type . $i);

                    // Catégorie et type de produit
                    $produit->setCategorie($categorie);
                    $produit->setTypeProduit($type);

                    // État aléatoire
                    $produit->setEtat($faker->randomElement($etats));

                    // Date d'achat aléatoire (moins de 3 ans à partir d'aujourd'hui)
                    $produit->setDateAchat($faker->dateTimeBetween('-3 years', 'now'));

                    // Planning de maintenance (entre 1 et 6 mois à partir d'aujourd'hui)
                    $produit->setPlanning($faker->dateTimeBetween('+1 month', '+6 months'));

                    // Catégorie rayon (identique à la catégorie)
                    $produit->setCategorieRayon($categorie);

                    // Gestion de l'étagère
                    if ($articleCount >= $articlesPerEtagere) {
                        // Passer à l'étagère suivante si le maximum est atteint
                        $etagereCounter[$type]++;
                        $articleCount = 0;
                    }

                    $produit->setEtagere((string) $etagereCounter[$type]);

                    // Incrémenter le compteur d'articles pour l'étagère actuelle
                    $articleCount++;

                    // Enregistrement
                    $manager->persist($produit);
                }
            }
        }

        $manager->flush();
    }
}
