<?php

namespace App\Controller;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('api/reservation/create', methods: ['POST'])]
    public function create(Request $request): JsonResponse{
        $data = json_decode($request->getContent(), true);
        
        if (!isset($data['during_stay'], $data['status'], $data['r_cost'], $data['check_in'], $data['check_out'])) {
            return new JsonResponse(['error' => 'Missing required fields'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!is_int($data['during_stay']) || $data['during_stay'] <= 0) {
            return new JsonResponse(['error' => 'Invalid value for during_stay'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!is_string($data['status']) || empty($data['status'])) {
            return new JsonResponse(['error' => 'Invalid status'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!is_float($data['r_cost']) || $data['r_cost'] < 0) {
            return new JsonResponse(['error' => 'Invalid cost'], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $check_in = new \DateTime($data['check_in']);
            $check_out = new \DateTime($data['check_out']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Invalid date format'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if ($check_out <= $check_in) {
            return new JsonResponse(['error' => 'Check-out date must be after check-in date'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $reservation = new Reservation();
        $reservation->setDuringStay($data['during_stay']);
        $reservation->setStatus($data['status']);
        $reservation->setRCost($data['r_cost']);
        $reservation->setCheckIn($check_in);
        $reservation->setCheckOut($check_out);
        $reservation->setReservationDate(new \DateTime());

        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        return new JsonResponse(['id' => $reservation->getId()], JsonResponse::HTTP_CREATED);
    }

    #[Route('api/reservation/update/{id}', methods: ['PUT'])]
    public function update(Request $request, int $id): JsonResponse{
        $reservation = $this->entityManager->getRepository(Reservation::class)->find($id);
        if (!$reservation) {
            return new JsonResponse(['error' => 'Reservation not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (!isset($data['during_stay'], $data['status'], $data['r_cost'], $data['check_in'], $data['check_out'])) {
            return new JsonResponse(['error' => 'Missing required fields'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!is_int($data['during_stay']) || $data['during_stay'] <= 0) {
            return new JsonResponse(['error' => 'Invalid value for during_stay'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!is_string($data['status']) || empty($data['status'])) {
            return new JsonResponse(['error' => 'Invalid status'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!is_float($data['r_cost']) || $data['r_cost'] < 0) {
            return new JsonResponse(['error' => 'Invalid cost'], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $check_in = new \DateTime($data['check_in']);
            $check_out = new \DateTime($data['check_out']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Invalid date format'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if ($check_out <= $check_in) {
            return new JsonResponse(['error' => 'Check-out date must be after check-in date'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $reservation->setDuringStay($data['during_stay']);
        $reservation->setStatus($data['status']);
        $reservation->setRCost($data['r_cost']);
        $reservation->setCheckIn($check_in);
        $reservation->setCheckOut($check_out);
        $reservation->setReservationDate(new \DateTime());

        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Reservation updated successfully'], JsonResponse::HTTP_NO_CONTENT);
    }


    #[Route('api/reservation/delete/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse{
        $reservation = $this->entityManager->getRepository(Reservation::class)->find($id);
        if (!$reservation) {
            return new JsonResponse(null, JsonResponse::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($reservation);
        $this->entityManager->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route('api/reservations', methods: ['GET'])]
    public function display(): JsonResponse{
        $reservations = $this->entityManager->getRepository(Reservation::class)->findAll();
        $data = [];
        foreach ($reservations as $reservation) {
            $data[] = [
                'id' => $reservation->getId(),
                'during_stay' => $reservation->getDuringStay(),
                'status' => $reservation->getStatus(),
                'r_cost' => $reservation->getCost(),
                'check_in' => $reservation->getCheckIn()->format('Y-m-d'),
                'check_out' => $reservation->getCheckOut()->format('Y-m-d'),
                'reservation_date' => $reservation->getReservationDate()->format('Y-m-d H:i:s'),
            ];
        }

        return new JsonResponse($data);
    }
}
