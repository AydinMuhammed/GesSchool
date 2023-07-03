<?php

namespace App\Controller;

use App\Entity\Ecole;
use App\Entity\Classe;
use App\Form\ClasseType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;




class ClasseController extends AbstractController
{
    // pour récuprer les classes d'une école
    public function index(Ecole $ecole): Response
    {
        $classes = $ecole->getClasses();

        return $this->render('ecole/index.html.twig', [
            'ecole' => $ecole,
            'classes' => $classes,
        ]);
    }

    //pour récuprer les info d'une classe secltionné    
    #[Route("/classe/{id}", name:"app_classe_show")]
    public function show(Classe $classe): Response
    {
        return $this->render('classe/show_classe.html.twig', [
            'classe' => $classe,
        ]);
    }


        // fonction pour modifier les information d'une classe
        #[Route('/classe/{id}/edit', name: 'app_classe_edit')]
        public function edit(Request $request, Classe $classe, ManagerRegistry $doctrine): Response
        {
            $form = $this->createFormBuilder($classe)
                ->add('nom_classe', TextType::class, [
                    'label' => 'Nom de la classe'
                ])
                ->add('section_classe', TextType::class, [
                    'label' => 'Section de la classe'
                ])
                
                ->getForm();
    
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $doctrine->getManager()->flush();
    
                $this->addFlash('success', 'La classe a été modifiée avec succès');
    
                return $this->redirectToRoute('app_classe_show', ['id' => $classe->getId()]);
            }
    
            return $this->render('classe/edit_show_classe.html.twig', [
                'form' => $form->createView(),
                'classe' => $classe,
            ]);
        }


 

        private $managerRegistry;

        public function __construct(ManagerRegistry $managerRegistry)
        {
            $this->managerRegistry = $managerRegistry;
        }
    
        // ...
    
        #[Route('/ecole/{id}/addClasse', name: 'add_classe')]
        public function addClasse(Request $request, $id): Response
        {
            $entityManager = $this->managerRegistry->getManager();
    
            // Récupérer l'école à partir de l'ID
            $ecole = $entityManager->getRepository(Ecole::class)->find($id);
    
            // Vérifier si l'école existe
            if (!$ecole) {
                throw $this->createNotFoundException('L\'école n\'existe pas.');
            }
    
            $classe = new Classe();
            $classe->setEcole($ecole);
    
            $form = $this->createForm(ClasseType::class, $classe);
    
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // Effectuer les opérations de sauvegarde de la classe et de liaison avec l'école
                $entityManager->persist($classe);
                $entityManager->flush();
    
                // Rediriger vers la page de détails de l'école après avoir ajouté la classe
                return $this->redirectToRoute('app_ecole_show', ['id' => $ecole->getId()]);
            }
    
            return $this->render('classe/add_classe.html.twig', [
                'form' => $form->createView(),
                'ecole' => $ecole,
            ]);
        }
    
    






        #[Route('/classe/{id}/supprimer', name: 'app_classe_delete')]
        public function delete(EntityManagerInterface $entityManager, Classe $classe): Response
        {
            // Vérifier si l'utilisateur est connecté et a les autorisations nécessaires

            // Supprimer les élèves liés à la classe
            foreach ($classe->getEleves() as $eleve) {
                $classe->removeEleve($eleve);
                $entityManager->remove($eleve);
            }

            // Supprimer la classe
            $entityManager->remove($classe);
            $entityManager->flush();

            // Rediriger vers une autre page après la suppression de la classe
            return $this->redirectToRoute('app_ecole_show', ['id' => $classe->getEcole()->getId()]);
        }
}
