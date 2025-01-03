<?php

namespace App\Entity;

use App\Enum\ProduitCategorie;
use App\Enum\ProduitEtat;
use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column(length: 255)]
    private ?string $categorie = null;

    #[ORM\Column(length: 255)]
    private ?string $type_produit = null;

    #[ORM\Column(enumType: ProduitEtat::class)]
    private ?ProduitEtat $etat = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_achat = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $planning = null;

    /**
     * @var Collection<int, Historiques>
     */
    #[ORM\OneToMany(targetEntity: Historiques::class, mappedBy: 'id_produit')]
    private Collection $historiques;

    /**
     * @var Collection<int, Statistiques>
     */
    #[ORM\OneToMany(targetEntity: Statistiques::class, mappedBy: 'id_produit')]
    private Collection $statistiques;

    /**
     * @var Collection<int, Maintenances>
     */
    #[ORM\OneToMany(targetEntity: Maintenances::class, mappedBy: 'id_produit')]
    private Collection $maintenances;

    #[ORM\Column(length: 255)]
    private ?string $categorie_rayon = null;

    #[ORM\Column]
    private ?int $etagere = null;

    /**
     * @var Collection<int, Reparations>
     */
    #[ORM\OneToMany(targetEntity: Reparations::class, mappedBy: 'id_produit')]
    private Collection $reparations;

    /**
     * @var Collection<int, Notifications>
     */
    #[ORM\OneToMany(targetEntity: Notifications::class, mappedBy: 'produit')]
    private Collection $notifications;

    public function __construct()
    {
        $this->historiques = new ArrayCollection();
        $this->statistiques = new ArrayCollection();
        $this->maintenances = new ArrayCollection();
        $this->reparations = new ArrayCollection();
        $this->notifications = new ArrayCollection();
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

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
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

    /**
     * @return Collection<int, Historiques>
     */
    public function getHistoriques(): Collection
    {
        return $this->historiques;
    }

    public function addHistorique(Historiques $historique): static
    {
        if (!$this->historiques->contains($historique)) {
            $this->historiques->add($historique);
            $historique->setIdProduit($this);
        }

        return $this;
    }

    public function removeHistorique(Historiques $historique): static
    {
        if ($this->historiques->removeElement($historique)) {
            // set the owning side to null (unless already changed)
            if ($historique->getIdProduit() === $this) {
                $historique->setIdProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Statistiques>
     */
    public function getStatistiques(): Collection
    {
        return $this->statistiques;
    }

    public function addStatistique(Statistiques $statistique): static
    {
        if (!$this->statistiques->contains($statistique)) {
            $this->statistiques->add($statistique);
            $statistique->setIdProduit($this);
        }

        return $this;
    }

    public function removeStatistique(Statistiques $statistique): static
    {
        if ($this->statistiques->removeElement($statistique)) {
            // set the owning side to null (unless already changed)
            if ($statistique->getIdProduit() === $this) {
                $statistique->setIdProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Maintenances>
     */
    public function getMaintenances(): Collection
    {
        return $this->maintenances;
    }

    public function addMaintenance(Maintenances $maintenance): static
    {
        if (!$this->maintenances->contains($maintenance)) {
            $this->maintenances->add($maintenance);
            $maintenance->setIdProduit($this);
        }

        return $this;
    }

    public function removeMaintenance(Maintenances $maintenance): static
    {
        if ($this->maintenances->removeElement($maintenance)) {
            // set the owning side to null (unless already changed)
            if ($maintenance->getIdProduit() === $this) {
                $maintenance->setIdProduit(null);
            }
        }

        return $this;
    }

    public function getCategorieRayon(): ?string
    {
        return $this->categorie_rayon;
    }

    public function setCategorieRayon(string $categorie_rayon): static
    {
        $this->categorie_rayon = $categorie_rayon;

        return $this;
    }

    public function getEtagere(): ?int
    {
        return $this->etagere;
    }

    public function setEtagere(int $etagere): static
    {
        $this->etagere = $etagere;

        return $this;
    }

    /**
     * @return Collection<int, Reparations>
     */
    public function getReparations(): Collection
    {
        return $this->reparations;
    }

    public function addReparation(Reparations $reparation): static
    {
        if (!$this->reparations->contains($reparation)) {
            $this->reparations->add($reparation);
            $reparation->setIdProduit($this);
        }

        return $this;
    }

    public function removeReparation(Reparations $reparation): static
    {
        if ($this->reparations->removeElement($reparation)) {
            // set the owning side to null (unless already changed)
            if ($reparation->getIdProduit() === $this) {
                $reparation->setIdProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notifications>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notifications $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setProduit($this);
        }

        return $this;
    }

    public function removeNotification(Notifications $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getProduit() === $this) {
                $notification->setProduit(null);
            }
        }

        return $this;
    }
}
