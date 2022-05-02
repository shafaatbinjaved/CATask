<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(
    name: 'review'
), ORM\Entity(
    repositoryClass: ReviewRepository::class
)]
class Review
{
    #[ORM\Id(
    ), ORM\GeneratedValue(
    ), ORM\Column(
        type: "integer"
    )]
    private int $id;

    #[ORM\ManyToOne(
        targetEntity: Hotel::class,
        cascade: ['persist']
    ), ORM\JoinColumn(
        nullable: false
    )]
    private Hotel $hotel;

    #[ORM\Column(
        type: "integer",
        nullable: false
    )]
    private int $score;

    #[ORM\Column(
        type: "text",
        nullable: false
    )]
    private string $comment;

    #[ORM\Column(
        type: "date"
    )]
    private DateTime $createdDate;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getHotel(): Hotel
    {
        return $this->hotel;
    }

    public function setHotel(Hotel $hotel): void
    {
        if (!isset($this->hotel) || $this->hotel !== $hotel) {
            $this->hotel = $hotel;
            $hotel->addReview($this);
        }
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function setScore(int $score): void
    {
        $this->score = $score;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    public function getCreatedDate(): DateTime
    {
        return $this->createdDate;
    }

    public function setCreatedDate(DateTime $createdDate): void
    {
        $this->createdDate = $createdDate;
    }
}
