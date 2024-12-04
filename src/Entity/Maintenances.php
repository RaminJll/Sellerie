<?php

namespace App\Entity;

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

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $duree_maintenance = null;

    #[ORM\Column(nullable: true)]
    private ?float $cout_maintenance = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

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

    public function getDureeMaintenance(): ?\DateTimeInterface
    {
        return $this->duree_maintenance;
    }

    public function setDureeMaintenance(?\DateTimeInterface $duree_maintenance): static
    {
        $this->duree_maintenance = $duree_maintenance;

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
}
