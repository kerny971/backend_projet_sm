<?php

namespace App\Entity;

use App\Repository\MeetingRoomRepository;
use App\Validator\SlugConstraint;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: MeetingRoomRepository::class)]
#[UniqueEntity('slug')]
class MeetingRoom
{
    #[ORM\Id]
    #[ORM\Column(type: Types::GUID, unique: true)]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[SlugConstraint]
    private ?string $slug = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToOne(mappedBy: 'meetingRoom', cascade: ['persist', 'remove'])]
    private ?MeetingOrder $meetingOrder = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

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

    public function getMeetingOrder(): ?MeetingOrder
    {
        return $this->meetingOrder;
    }

    public function setMeetingOrder(?MeetingOrder $meetingOrder): static
    {
        // unset the owning side of the relation if necessary
        if ($meetingOrder === null && $this->meetingOrder !== null) {
            $this->meetingOrder->setMeetingRoom(null);
        }

        // set the owning side of the relation if necessary
        if ($meetingOrder !== null && $meetingOrder->getMeetingRoom() !== $this) {
            $meetingOrder->setMeetingRoom($this);
        }

        $this->meetingOrder = $meetingOrder;

        return $this;
    }
}
