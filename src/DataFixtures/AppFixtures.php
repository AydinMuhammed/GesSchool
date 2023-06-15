<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\User;
use App\Entity\Ecole;
use App\Entity\Classe;
use App\Entity\Eleve;
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

    // Commande pour inserer les données 
    // php bin/console doctrine:fixtures:load
    public function load(ObjectManager $manager)
{
    // Ajout des écoles de test
    $ecoles = [];
    for ($i = 0; $i < 60; $i++) {
        $ecole = new Ecole();
        $ecole->setNomEcole('Ecole '.$i);
        $ecole->setTelephoneEcole('0123456789');
        $ecole->setMailEcole('ecole'.$i.'@example.com');
        $ecole->setAdresseEcole('Adresse école '.$i);
        $ecole->setVilleEcole('Ville école '.$i);

        $manager->persist($ecole);
        $ecoles[] = $ecole;
    }
    $manager->flush();

    // Récupérer toutes les écoles existantes
    $ecoles = $manager->getRepository(Ecole::class)->findAll();

    $generatedClasses = [];
    for ($i = 0; $i < 300; $i++) {
        $classe = new Classe();
        $classe->setNomClasse('Classe '.$i);
        $classe->setSectionClasse('Section '.$i);

        // Lier la classe à une école au hasard
        $randomEcole = $ecoles[array_rand($ecoles)];
        $classe->setEcole($randomEcole);

        $manager->persist($classe);
        $generatedClasses[] = $classe;
    }
    
    $manager->flush();

        // Récupérer toutes les classes existantes une seule fois
        $classes = $manager->getRepository(Classe::class)->findAll();

        $eleves = [];
        for ($i = 0; $i < 1500; $i++) {
            $eleve = new Eleve();
            $eleve->setNomEleve('Nom '.$i);
            $eleve->setPrenomEleve('Prénom '.$i);
            $eleve->setAdresseEleve('Adresse '.$i);
            $eleve->setVilleEleve('Ville '.$i);

            $dateNaissance = new \DateTime();
            $dateNaissance->setDate(2000, random_int(1, 12), random_int(1, 28));
            $eleve->setDateNaissaneEleve($dateNaissance);

            // Lier l'élève à une classe au hasard
            $randomClasse = $classes[array_rand($classes)];
            $eleve->setClasse($randomClasse);

            $manager->persist($eleve);
            $eleves[] = $eleve;
        }

        $manager->flush();

    
    
    /** @var PasswordAuthenticatedUserInterface|User $user */
    // Utilisateur "sefa"
    $sefaUser = new User();
    $sefaUser->setEmail('sefa@sefa.com');
    $sefaUser->setPrenom('sefa');
    $sefaUser->setNom('gunendi');
    $dateNaissance = DateTime::createFromFormat('d/m/Y', '08/10/1999');
    $sefaUser->setDateNaissance($dateNaissance);
    $hashedPassword = $this->passwordHasher->hashPassword($sefaUser, 'Azerty.000');
    $sefaUser->setMotDePasse($hashedPassword);
    $sefaUser->setRoles(['ROLE_ADMIN']);
    $manager->persist($sefaUser);
    $manager->flush();

    // Utilisateur "user"
    $userUser = new User();
    $userUser->setEmail('user@user.com');
    $userUser->setPrenom('user');
    $userUser->setNom('user');
    $dateNaissance = DateTime::createFromFormat('d/m/Y', '08/10/2000');
    $userUser->setDateNaissance($dateNaissance);
    $hashedPassword = $this->passwordHasher->hashPassword($userUser, 'Azerty.000');
    $userUser->setMotDePasse($hashedPassword);
    $userUser->setRoles(['ROLE_USER']);
    $manager->persist($userUser);

    // Utilisateur "test"
    $testUSer = new User();
    $testUSer->setEmail('test@test.com');
    $testUSer->setPrenom('test');
    $testUSer->setNom('test');
    $dateNaissance = DateTime::createFromFormat('d/m/Y', '08/10/2000');
    $testUSer->setDateNaissance($dateNaissance);
    $hashedPassword = $this->passwordHasher->hashPassword($testUSer, 'Azerty.000');
    $testUSer->setMotDePasse($hashedPassword);
    $testUSer->setRoles(['ROLE_USER']);
    $manager->persist($testUSer);

    // Lier les écoles aux utilisateurs
    for ($i = 0; $i < 20; $i++) {
        $sefaUser->addEcole($ecoles[$i]);
        $userUser->addEcole($ecoles[$i + 20]);
        $testUSer->addEcole($ecoles[$i + 40]);
    }
    

    $manager->flush();
}
}