<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $prenom = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $motDePasse = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $telephone = null;

    #[ORM\OneToMany(targetEntity: order::class, mappedBy: 'user', cascade: ["persist", "remove"])]
    private Collection $orders;

    #[ORM\OneToMany(targetEntity: Basket::class, mappedBy: "user", cascade: ["persist", "remove"])]
    private Collection $baskets;

    #[ORM\OneToMany(targetEntity: Favorites::class, mappedBy: 'user', cascade: ["persist", "remove"])]
    private Collection $favorites;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->Baskets = new ArrayCollection();
        $this->favorites = new ArrayCollection();
    }

    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Favorites $favorite): static
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites->add($favorite);
            $favorite->setUser($this);
        }
        return $this;
    }

    public function removeFavorite(Favorites $favorite): static
    {
        if ($this->favorites->removeElement($favorite)) {
            if ($favorite->getUser() === $this) {
                $favorite->setUser(null);
            }
        }
        return $this;
    }
}
