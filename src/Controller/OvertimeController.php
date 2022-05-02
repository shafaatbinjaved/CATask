<?php

namespace App\Controller;

use App\Dto\OvertimeOutput;
use App\Repository\HotelRepository;
use App\Repository\ReviewRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OvertimeController extends AbstractController
{
    #[Route(
        path: "/time/{hotel_id}/{start_date}/{end_date}",
        name: "over_time",
        methods: [Request::METHOD_GET]
    )]
    public function list(
        Request $request,
        HotelRepository $hotelRepository,
        ReviewRepository $reviewRepository
    ): JsonResponse
    {
        $routeParams = $request->attributes->get('_route_params');
        $hotelId = (int) $routeParams['hotel_id'];
        $startDate = DateTime::createFromFormat('Y-m-d', $routeParams['start_date']);
        $endDate = DateTime::createFromFormat('Y-m-d', $routeParams['end_date']);

        $interval = '';
        $diffInDays = ($endDate->getTimestamp() - $startDate->getTimestamp()) / (24*60*60);

        if ($diffInDays <= 29) {
            $interval = 'DAY';
        } elseif ($diffInDays >= 30 && $diffInDays <= 89) {
            $interval = 'WEEK';
        } elseif ($diffInDays > 89) {
            $interval = 'MONTH';
        }

        $hotel = $hotelRepository->find($hotelId);

        $results = $reviewRepository->getReviewsByHotelGroupedByInterval(
            $hotel,
            $startDate,
            $endDate,
            $interval
        );

        return $this->json($this->populateDto($results));
    }

    private function populateDto($results): iterable
    {
        foreach ($results as $result) {
            $dto = new OvertimeOutput();
            $dto->setAverageScore($result['avgScore']);
            $dto->setReviewCount($result['count']);
            $dto->setDateGroup($result['interval']);

            yield $dto;
        }
    }
}
