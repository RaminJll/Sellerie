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

        $categories = [
            'cheval' => ['selle', 'mors', 'couverture', 'pansage', 'licol', 'harnais', 'corde', 'bouchon'],
            'écurie' => ['nourriture', 'clôture', 'accessoire', 'paillasse', 'seau', 'râteau', 'brosse', 'panier'],
            'cavalier' => ['polos', 'sweet', 'chaussette', 'botte', 'casque', 'gants', 'pantalon', 'chemise'],
            'cavalière' => ['polos', 'sweet', 'chaussette', 'botte', 'casque', 'gants', 'pantalon', 'chemise'],
            'enfant' => ['polos', 'sweet', 'chaussette', 'botte', 'casquette', 'gants', 't-shirt', 'doudoune'],
            'poney' => ['selle', 'mors', 'couverture', 'pansage', 'licol', 'harnais', 'corde', 'bouchon']
        ];

        $etats = [
            ProduitEtat::Neuf,
            ProduitEtat::BonEtat,
            ProduitEtat::USE,
            //ProduitEtat::Reparation,
            //ProduitEtat::HorsService,
        ];

        foreach ($categories as $categorie => $types) {
            $etagereCounter = [];
            $articlesPerEtagere = 5;

            foreach ($types as $type) {
                if (!isset($etagereCounter[$type])) {
                    $etagereCounter[$type] = 1;
                }

                $articleCount = 0;

                for ($i = 1; $i <= 30; $i++) {
                    $produit = new Produit();

                    $produit->setNom($type . $i);

                    $produit->setCategorie($categorie);
                    $produit->setTypeProduit($type);

                    $produit->setEtat($faker->randomElement($etats));

                    $produit->setDateAchat($faker->dateTimeBetween('-3 years', 'now'));

                    if (mt_rand(1, 40) === 1) {
                        $produit->setPlanning(new DateTime('today'));
                    } else {
                        $produit->setPlanning($faker->dateTimeBetween('+1 month', '+6 months'));
                    }

                    $produit->setCategorieRayon($categorie);

                    if ($articleCount >= $articlesPerEtagere) {
                        $etagereCounter[$type]++;
                        $articleCount = 0;
                    }

                    $produit->setEtagere((string) $etagereCounter[$type]);

                    $articleCount++;

                    $manager->persist($produit);
                }
            }
        }

        $manager->flush();
    }
}
