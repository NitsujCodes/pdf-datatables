<?php

namespace NitsujCodes\PDFDataTable\DTO\Interfaces;

interface IDTO
{
    public function toArray(): array;
    public function withProperty(string $key, mixed $value): static;
    public function withProperties(array $properties): static;
}