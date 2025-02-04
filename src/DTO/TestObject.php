<?php

namespace NitsujCodes\PDFDataTable\DTO;

class TestObject extends BaseDTO
{
    public function __construct(
        public readonly string $phone,
        public readonly string $name,
        public readonly int $age = 20,
        public readonly ?string $email = null,
    ) {
        parent::__construct();
    }
}