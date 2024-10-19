<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document]
class MailConfirmation
{
    #[MongoDB\Id]
    private ?string $id = null;


    #[MongoDB\Field(type: "string")]
    private ?string $code = null;

    #[MongoDB\Field(type: "timestamp")]
    private ?int $createdAt = null;

    #[MongoDB\Field(type: "timestamp")]
    private ?int $expiredAt = null;

    #[MongoDB\Field(type: "string")]
    private ?String $user;

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }
    

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getCreatedAt(): ?int
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt->getTimestamp();

        return $this;
    }

    public function getExpiredAt(): ?int
    {
        return $this->expiredAt;
    }

    public function setExpiredAt(\DateTimeImmutable $expiredAt): static
    {
        $this->expiredAt = $expiredAt->getTimestamp();

        return $this;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(?string $user): static
    {
        $this->user = $user;

        return $this;
    }
}


?>