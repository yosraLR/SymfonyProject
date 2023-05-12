<?php

namespace App\Entity;

use App\Repository\PrizeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrizeRepository::class)]
class Prize
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Items = null;

    #[ORM\ManyToOne(inversedBy: 'PrizeID')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Giveaways $giveaways = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getItems(): ?string
    {
        return $this->Items;
    }

    public function setItems(?string $Items): self
    {
        $this->Items = $Items;

        return $this;
    }

    public function getGiveaways(): ?Giveaways
    {
        return $this->giveaways;
    }

    public function setGiveaways(?Giveaways $giveaways): self
    {
        $this->giveaways = $giveaways;

        return $this;
    }
}
