<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;
    public function getProduct(): ?Product
    {
    return $this->product;
    }

    public function setProduct(?Product $product): self
    {
    $this->product = $product;
    return $this;
    }

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateOrder = null;

    #[ORM\Column(length: 50)]
    private ?string $statut = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $total = null;

    #[ORM\OneToOne(targetEntity: Payment::class, mappedBy: "order", cascade: ["persist", "remove"])]
    private ?Payment $payment = null;

    #[ORM\OneToMany(targetEntity: Summary::class, mappedBy: "order", cascade: ["persist", "remove"])]
    private Collection $summaries;

    public function __construct()
    {
        $this->summaries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDateOrder(): ?\DateTimeInterface
    {
        return $this->dateOrder;
    }

    public function setDateOrder(\DateTimeInterface $dateOrder): static
    {
        $this->dateOrder = $dateOrder;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): static
    {
        $this->payment = $payment;
        return $this;
    }

    public function getSummaries(): Collection
    {
        return $this->summaries;
    }

    public function addSummary(Summary $summary): static
    {
        if (!$this->summaries->contains($summary)) {
            $this->summaries->add($summary);
            $summary->setOrder($this);
        }
        return $this;
    }

    public function removeSummary(Summary $summary): static
    {
        if ($this->summaries->removeElement($summary)) {
            if ($summary->getOrder() === $this) {
                $summary->setOrder(null);
            }
        }
        return $this;
    }
}
