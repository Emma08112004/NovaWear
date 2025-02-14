<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nomProduct = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descriptionProduct = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?float $prixProduct = null;

    #[ORM\Column(length: 255)]
    private ?string $imageUrl = null;

    #[ORM\Column]
    private ?int $stockProduct = null;

    /**
     * @var Collection<int, Summary>
     */
    #[ORM\OneToMany(targetEntity: Summary::class, mappedBy: 'product')]
    private Collection $summaries;

    public function __construct()
    {
        $this->summaries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProduct(): ?string
    {
        return $this->nomProduct;
    }

    public function setNomProduct(string $nomProduct): static
    {
        $this->nomProduct = $nomProduct;
        return $this;
    }

    public function getDescriptionProduct(): ?string
    {
        return $this->descriptionProduct;
    }

    public function setDescriptionProduct(string $descriptionProduct): static
    {
        $this->descriptionProduct = $descriptionProduct;
        return $this;
    }

    public function getPrixProduct(): ?float
    {
        return $this->prixProduct;
    }

    public function setPrixProduct(float $prixProduct): static
    {
        $this->prixProduct = $prixProduct;
        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    public function getStockProduct(): ?int
    {
        return $this->stockProduct;
    }

    public function setStockProduct(int $stockProduct): static
    {
        $this->stockProduct = $stockProduct;
        return $this;
    }

    /**
     * @return Collection<int, Summary>
     */
    public function getSummaries(): Collection
    {
        return $this->summaries;
    }

    public function addSummary(Summary $summary): static
    {
        if (!$this->summaries->contains($summary)) {
            $this->summaries->add($summary);
            $summary->setProduct($this);
        }
        return $this;
    }

    public function removeSummary(Summary $summary): static
    {
        if ($this->summaries->removeElement($summary)) {
            if ($summary->getProduct() === $this) {
                $summary->setProduct(null);
            }
        }
        return $this;
    }
}
