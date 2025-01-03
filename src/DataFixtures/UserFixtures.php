<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Enum\UserRole;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher) {
        $this->hasher = $hasher;
    }
    
    public function load(ObjectManager $manager): void {
        $faker = Factory::create('fr_FR');
    
        $noms = ["Luca", "Marina", "Mathis", "Laurent", "Neymar", "Ronaldo", "Margot", "Jessica"];
        
        for ($i = 0; $i < count($noms); $i++) {
            $user = new User();
            $user->setNom($noms[$i]);
            $user->setEmail($faker->unique()->email);
            
            $user->setPassword($this->hasher->hashPassword($user, 'Password123!'));
    
            if ($i === 1 || $i === 3 || $i === 4) {
                $user->setRole(UserRole::ADMIN);
            } else {
                $user->setRole(UserRole::USER);
            }
    
            $manager->persist($user);
        }
    
        $adminUser = new User();
        $adminUser->setNom("patrick");
        $adminUser->setEmail("admin@gmail.com");
        $adminUser->setPassword($this->hasher->hashPassword($adminUser, 'password'));
        $adminUser->setRole(UserRole::ADMIN);
    
        $manager->persist($adminUser);

        $normalUser = new User();
        $normalUser->setNom("jean");
        $normalUser->setEmail("jean@gmail.com");
        $normalUser->setPassword($this->hasher->hashPassword($normalUser, 'password'));
        $normalUser->setRole(UserRole::USER);

        $manager->persist($normalUser);
    
        $manager->flush();
    }
    
}