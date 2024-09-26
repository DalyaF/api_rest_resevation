<?php
namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation{
   
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private ?int $r_id = null;

    #[ORM\Column(type: 'integer')]
    private ?int $during_stay = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $status = null;

    #[ORM\Column(type: 'float')]
    private ?float $r_cost = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $check_in = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $check_out = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $reservation_date = null;

    public function getId(): ?int {
         return $this->r_id; 
    }
    public function getDuringStay(): ?int { 
        return $this->during_stay; 
    }
    public function setDuringStay(int $during_stay): self { 
        $this->during_stay = $during_stay; return $this; 
    }
    public function getStatus(): ?string { 
        return $this->status; 
    }
    public function setStatus(string $status): self { 
        $this->status = $status; return $this; 
    }
    public function getCost(): ?float { 
        return $this->r_cost; 
    }
    public function setRCost(float $r_cost): self { 
        $this->r_cost = $r_cost; return $this; 
    }
    public function getCheckIn(): ?\DateTimeInterface { 
        return $this->check_in; 
    }
    public function setCheckIn(\DateTimeInterface $check_in): self { 
        $this->check_in = $check_in; return $this; 
    }
    public function getCheckOut(): ?\DateTimeInterface { 
        return $this->check_out; 
    }
    public function setCheckOut(\DateTimeInterface $check_out): self { 
        $this->check_out = $check_out; return $this; 
    }
    public function getReservationDate(): ?\DateTimeInterface { 
        return $this->reservation_date; 
    }
    public function setReservationDate(\DateTimeInterface $reservation_date): self { 
        $this->reservation_date = $reservation_date; return $this; 
    }
}
