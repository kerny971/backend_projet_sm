<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\Column(type: Types::GUID, unique: true)]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $cost = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isPayed = null;

    #[ORM\Column(nullable: true)]
    private ?int $feeTaxesCost = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $stripeReference = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    private ?MeetingOrder $meetingOrder = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCost(): ?int
    {
        return $this->cost;
    }

    public function setCost(int $cost): static
    {
        $this->cost = $cost;

        return $this;
    }

    public function isPayed(): ?bool
    {
        return $this->isPayed;
    }

    public function setPayed(bool $isPayed): static
    {
        $this->isPayed = $isPayed;

        return $this;
    }

    public function getFeeTaxesCost(): ?int
    {
        return $this->feeTaxesCost;
    }

    public function setFeeTaxesCost(?int $feeTaxesCost): static
    {
        $this->feeTaxesCost = $feeTaxesCost;

        return $this;
    }

    public function getStripeReference(): ?string
    {
        return $this->stripeReference;
    }

    public function setStripeReference(?string $stripeReference): static
    {
        $this->stripeReference = $stripeReference;

        return $this;
    }

    public function getMeetingOrder(): ?MeetingOrder
    {
        return $this->meetingOrder;
    }

    public function setMeetingOrder(?MeetingOrder $meetingOrder): static
    {
        $this->meetingOrder = $meetingOrder;

        return $this;
    }
}
