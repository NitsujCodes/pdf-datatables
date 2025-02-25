<?php

namespace NitsujCodes\PDFDataTable\Entities\InteractiveElements;

use NitsujCodes\PDFDataTable\Contracts\InteractiveElementInterface;
use TCPDF;

class TextInput implements InteractiveElementInterface
{
    public function __construct(
        private string $value = '',
    ) {}

    public function render(TCPDF $pdf): string
    {
        // TODO: Add TCPDF rendering for the input
//        $pdf = PDFDataTables::getInstance()->getPDF();
        return '';
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(mixed $value): void
    {
        $this->value = (string)$value;
    }
}