<?php

namespace App\Entity;

use App\Repository\DeliveryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeliveryRepository::class)]
class Delivery
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deliveredAt = null;

    #[ORM\Column(length: 50)]
    private ?string $delivery_number = null;

    #[ORM\Column]
    private ?float $distance = null;

    #[ORM\Column(length: 50)]
    private ?string $dayTime = null;

    #[ORM\Column(length: 50)]
    private ?string $weekTime = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $deliveryExpected = null;

    #[ORM\Column]
    private ?bool $isEmailSent = false;

    #[ORM\ManyToOne]
    private ?Order $orders = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeliveredAt(): ?\DateTimeImmutable
    {
        return $this->deliveredAt;
    }

    public function setDeliveredAt(\DateTimeImmutable $deliveredAt): static
    {
        $this->deliveredAt = $deliveredAt;

        return $this;
    }

    public function getDeliveryNumber(): ?string
    {
        return $this->delivery_number;
    }

    public function setDeliveryNumber(string $delivery_number): static
    {
        $this->delivery_number = $delivery_number;

        return $this;
    }

    public function getDistance(): ?float
    {
        return $this->distance;
    }

    public function setDistance(float $distance): static
    {
        $this->distance = $distance;

        return $this;
    }

    public function getDayTime(): ?string
    {
        return $this->dayTime;
    }

    public function setDayTime(string $dayTime): static
    {
        $this->dayTime = $dayTime;

        return $this;
    }

    public function getWeekTime(): ?string
    {
        return $this->weekTime;
    }

    public function setWeekTime(string $weekTime): static
    {
        $this->weekTime = $weekTime;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDeliveryExpected(): ?\DateTimeImmutable
    {
        return $this->deliveryExpected;
    }

    public function setDeliveryExpected(\DateTimeImmutable $deliveryExpected): static
    {
        $this->deliveryExpected = $deliveryExpected;

        return $this;
    }

    public function isIsEmailSent(): ?bool
    {
        return $this->isEmailSent;
    }

    public function setIsEmailSent(bool $isEmailSent): static
    {
        $this->isEmailSent = $isEmailSent;

        return $this;
    }

    public function getOrders(): ?Order
    {
        return $this->orders;
    }

    public function setOrders(?Order $orders): static
    {
        $this->orders = $orders;

        return $this;
    }
}
