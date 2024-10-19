<?php

namespace App\Document;

class ResponseData
{

    private ?string $message = null;

    private ?string $propertyPath = null;

    private ?string $invalidValue = null;


    public function getPropertyPath(): ?string
    {
        return $this->propertyPath;
    }

    public function setPropertyPath(string $propertyPath): static
    {
        $this->propertyPath = $propertyPath;

        return $this;
    }

    public function getInvalidValue(): ?string
    {
        return $this->invalidValue;
    }

    public function setInvalidValue(string $invalidValue): static
    {
        $this->invalidValue = $invalidValue;

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

}

?>