<?php

namespace App\Entity;

use App\Repository\ProductItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductItemRepository::class)]
class ProductItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull()]
    #[Groups(['api:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull()]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull()]
    private ?string $category = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull()]
    private ?string $internationalReference = null;

    #[Assert\GreaterThan(0)]
    #[Groups(['api:read'])]
    private ?int $quantityInStock = null;

    #[ORM\Column]
    private ?int $identifier = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getInternationalReference(): ?string
    {
        return $this->internationalReference;
    }

    public function setInternationalReference(string $internationalReference): static
    {
        $this->internationalReference = $internationalReference;

        return $this;
    }

    public function getQuantityInStock(): ?int
    {
        return $this->quantityInStock;
    }

    public function setQuantityInStock(int $quantityInStock): static
    {
        $this->quantityInStock = $quantityInStock;

        return $this;
    }

    public function getIdentifier(): ?int
    {
        return $this->identifier;
    }

    public function setIdentifier(int $identifier): static
    {
        $this->identifier = $identifier;

        return $this;
    }
}
