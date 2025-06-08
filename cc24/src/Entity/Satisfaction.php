<?php

namespace App\Entity;

use App\Repository\SatisfactionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SatisfactionRepository::class)]
class Satisfaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $note = null;

    #[ORM\ManyToOne(inversedBy: 'satisfactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $apprenti = null;

    #[ORM\ManyToOne(inversedBy: 'satisfactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Atelier $atelier = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getApprenti(): ?User
    {
        return $this->apprenti;
    }

    public function setApprenti(?User $apprenti): static
    {
        $this->apprenti = $apprenti;

        return $this;
    }

    public function getAtelier(): ?Atelier
    {
        return $this->atelier;
    }

    public function setAtelier(?Atelier $atelier): static
    {
        $this->atelier = $atelier;

        return $this;
    }
}
