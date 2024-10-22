<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Validator\NameConstraint;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\PasswordConstraint;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity('pseudo', message: 'Ce pseudo n\'est pas disponible')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: Types::GUID, unique: true)]
    #[Assert\NotBlank()]
    #[Groups(['user.read'])]
    private ?string $id = null;

    #[Assert\Email(message: 'Cette adresse mail n\'est pas valide !')]
    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['user.create','user.read'])]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[Assert\NotBlank(message: "Veuillez entrer un mot de passe")]
    #[Assert\NotCompromisedPassword(message: 'Ce mot de passe semble pas très sure ! Veuillez utiliser un autre')]
    #[PasswordConstraint]
    #[ORM\Column]
    #[Groups(['user.create'])]
    private ?string $password = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['user.create', 'user.read'])]
    #[Assert\Length(min: 1, max: 50, minMessage: '1 caractère minimum', maxMessage: '50 charactères Maximum')]
    #[Assert\NoSuspiciousCharacters]
    private ?string $pseudo = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message: "Veuillez entrer votre Prénom")]
    #[NameConstraint]
    #[Groups(['user.create', 'user.read'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message: "Veuillez entrer votre Nom")]
    #[NameConstraint]
    #[Groups(['user.create', 'user.read'])]
    private ?string $lastname = null;

    #[ORM\Column]
    #[Groups(['user.read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['user.read'])]
    private ?bool $isConfirmed = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['user.read'])]
    private ?bool $isBanned = null;

    /**
     * @var Collection<int, MeetingOffer>
     */
    #[ORM\OneToMany(targetEntity: MeetingOffer::class, mappedBy: 'user')]
    private Collection $meetingOffers;

    /**
     * @var Collection<int, MeetingOrder>
     */
    #[ORM\OneToMany(targetEntity: MeetingOrder::class, mappedBy: 'user')]
    private Collection $meetingOrders;


    public function __construct()
    {
        $this->meetingOffers = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

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

    public function isConfirmed(): ?bool
    {
        return $this->isConfirmed;
    }

    public function setConfirmed(?bool $isConfirmed): static
    {
        $this->isConfirmed = $isConfirmed;

        return $this;
    }

    public function isBanned(): ?bool
    {
        return $this->isBanned;
    }

    public function setBanned(?bool $isBanned): static
    {
        $this->isBanned = $isBanned;

        return $this;
    }

    /**
     * @return Collection<int, MeetingOffer>
     */
    public function getMeetingOffers(): Collection
    {
        return $this->meetingOffers;
    }

    public function addMeetingOffer(MeetingOffer $meetingOffer): static
    {
        if (!$this->meetingOffers->contains($meetingOffer)) {
            $this->meetingOffers->add($meetingOffer);
            $meetingOffer->setUser($this);
        }

        return $this;
    }

    public function removeMeetingOffer(MeetingOffer $meetingOffer): static
    {
        if ($this->meetingOffers->removeElement($meetingOffer)) {
            // set the owning side to null (unless already changed)
            if ($meetingOffer->getUser() === $this) {
                $meetingOffer->setUser(null);
            }
        }

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
            $meetingOrder->setUser($this);
        }

        return $this;
    }

    public function removeMeetingOrder(MeetingOrder $meetingOrder): static
    {
        if ($this->meetingOrders->removeElement($meetingOrder)) {
            // set the owning side to null (unless already changed)
            if ($meetingOrder->getUser() === $this) {
                $meetingOrder->setUser(null);
            }
        }

        return $this;
    }

}
