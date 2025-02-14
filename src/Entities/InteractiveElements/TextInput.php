<?php

namespace NitsujCodes\PDFDataTable\Entities\InteractiveElements;

use NitsujCodes\PDFDataTable\Interfaces\IInteractiveElement;
use NitsujCodes\PDFDataTable\PDFDataTables;
use TCPDF;

class TextInput implements IInteractiveElement
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