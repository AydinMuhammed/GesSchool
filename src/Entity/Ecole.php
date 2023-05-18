<?php

namespace App\Entity;

use App\Repository\EcoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EcoleRepository::class)]
class Ecole
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomEcole = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $telephoneEcole = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mailEcole = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresseEcole = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $villeEcole = null;

    #[ORM\OneToMany(mappedBy: 'ecole', targetEntity: Classe::class)]
    private Collection $classes;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'ecoles')]
    private Collection $users;

    public function __construct()
    {
        $this->classes = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getNomEcole(): ?string
    {
        return $this->nomEcole;
    }

    public function setNomEcole(string $nomEcole): self
    {
        $this->nomEcole = $nomEcole;

        return $this;
    }

    public function getTelephoneEcole(): ?string
    {
        return $this->telephoneEcole;
    }

    public function setTelephoneEcole(?string $telephoneEcole): self
    {
        $this->telephoneEcole = $telephoneEcole;

        return $this;
    }

    public function getMailEcole(): ?string
    {
        return $this->mailEcole;
    }

    public function setMailEcole(?string $mailEcole): self
    {
        $this->mailEcole = $mailEcole;

        return $this;
    }

    public function getAdresseEcole(): ?string
    {
        return $this->adresseEcole;
    }

    public function setAdresseEcole(?string $adresseEcole): self
    {
        $this->adresseEcole = $adresseEcole;

        return $this;
    }

    public function getVilleEcole(): ?string
    {
        return $this->villeEcole;
    }

    public function setVilleEcole(?string $villeEcole): self
    {
        $this->villeEcole = $villeEcole;

        return $this;
    }

    /**
     * @return Collection<int, Classe>
     */
    public function getClasses(): Collection
    {
        return $this->classes;
    }

    public function addClass(Classe $class): self
    {
        if (!$this->classes->contains($class)) {
            $this->classes->add($class);
            $class->setEcole($this);
        }

        return $this;
    }

    public function removeClass(Classe $class): self
    {
        if ($this->classes->removeElement($class)) {
            // set the owning side to null (unless already changed)
            if ($class->getEcole() === $this) {
                $class->setEcole(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addEcole($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeEcole($this);
        }

        return $this;
    }
}
