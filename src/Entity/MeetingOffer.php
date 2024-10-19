<?php

namespace App\Entity;

use App\Repository\MeetingOfferRepository;
use App\Validator\SlugConstraint;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
    
#[ORM\Entity(repositoryClass: MeetingOfferRepository::class)]
#[UniqueEntity('slug')]
class MeetingOffer
{
    #[ORM\Id]
    #[ORM\Column(type: Types::GUID, unique: true)]
    #[NotBlank]
    #[Groups(['meeting_offer.read'])]
    private ?string $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['meeting_offer.create','meeting_offer.read'])]
    #[Assert\NotBlank(message: "Veuillez saisir un slug")]
    #[SlugConstraint]
    private ?string $slug = null;

    #[Groups(['meeting_offer.create','meeting_offer.read'])]
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez saisir un titre")]
    #[Assert\Length(min: 3, max: 225, minMessage: "3 caractères minimum", maxMessage: "225 caractères maximum")]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['meeting_offer.create','meeting_offer.read'])]
    #[Assert\Length(max: 65000, maxMessage: "La description est trop longue !")]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(max: 65000, maxMessage: "Le corps du contenu est trop long !")]
    #[Groups(['meeting_offer.create','meeting_offer.read'])]
    private ?string $content = null;

    #[ORM\Column(nullable: true)]
    #[Assert\LessThanOrEqual(10000)]
    #[Groups(['meeting_offer.create','meeting_offer.read'])]
    private ?int $price = null;

    #[ORM\Column]
    #[Groups(['meeting_offer.read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['meeting_offer.read'])]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'meetingOffers')]
    private ?User $user = null;

    /**
     * @var Collection<int, MeetingOrder>
     */
    #[ORM\OneToMany(targetEntity: MeetingOrder::class, mappedBy: 'meetingOffer')]
    private Collection $meetingOrders;

    #[ORM\Column(nullable: true)]
    private ?bool $isValided = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isActived = null;

    public function __construct()
    {
        $this->meetingOrders = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): static
    {
        $this->price = $price;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, MeetingOrder>
     */
    public function getMeetingOrders(): Collection
    {
        return $this->meetingOrders;
    }

    public function addMeetingOrder(MeetingOrder $meetingOrder): static
    {
        if (!$this->meetingOrders->contains($meetingOrder)) {
            $this->meetingOrders->add($meetingOrder);
            $meetingOrder->setMeetingOffer($this);
        }

        return $this;
    }

    public function removeMeetingOrder(MeetingOrder $meetingOrder): static
    {
        if ($this->meetingOrders->removeElement($meetingOrder)) {
            // set the owning side to null (unless already changed)
            if ($meetingOrder->getMeetingOffer() === $this) {
                $meetingOrder->setMeetingOffer(null);
            }
        }

        return $this;
    }

    public function isValided(): ?bool
    {
        return $this->isValided;
    }

    public function setValided(?bool $isValided): static
    {
        $this->isValided = $isValided;

        return $this;
    }

    public function isActived(): ?bool
    {
        return $this->isActived;
    }

    public function setActived(?bool $isActived): static
    {
        $this->isActived = $isActived;

        return $this;
    }
}
