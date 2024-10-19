<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use App\Document\Response;

#[MongoDB\Document]
class Logging
{
    #[MongoDB\Id]
    private ?string $id = null;

    #[MongoDB\Field(type: "string")]
    private ?string $messageRaw = null;

    #[MongoDB\Field(type: "string")]
    private ?int $code = null;

    #[MongoDB\Field(type: "int")]
    private ?int $line = null;

    #[MongoDB\EmbedMany(targetDocument: "App\Document\Response")]
    private $responses;

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getMessageRaw(): ?string
    {
        return $this->messageRaw;
    }

    public function setMessageRaw(string $messageRaw): static
    {
        $this->messageRaw = $messageRaw;

        return $this;
    }

    public function getLine(): ?int
    {
        return $this->line;
    }

    public function setLine(int $line): static
    {
        $this->line = $line;

        return $this;
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

    /**
     * @return Collection|Response[]
     */
    public function getResponse(): Response
    {
        return $this->responses;
    }

    public function addResponses(Response $response): self
    {
        if (!$this->responses->contains($response)) {
            $this->responses[] = $response;
        }

        return $this;
    }

    public function removeResponse(Response $response): self
    {
        if ($this->responses->contains($response)) {
            $this->responses->removeElement($response);
        }

        return $this;
    }
}


?>