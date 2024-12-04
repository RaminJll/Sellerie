<?php

namespace App\Entity;

use App\Repository\HistoriquesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistoriquesRepository::class)]
class Historiques
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'historiques')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    #[ORM\ManyToOne(inversedBy: 'historiques')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_empreinte = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_rendu = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $signalement = null;

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

    public function getIdUser(): ?User
    {
        return $this->user;
    }

    public function setIdUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDateEmpreinte(): ?\DateTimeInterface
    {
        return $this->date_empreinte;
    }

    public function setDateEmpreinte(\DateTimeInterface $date_empreinte): static
    {
        $this->date_empreinte = $date_empreinte;

        return $this;
    }

    public function getDateRendu(): ?\DateTimeInterface
    {
        return $this->date_rendu;
    }

    public function setDateRendu(\DateTimeInterface $date_rendu): static
    {
        $this->date_rendu = $date_rendu;

        return $this;
    }

    public function getSignalement(): ?string
    {
        return $this->signalement;
    }

    public function setSignalement(?string $signalement): static
    {
        $this->signalement = $signalement;

        return $this;
    }
}
