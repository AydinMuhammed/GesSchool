<?php

namespace App\Controller;

use App\Entity\Eleve;
use App\Entity\Classe;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Ecole;

class EleveController extends AbstractController
{
    // pour récuprer les eleves d'une classe
    public function index(Classe $classe): Response
    {
        $eleves = $classe->getEleves();

        return $this->render('ecole/index.html.twig', [
            
            'classe' => $classe,
            'eleves' => $eleves,
        ]);
    }



     // voir les infos de l'élève   
    #[Route("/eleve/{id}", name:"app_eleve_show")]
    public function show(Eleve $eleve): Response
    {
        return $this->render('eleve/show_ecole.html.twig', [
            'eleve' => $eleve,
        ]);
    }
}
