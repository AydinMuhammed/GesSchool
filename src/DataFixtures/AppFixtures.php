<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;


class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    
    public function load(ObjectManager $manager)
    {
        /** @var PasswordAuthenticatedUserInterface|User $user */
        $user = new User();
        $user->setEmail('sefa@sefa.com');
        $user->setPrenom('sefa');
        $user->setNom('gunendi');
        $dateNaissance = DateTime::createFromFormat('d/m/Y', '08/10/1999');
        $user->setDateNaissance($dateNaissance);
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'Azerty.000');
        $user->setMotDePasse($hashedPassword);

        $manager->persist($user);
        $manager->flush();

        $user = new User();
        $user->setEmail('user@user.com');
        $user->setPrenom('user');
        $user->setNom('user');
        $dateNaissance = DateTime::createFromFormat('d/m/Y', '08/10/2000');
        $user->setDateNaissance($dateNaissance);
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'Azerty.000');
        $user->setMotDePasse($hashedPassword);

        $manager->persist($user);
        $manager->flush();
    }
}