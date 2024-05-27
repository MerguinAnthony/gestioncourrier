<?php

namespace App\DataFixtures;


use App\Entity\User;
use App\Entity\Archmail;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    // On injecte le service UserPasswordHasherInterface

    private UserPasswordHasherInterface $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    // On crée un utilisateur avec un mot de passe hashé

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('emmanuelle.corte@deyvillers.fr');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
        $user->setPagination(10);
        $manager->persist($user);

        $manager->flush();
    }
}
