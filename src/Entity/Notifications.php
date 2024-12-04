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

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    #[ORM\Column(enumType: NotificationType::class)]
    private ?NotificationType $type = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_notification = null;

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
}
