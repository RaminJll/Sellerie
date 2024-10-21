<?php

namespace App\Entity;

use App\Enum\ProduitCategorie;
use App\Enum\ProduitEtat;
use App\Repository\ProduitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(enumType: ProduitCategorie::class)]
    private ?ProduitCategorie $categorie = null;

    #[ORM\Column(length: 255)]
    private ?string $type_produit = null;

    #[ORM\Column(enumType: ProduitEtat::class)]
    private ?ProduitEtat $etat = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_achat = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $planning = null;

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

    public function getCategorie(): ?ProduitCategorie
    {
        return $this->categorie;
    }

    public function setCategorie(ProduitCategorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getTypeProduit(): ?string
    {
        return $this->type_produit;
    }

    public function setTypeProduit(string $type_produit): static
    {
        $this->type_produit = $type_produit;

        return $this;
    }

    public function getEtat(): ?ProduitEtat
    {
        return $this->etat;
    }

    public function setEtat(ProduitEtat $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDateAchat(): ?\DateTimeInterface
    {
        return $this->date_achat;
    }

    public function setDateAchat(\DateTimeInterface $date_achat): static
    {
        $this->date_achat = $date_achat;

        return $this;
    }

    public function getPlanning(): ?\DateTimeInterface
    {
        return $this->planning;
    }

    public function setPlanning(\DateTimeInterface $planning): static
    {
        $this->planning = $planning;

        return $this;
    }
}
