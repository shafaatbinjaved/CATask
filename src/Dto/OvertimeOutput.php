<?php

namespace App\Dto;

class OvertimeOutput
{
    private int $reviewCount;

    private int $averageScore;

    private string $dateGroup;

    public function getReviewCount(): int
    {
        return $this->reviewCount;
    }

    public function setReviewCount(int $reviewCount): void
    {
        $this->reviewCount = $reviewCount;
    }

    public function getAverageScore(): int
    {
        return $this->averageScore;
    }

    public function setAverageScore(int $averageScore): void
    {
        $this->averageScore = $averageScore;
    }

    public function getDateGroup(): string
    {
        return $this->dateGroup;
    }

    public function setDateGroup(string $dateGroup): void
    {
        $this->dateGroup = $dateGroup;
    }
}
