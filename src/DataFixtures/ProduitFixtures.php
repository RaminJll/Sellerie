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
            $etagereCounter = []; // Compteur pour chaque type de produit

            foreach ($types as $type) {
                if (!isset($etagereCounter[$type])) {
                    $etagereCounter[$type] = 0;
                }

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

                    // Étagère : Numéro incrémental par type de produit
                    $etagereCounter[$type]++;
                    $produit->setEtagere((string) $etagereCounter[$type]);

                    // Enregistrement
                    $manager->persist($produit);
                }
            }
        }

        $manager->flush();
    }
}
