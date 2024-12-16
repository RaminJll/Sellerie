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

        // Récupérer uniquement les produits dont l'état est Neuf, BonEtat, ou USE
        $produits = $manager->getRepository(Produit::class)->findBy([
            'etat' => [
                ProduitEtat::Neuf,
                ProduitEtat::BonEtat,
                ProduitEtat::USE,
            ]
        ]);

        // Récupération des utilisateurs depuis la base
        $users = $manager->getRepository(User::class)->findAll();

        // Sélectionner 20 produits aléatoires parmi ceux filtrés
        $selectedProduits = $faker->randomElements($produits, 20);

        foreach ($selectedProduits as $produit) {
            // Générer un nombre aléatoire d'historiques pour chaque produit
            $historiquesCount = rand(1, 2);

            for ($i = 0; $i < $historiquesCount; $i++) {
                $historique = new Historiques();

                // Produit associé
                $historique->setIdProduit($produit);

                // Utilisateur associé (aléatoire)
                $historique->setIdUser($faker->randomElement($users));

                // Date d'empreinte : entre la date d'achat du produit et aujourd'hui
                // Date d'empreinte : entre la date d'achat du produit et aujourd'hui
                $dateEmpreinte = $faker->dateTimeBetween($produit->getDateAchat(), 'now');
                $historique->setDateEmpreinte($dateEmpreinte);

                // Calcul de la date limite pour la date de rendu (15 jours après la date d'empreinte)
                $dateLimite = clone $dateEmpreinte; // Cloner pour ne pas modifier $dateEmpreinte
                $dateLimite->modify('+15 days');

                // Date de rendu : entre la date d'empreinte et 15 jours après la date d'empreinte
                $dateRendu = $faker->dateTimeBetween($dateEmpreinte, $dateLimite);

                // S'assurer que l'heure de la date de retour est à 00:00:00
                $dateRendu->setTime(0, 0, 0);

                // Définir la date de retour
                $historique->setDateRendu($dateRendu);


                // État initial (repris de l'état du produit, qui est garanti de correspondre à l'un des trois états)
                $historique->setEtatInit($produit->getEtat());

                $historique->setSignalement('Rien à signaler');

                // Calculer le retard en jours
                $interval = $dateEmpreinte->diff($dateRendu);
                $daysDiff = $interval->days;

                // Si la différence est supérieure à 10 jours, ajouter le retard
                if ($daysDiff > 10) {
                    $retard = $daysDiff -10;
                    $historique->setRetard($retard);
                } else {
                    // Sinon, il n'y a pas de retard
                    $historique->setRetard(0);
                }

                // Persister l'historique
                $manager->persist($historique);
            }
        }

        // Sauvegarde dans la base de données
        $manager->flush();
    }
}
