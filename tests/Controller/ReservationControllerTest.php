<?php
namespace App\Tests\Controller;

use App\Controller\ReservationController;
use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PHPUnit\Framework\Attributes\CoversDefaultClass;
use PHPUnit\Framework\Attributes\Covers;

#[CoversDefaultClass(ReservationController::class)]
class ReservationControllerTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;
    private ReservationController $controller;
    private EntityRepository $repository;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->repository = $this->createMock(EntityRepository::class);
        
        $this->entityManager->method('getRepository')
            ->willReturn($this->repository);

        $this->controller = new ReservationController($this->entityManager);
    }

    #[Covers('create')]
    public function testCreateReservationWithValidData(): void
    {
        $jsonData = json_encode([
            "id" => 1,
            "during_stay" => 6,
            "status" => "confirmed",
            "r_cost" => 250.5,
            "check_in" => "2024-10-15",
            "check_out" => "2024-10-22",
            "reservation_date" => "2024-09-26 12:28:38"
        ]);

        $request = new Request([], [], [], [], [], [], $jsonData);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(Reservation::class));

        $this->entityManager->expects($this->once())
            ->method('flush');

        $response = $this->controller->create($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    #[Covers('update')]
    public function testUpdateReservationWithValidData(): void
    {
        $existingReservation = new Reservation();
        $existingReservation->setDuringStay(5);
        $existingReservation->setStatus("pending");
        $existingReservation->setRCost(200.0);
        $existingReservation->setCheckIn(new \DateTime("2024-10-10"));
        $existingReservation->setCheckOut(new \DateTime("2024-10-15"));
        $existingReservation->setReservationDate(new \DateTime("2024-09-20 12:28:38"));

        $this->repository->method('find')
            ->willReturn($existingReservation);

        $jsonData = json_encode([
            "during_stay" => 6,
            "status" => "confirmed",
            "r_cost" => 250.5,
            "check_in" => "2024-10-15",
            "check_out" => "2024-10-22",
            "reservation_date" => "2024-09-26 12:28:38"
        ]);

        $request = new Request([], [], [], [], [], [], $jsonData);
        
        $this->entityManager->expects($this->once())
            ->method('flush');

        $response = $this->controller->update($request, 1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
