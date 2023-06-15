<?php

namespace App\Entity;

use App\Entity\Ecole;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;



#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(length: 255)]
    private ?string $motDePasse = null;

    #[ORM\ManyToMany(targetEntity: Ecole::class, inversedBy: 'users')]
    private Collection $ecoles;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private array $roles = [];

    public function __construct()
    {
        $this->ecoles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): self
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }

    /**
     * @return Collection<int, Ecole>
     */
    public function getEcoles(): Collection
    {
        return $this->ecoles;
    }

    public function addEcole(Ecole $ecole): self
    {
        if (!$this->ecoles->contains($ecole)) {
            $this->ecoles->add($ecole);
        }

        return $this;
    }

    public function removeEcole(Ecole $ecole): self
    {
        $this->ecoles->removeElement($ecole);

        return $this;
    }


    public function eraseCredentials()
    {
        // Supprimez les données sensibles de l'utilisateur.
        // Par exemple, si vous stockez un mot de passe en texte brut, cette méthode doit supprimer le mot de passe.
    }

    public function getUserIdentifier(): string
    {
        // Logique pour obtenir l'identifiant unique de l'utilisateur
        return $this->email; // Exemple : si votre utilisateur a un champ "email"
    }

    public function getPassword(): string
    {
        return $this->motDePasse;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles ?? []; // Initialise $roles à un tableau vide s'il est nul
        // garantit que chaque utilisateur a au moins ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
}
