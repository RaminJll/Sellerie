<?php

namespace App\Entity;

use App\Enum\NotificationType;
use App\Repository\NotificationsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationsRepository::class)]
class Notifications
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: NotificationType::class)]
    private ?NotificationType $type = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_notification = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type_produit_manquant = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $categorie = null;

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    private ?Produit $produit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?NotificationType
    {
        return $this->type;
    }

    public function setType(NotificationType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDateNotification(): ?\DateTimeInterface
    {
        return $this->date_notification;
    }

    public function setDateNotification(\DateTimeInterface $date_notification): static
    {
        $this->date_notification = $date_notification;

        return $this;
    }

    public function getTypeProduitManquant(): ?string
    {
        return $this->type_produit_manquant;
    }

    public function setTypeProduitManquant(string $type_produit_manquant): static
    {
        $this->type_produit_manquant = $type_produit_manquant;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }
}
