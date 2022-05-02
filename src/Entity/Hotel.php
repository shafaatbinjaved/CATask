<?php

namespace App\Entity;

use App\Repository\HotelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(
    name: "hotel"
), ORM\Entity(
    repositoryClass: HotelRepository::class
)]
class Hotel
{
    #[ORM\Id(
    ), ORM\GeneratedValue(
    ), ORM\Column(
        type: "integer"
    )]
    private int $id;

    #[ORM\Column(
        type: "string",
        nullable: false
    )]
    private string $name;

    #[ORM\OneToMany(
        mappedBy: "hotel",
        targetEntity: Review::class
    )]
    private Collection $reviews;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): void
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setHotel($this);
        }
    }

    public function removeReview(Review $review): void
    {
        if ($this->reviews->contains($review)) {
            $this->reviews->removeElement($review);
        }
    }
}
