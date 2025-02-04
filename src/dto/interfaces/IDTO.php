<?php

namespace NitsujCodes\PDFDataTable\dto\interfaces;

interface IDTO
{
    public static function fromArray(array $data): static;
    public function toArray(): array;
}