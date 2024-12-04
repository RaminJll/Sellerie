<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\UserRole;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: 'string', enumType: UserRole::class)]
    private UserRole $role;

    #[ORM\Column(nullable: true)]
    private ?int $nombre_prets = null;

    /**
     * @var Collection<int, Historiques>
     */
    #[ORM\OneToMany(targetEntity: Historiques::class, mappedBy: 'id_user')]
    private Collection $historiques;

    /**
     * @var Collection<int, Statistiques>
     */
    #[ORM\OneToMany(targetEntity: Statistiques::class, mappedBy: 'id_user')]
    private Collection $statistiques;

    public function __construct() {
        $this->role = UserRole::USER;
        $this->historiques = new ArrayCollection();
        $this->statistiques = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): UserRole
    {
        return $this->role;
    }

    public function setRole(UserRole $role): static
    {
        $this->role = $role;
        return $this;
    }

    public function getNombrePrets(): ?int
    {
        return $this->nombre_prets;
    }

    public function setNombrePrets(?int $nombre_prets): static
    {
        $this->nombre_prets = $nombre_prets;

        return $this;
    }

    public function getRoles(): array
    {
        return [$this->role->value];
    }

    public function getUserIdentifier(): string
    {
        return $this->email; 
    }

    public function eraseCredentials(): void
    {
       
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
            $historique->setIdUser($this);
        }

        return $this;
    }

    public function removeHistorique(Historiques $historique): static
    {
        if ($this->historiques->removeElement($historique)) {
            // set the owning side to null (unless already changed)
            if ($historique->getIdUser() === $this) {
                $historique->setIdUser(null);
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
            $statistique->setIdUser($this);
        }

        return $this;
    }

    public function removeStatistique(Statistiques $statistique): static
    {
        if ($this->statistiques->removeElement($statistique)) {
            // set the owning side to null (unless already changed)
            if ($statistique->getIdUser() === $this) {
                $statistique->setIdUser(null);
            }
        }

        return $this;
    }
}
