<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints  as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "Champs Obligatoire")]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Assert\Positive(message: "Veuillez renseigner un prix supérieur à zéro")]
    #[Assert\NotBlank(message: "Champs Obligatoire")]
    #[ORM\Column]
    private ?float $price = null;

    #[Assert\NotBlank(message: "Champs Obligatoire")]
    #[ORM\Column(length: 255)]
    private ?string $picture = null;

    public ?string $updatePicture = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $publish_date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getPublishDate(): ?\DateTimeInterface
    {
        return $this->publish_date;
    }

    public function setPublishDate(\DateTimeInterface $publish_date): static
    {
        $this->publish_date = $publish_date;

        return $this;
    }
}
