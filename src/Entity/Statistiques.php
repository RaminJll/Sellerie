<?php

namespace App\Entity;

use App\Repository\StatistiquesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatistiquesRepository::class)]
class Statistiques
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'statistiques')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    #[ORM\ManyToOne(inversedBy: 'statistiques')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?int $nombre_prets = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $duree_utilise = null;

    #[ORM\Column]
    private ?int $retard = null;

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

    public function getIdUser(): ?user
    {
        return $this->user;
    }

    public function setIdUser(?user $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getNombrePrets(): ?int
    {
        return $this->nombre_prets;
    }

    public function setNombrePrets(int $nombre_prets): static
    {
        $this->nombre_prets = $nombre_prets;

        return $this;
    }

    public function getDureeUtilise(): ?\DateTimeInterface
    {
        return $this->duree_utilise;
    }

    public function setDureeUtilise(\DateTimeInterface $duree_utilise): static
    {
        $this->duree_utilise = $duree_utilise;

        return $this;
    }

    public function getRetard(): ?int
    {
        return $this->retard;
    }

    public function setRetard(int $retard): static
    {
        $this->retard = $retard;

        return $this;
    }
}
