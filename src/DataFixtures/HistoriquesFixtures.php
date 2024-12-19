<?php

namespace App\DataFixtures;

use App\Entity\Historiques;
use App\Entity\Produit;
use App\Entity\User;
use App\Enum\ProduitEtat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class HistoriquesFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            ProduitFixtures::class,
            UserFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $produits = $manager->getRepository(Produit::class)->findBy([
            'etat' => [
                ProduitEtat::Neuf,
                ProduitEtat::BonEtat,
                ProduitEtat::USE,
            ]
        ]);

        $users = $manager->getRepository(User::class)->findAll();

        $selectedProduits = $faker->randomElements($produits, 20);

        foreach ($selectedProduits as $produit) {
            $historiquesCount = rand(1, 2);

            for ($i = 0; $i < $historiquesCount; $i++) {
                $historique = new Historiques();

                $historique->setIdProduit($produit);

                $historique->setIdUser($faker->randomElement($users));


                $dateEmpreinte = $faker->dateTimeBetween($produit->getDateAchat(), 'now');
                $historique->setDateEmpreinte($dateEmpreinte);

                $dateLimite = clone $dateEmpreinte;
                $dateLimite->modify('+15 days');

                $dateRendu = $faker->dateTimeBetween($dateEmpreinte, endDate: $dateLimite);

                $dateRendu->setTime(0, 0, 0);

                $historique->setDateRendu($dateRendu);


                $historique->setEtatInit($produit->getEtat());

                $historique->setSignalement('Rien à signaler');

                $interval = $dateEmpreinte->diff($dateRendu);
                $daysDiff = $interval->days;

                if ($daysDiff > 10) {
                    $retard = $daysDiff -10;
                    $historique->setRetard($retard);
                } else {
                    $historique->setRetard(0);
                }

                $manager->persist($historique);
            }
        }

        $manager->flush();
    }
}
