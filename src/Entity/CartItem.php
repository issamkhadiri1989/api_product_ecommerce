<?php

namespace App\Entity;

use App\Repository\CartItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: CartItemRepository::class)]
class CartItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    #[Groups(['api:read'])]
    private ?int $quantity = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Assert\NotNull(), Assert\Valid]
    #[Groups(['api:read'])]
    private ?ProductItem $product = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cart $cart = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getProduct(): ?ProductItem
    {
        return $this->product;
    }

    public function setProduct(?ProductItem $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): static
    {
        $this->cart = $cart;

        return $this;
    }

    public function hasEnoughQuantityInStock(): bool
    {
        return $this->product->getQuantityInStock() > $this->quantity;
    }

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context, mixed $payload): void
    {
        $hasEnoughQuantityInStock = $this->hasEnoughQuantityInStock();

        if (!$hasEnoughQuantityInStock) {
            $context->buildViolation('There is not enough quantity to order.')
                ->atPath('quantity')
                ->addViolation();
        }
    }
}
