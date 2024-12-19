<?php

namespace App\Entity;

use App\Enum\ProduitEtat;
use App\Repository\ReparationsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReparationsRepository::class)]
class Reparations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reparations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    #[ORM\Column(length: 255)]
    private ?string $description_probleme = null;

    #[ORM\Column]
    private ?float $cout_reparation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_fin_reparation = null;

    #[ORM\Column(enumType: ProduitEtat::class)]
    private ?ProduitEtat $etat_init = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setIdProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }

    public function getDescriptionProbleme(): ?string
    {
        return $this->description_probleme;
    }

    public function setDescriptionProbleme(string $description_probleme): static
    {
        $this->description_probleme = $description_probleme;

        return $this;
    }

    public function getCoutReparation(): ?float
    {
        return $this->cout_reparation;
    }

    public function setCoutReparation(float $cout_reparation): static
    {
        $this->cout_reparation = $cout_reparation;

        return $this;
    }

    public function getDateFinReparation(): ?\DateTimeInterface
    {
        return $this->date_fin_reparation;
    }

    public function setDateFinReparation(\DateTimeInterface $date_fin_reparation): static
    {
        $this->date_fin_reparation = $date_fin_reparation;

        return $this;
    }

    public function getEtatInit(): ?ProduitEtat
    {
        return $this->etat_init;
    }

    public function setEtatInit(ProduitEtat $etat_init): static
    {
        $this->etat_init = $etat_init;

        return $this;
    }
}
