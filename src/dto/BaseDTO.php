<?php

namespace NitsujCodes\PDFDataTable\dto;

use Exception;

class BaseDTO
{
    /**
     * @throws Exception
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (!property_exists($this, $key)) {
                throw new \Exception("Property $key does not exist");
            }

            $this->$key = $value;
        }
    }

    /**
     * @throws Exception
     */
    public static function fromArray(array $data): static
    {
        return new static($data);
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}