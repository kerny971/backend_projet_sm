<?php

namespace App\Document;

use Symfony\Component\Serializer\Annotation\Groups;

class Response
{
    private ?int $statusCode = null;

    private ?string $status = null;

    private ?string $errorCode = null;

    private ?string $message = null;

    private ?int $timestamp = null;

    private ?string $timezone = null;

    private array $responseDatas = [];
    

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): static
    {
        $this->statusCode = $statusCode;

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

    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    public function setErrorCode(string $errorCode): static
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getTimestamp(): ?\DateTime
    {
        $timestamp = "@$this->timestamp";
        return new \DateTime($timestamp);
    }

    public function setTimestamp(\DateTime $timestamp): static
    {
        $this->timestamp = $timestamp->getTimestamp();

        return $this;
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    public function setTimezone(string $timezone): static
    {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * @return Collection|ResponseData[]
     */
    public function getResponseDatas(): array
    {
        return $this->responseDatas;
    }

    public function addResponseDatas(ResponseData $responseData): self
    {
        $this->responseDatas[] = $responseData;

        return $this;
    }

}

?>