<?php

namespace App\Entity;

use App\Repository\MeetingOrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeetingOrderRepository::class)]
class MeetingOrder
{
    #[ORM\Id]
    #[ORM\Column(type: Types::GUID, unique: true)]
    private ?string $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $startedAt = null;

    #[ORM\ManyToOne(inversedBy: 'meetingOrders')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'meetingOrders')]
    private ?MeetingOffer $meetingOffer = null;

    #[ORM\OneToOne(inversedBy: 'meetingOrder', cascade: ['persist', 'remove'])]
    private ?MeetingRoom $meetingRoom = null;

    #[ORM\Column(nullable: true)]
    private ?int $totalOrder = null;

    /**
     * @var Collection<int, Payment>
     */
    #[ORM\OneToMany(targetEntity: Payment::class, mappedBy: 'meetingOrder')]
    private Collection $payments;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
    }

    public function getId(): ?string
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


    public function getStartedAt(): ?\DateTimeInterface
    {
        return $this->startedAt;
    }

    public function setStartedAt(?\DateTimeInterface $startedAt): static
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getMeetingOffer(): ?MeetingOffer
    {
        return $this->meetingOffer;
    }

    public function setMeetingOffer(?MeetingOffer $meetingOffer): static
    {
        $this->meetingOffer = $meetingOffer;

        return $this;
    }

    public function getMeetingRoom(): ?MeetingRoom
    {
        return $this->meetingRoom;
    }

    public function setMeetingRoom(?MeetingRoom $meetingRoom): static
    {
        $this->meetingRoom = $meetingRoom;

        return $this;
    }

    public function getTotalOrder(): ?int
    {
        return $this->totalOrder;
    }

    public function setTotalOrder(?int $totalOrder): static
    {
        $this->totalOrder = $totalOrder;

        return $this;
    }

    /**
     * @return Collection<int, Payment>
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): static
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->setMeetingOrder($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): static
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getMeetingOrder() === $this) {
                $payment->setMeetingOrder(null);
            }
        }

        return $this;
    }
}
