<?php

namespace App\Controller;

use App\Form\EcoleType;
use Doctrine;
use App\Entity\Ecole;
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

        $ecoles = $doctrine->getRepository(Ecole::class)->findBy([]);

        return $this->render('ecole/index.html.twig', [
            'ecoles' => $ecoles,
        ]);
    }

    

    //fonction pour AFFICHER les details d'une ecole selectionné
    #[Route('/ecole/{id}', name: 'app_ecole_show')]
    public function show(Ecole $ecole, EntityManagerInterface $entityManager): Response
    {
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
    

    // fonctionj pour AJOUTER  une école 
    #[Route('/addEcole', name: 'ecole_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ecole = new Ecole();
        $form = $this->createForm(EcoleType::class, $ecole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ecole);
            $entityManager->flush();

            return $this->redirectToRoute('app_ecole');
        }
        $this->addFlash('success', 'L\'école a été ajoutée avec succès !');

        return $this->render('ecole/add_ecole.html.twig', [
            'ecole' => $ecole,
            'form' => $form->createView(),
        ]);
    }
    

 
    // fonction pour MODIFIER les information d'une ecole
    #[Route('/ecole/{id}/edit', name: 'app_ecole_edit')]
    public function edit(Request $request, Ecole $ecole, ManagerRegistry $doctrine): Response
    {
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
            $doctrine->getManager()->flush();

            $this->addFlash('success', 'L\'école a été modifiée avec succès');

            return $this->redirectToRoute('app_ecole_show', ['id' => $ecole->getId()]);
        }

        return $this->render('ecole/edit_show_ecole.html.twig', [
            'form' => $form->createView(),
            'ecole' => $ecole,
        ]);
    }


    // fonction pour SUPPRIMER une école dedié 
    #[Route('/ecole/{id}/supprimer', name: 'app_ecole_delete')]
    public function delete(ManagerRegistry $doctrine, Ecole $ecole): Response
    {
       

        $entityManager = $doctrine->getManager();
        $entityManager->remove($ecole);
        $entityManager->flush();

         //redirect vers le duer
         return $this->redirectToRoute('app_ecole');
    }


    

}
