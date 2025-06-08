<?php

namespace App\Entity;

use App\Repository\AtelierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AtelierRepository::class)]
class Atelier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'ateliers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $instructeur = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'ateliersInscrits')]
    private Collection $apprentis;

    /**
     * @var Collection<int, Satisfaction>
     */
    #[ORM\OneToMany(targetEntity: Satisfaction::class, mappedBy: 'atelier')]
    private Collection $satisfactions;

    public function __construct()
    {
        $this->apprentis = new ArrayCollection();
        $this->satisfactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getInstructeur(): ?User
    {
        return $this->instructeur;
    }

    public function setInstructeur(?User $instructeur): static
    {
        $this->instructeur = $instructeur;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getApprentis(): Collection
    {
        return $this->apprentis;
    }

    public function addApprenti(User $apprenti): static
    {
        if (!$this->apprentis->contains($apprenti)) {
            $this->apprentis->add($apprenti);
        }

        return $this;
    }

    public function removeApprenti(User $apprenti): static
    {
        $this->apprentis->removeElement($apprenti);

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
            $satisfaction->setAtelier($this);
        }

        return $this;
    }

    public function removeSatisfaction(Satisfaction $satisfaction): static
    {
        if ($this->satisfactions->removeElement($satisfaction)) {
            // set the owning side to null (unless already changed)
            if ($satisfaction->getAtelier() === $this) {
                $satisfaction->setAtelier(null);
            }
        }

        return $this;
    }
}
