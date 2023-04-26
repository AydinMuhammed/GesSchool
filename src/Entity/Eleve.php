<?php

namespace App\Entity;

use App\Repository\EleveRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EleveRepository::class)]
class Eleve
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_eleve = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom_eleve = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse_eleve = null;

    #[ORM\Column(length: 255)]
    private ?string $ville_eleve = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_naissane_eleve = null;

    #[ORM\ManyToOne(inversedBy: 'eleves')]
    private ?Classe $classe = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEleve(): ?string
    {
        return $this->nom_eleve;
    }

    public function setNomEleve(string $nom_eleve): self
    {
        $this->nom_eleve = $nom_eleve;

        return $this;
    }

    public function getPrenomEleve(): ?string
    {
        return $this->prenom_eleve;
    }

    public function setPrenomEleve(string $prenom_eleve): self
    {
        $this->prenom_eleve = $prenom_eleve;

        return $this;
    }

    public function getAdresseEleve(): ?string
    {
        return $this->adresse_eleve;
    }

    public function setAdresseEleve(string $adresse_eleve): self
    {
        $this->adresse_eleve = $adresse_eleve;

        return $this;
    }

    public function getVilleEleve(): ?string
    {
        return $this->ville_eleve;
    }

    public function setVilleEleve(string $ville_eleve): self
    {
        $this->ville_eleve = $ville_eleve;

        return $this;
    }

    public function getDateNaissaneEleve(): ?\DateTimeInterface
    {
        return $this->date_naissane_eleve;
    }

    public function setDateNaissaneEleve(\DateTimeInterface $date_naissane_eleve): self
    {
        $this->date_naissane_eleve = $date_naissane_eleve;

        return $this;
    }

    public function getClasse(): ?Classe
    {
        return $this->classe;
    }

    public function setClasse(?Classe $classe): self
    {
        $this->classe = $classe;

        return $this;
    }


}
