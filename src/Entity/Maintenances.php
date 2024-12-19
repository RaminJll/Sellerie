<?php

namespace App\Entity;

use App\Enum\ProduitEtat;
use App\Repository\MaintenancesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaintenancesRepository::class)]
class Maintenances
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'maintenances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    #[ORM\Column(nullable: true)]
    private ?float $cout_maintenance = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_fin_maintenance = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $future_date_maintenance = null;

    #[ORM\Column(enumType: ProduitEtat::class)]
    private ?ProduitEtat $etat_init = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdProduit(): ?produit
    {
        return $this->produit;
    }

    public function setIdProduit(?produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }

    public function getCoutMaintenance(): ?float
    {
        return $this->cout_maintenance;
    }

    public function setCoutMaintenance(?float $cout_maintenance): static
    {
        $this->cout_maintenance = $cout_maintenance;

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

    public function getDateFinMaintenance(): ?\DateTimeInterface
    {
        return $this->date_fin_maintenance;
    }

    public function setDateFinMaintenance(\DateTimeInterface $date_fin_maintenance): static
    {
        $this->date_fin_maintenance = $date_fin_maintenance;

        return $this;
    }

    public function getFutureDateMaintenance(): ?\DateTimeInterface
    {
        return $this->future_date_maintenance;
    }

    public function setFutureDateMaintenance(\DateTimeInterface $future_date_maintenance): static
    {
        $this->future_date_maintenance = $future_date_maintenance;

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
