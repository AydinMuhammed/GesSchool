<?php

namespace App\Controller;

use App\Form\EcoleType;
use Doctrine;
use App\Entity\Ecole;
use App\Entity\User;
use App\Repository\EleveRepository;
use App\Repository\ClasseRepository;
use App\Form\EcoleAddType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class EcoleController extends AbstractController
{
    //fonction pour AFFICHER  les ecoles
    #[Route('/ecole', name: 'app_ecole')]
    public function index(ManagerRegistry $doctrine): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Vérifier si l'utilisateur est connecté
        if ($user) {
            // Récupérer les écoles associées à l'utilisateur
            $ecoles = $user->getEcoles();

            return $this->render('ecole/index.html.twig', [
                'ecoles' => $ecoles,
            ]);
        } else {
            // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        //$ecoles = $doctrine->getRepository(Ecole::class)->findBy([]);

        
    }

    

    //fonction pour AFFICHER les details d'une ecole sélectionnée
    #[Route('/ecole/{id}', name: 'app_ecole_show')]
    public function show(Ecole $ecole, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Vérifier si l'utilisateur est connecté et si l'école est associée à l'utilisateur
        if ($user && $ecole->getUsers()->contains($user)) {
            $eleves = $entityManager->createQuery(
                'SELECT e
                FROM App\Entity\Eleve e
                JOIN e.classe c
                JOIN c.ecole ecole
                WHERE ecole.id = :id
                ORDER BY e.nom_eleve ASC'
            )->setParameter('id', $ecole->getId())
            ->getResult();

            return $this->render('ecole/show_ecole.html.twig', [
                'ecole' => $ecole,
                'eleves' => $eleves,
            ]);
        }

        // Rediriger vers une page d'erreur ou une autre action appropriée
        throw $this->createNotFoundException('L\'école demandée n\'existe pas ou vous n\'avez pas l\'autorisation d\'y accéder.');
    }

    

    // fonctionj pour AJOUTER  une école 
    #[Route('/addEcole', name: 'ecole_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ecole = new Ecole();
        $form = $this->createForm(EcoleType::class, $ecole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'utilisateur connecté
            $user = $this->getUser();
    
            // Vérifier si l'utilisateur est connecté
            if ($user) {
                // Associer l'école à l'utilisateur
                $ecole->addUser($user);
            }
    
            $entityManager->persist($ecole);
            $entityManager->flush();
            
            // pour le message qui apparait lorqu'on ajoute une école  
            $this->addFlash('success', 'L\'école a été ajoutée avec succès !');
            return $this->redirectToRoute('app_ecole');
            // ---- fin---------
        }

        
        
        
        


        return $this->render('ecole/add_ecole.html.twig', [
            'ecole' => $ecole,
            'form' => $form->createView(),
        ]);
    }
    

    
    // fonction pour MODIFIER les informations d'une école
    #[Route('/ecole/{id}/edit', name: 'app_ecole_edit')]
    public function edit(Request $request, Ecole $ecole, ManagerRegistry $doctrine): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Vérifier si l'utilisateur est connecté et si l'école est associée à l'utilisateur
        if ($user && $ecole->getUsers()->contains($user)) {
            $form = $this->createFormBuilder($ecole)
                ->add('nom_ecole', TextType::class, [
                    'label' => 'Nom de l\'école'
                ])
                ->add('adresse_ecole', TextType::class, [
                    'label' => 'Adresse de l\'école'
                ])
                ->add('telephone_ecole', TextType::class, [
                    'label' => 'Téléphone de l\'école'
                ])
                ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $doctrine->getManager();
                $entityManager->flush();
                
                $this->addFlash('success', 'L\'école a été modifier avec succès !');
                return $this->redirectToRoute('app_ecole_show', ['id' => $ecole->getId()]);
            }

            return $this->render('ecole/edit_show_ecole.html.twig', [
                'form' => $form->createView(),
                'ecole' => $ecole,
            ]);
        }

        


        // Rediriger vers une page d'erreur ou une autre action appropriée
        throw $this->createNotFoundException('L\'école demandée n\'existe pas ou vous n\'avez pas l\'autorisation de la modifier.');
    }



    // fonction pour SUPPRIMER une école dédiée
    #[Route('/ecole/{id}/supprimer', name: 'app_ecole_delete')]
    public function delete(ManagerRegistry $doctrine, Ecole $ecole): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Vérifier si l'utilisateur est connecté et si l'école est associée à l'utilisateur
        if ($user && $ecole->getUsers()->contains($user)) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($ecole);
            $entityManager->flush();

            $this->addFlash('success', 'L\'école a été supprimer avec succès !');
            // Redirection après la suppression
            return $this->redirectToRoute('app_ecole');
        }

        


        // Rediriger vers une page d'erreur ou une autre action appropriée
        throw $this->createNotFoundException('L\'école demandée n\'existe pas ou vous n\'avez pas l\'autorisation de la supprimer.');
    }



    

}
