<?php

namespace NitsujCodes\PDFDataTable\DTO\Interfaces;

interface IDTO
{
    public static function fromArray(array $data): static;
    public function toArray(): array;
}