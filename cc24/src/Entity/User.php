<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    /**
     * @var Collection<int, Atelier>
     */
    #[ORM\OneToMany(targetEntity: Atelier::class, mappedBy: 'instructeur')]
    private Collection $ateliers;

    /**
     * @var Collection<int, Atelier>
     */
    #[ORM\ManyToMany(targetEntity: Atelier::class, mappedBy: 'apprentis')]
    private Collection $ateliersInscrits;

    /**
     * @var Collection<int, Satisfaction>
     */
    #[ORM\OneToMany(targetEntity: Satisfaction::class, mappedBy: 'apprenti')]
    private Collection $satisfactions;

    public function __construct()
    {
        $this->ateliers = new ArrayCollection();
        $this->ateliersInscrits = new ArrayCollection();
        $this->satisfactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;


        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * @return Collection<int, Atelier>
     */
    public function getAteliers(): Collection
    {
        return $this->ateliers;
    }

    public function addAtelier(Atelier $atelier): static
    {
        if (!$this->ateliers->contains($atelier)) {
            $this->ateliers->add($atelier);
            $atelier->setInstructeur($this);
        }

        return $this;
    }

    public function removeAtelier(Atelier $atelier): static
    {
        if ($this->ateliers->removeElement($atelier)) {
            // set the owning side to null (unless already changed)
            if ($atelier->getInstructeur() === $this) {
                $atelier->setInstructeur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Atelier>
     */
    public function getAteliersInscrits(): Collection
    {
        return $this->ateliersInscrits;
    }

    public function addAteliersInscrit(Atelier $ateliersInscrit): static
    {
        if (!$this->ateliersInscrits->contains($ateliersInscrit)) {
            $this->ateliersInscrits->add($ateliersInscrit);
            $ateliersInscrit->addApprenti($this);
        }

        return $this;
    }

    public function removeAteliersInscrit(Atelier $ateliersInscrit): static
    {
        if ($this->ateliersInscrits->removeElement($ateliersInscrit)) {
            $ateliersInscrit->removeApprenti($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Satisfaction>
     */
    public function getSatisfactions(): Collection
    {
        return $this->satisfactions;
    }

    public function addSatisfaction(Satisfaction $satisfaction): static
    {
        if (!$this->satisfactions->contains($satisfaction)) {
            $this->satisfactions->add($satisfaction);
            $satisfaction->setApprenti($this);
        }

        return $this;
    }

    public function removeSatisfaction(Satisfaction $satisfaction): static
    {
        if ($this->satisfactions->removeElement($satisfaction)) {
            // set the owning side to null (unless already changed)
            if ($satisfaction->getApprenti() === $this) {
                $satisfaction->setApprenti(null);
            }
        }

        return $this;
    }
}
