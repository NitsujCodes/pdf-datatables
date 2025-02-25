<?php

namespace NitsujCodes\PDFDataTable\Contracts;

use TCPDF;

interface InteractiveElementInterface
{
    /**
     * Render the element onto the current PDF
     * @param TCPDF $pdf
     * @return string
     */
    public function render(TCPDF $pdf): string;

    /**
     * Get value from current row
     * @return mixed
     */
    public function getValue(): mixed;

    /**
     * Set current value
     * This acts as an override
     * @param mixed $value
     * @return void
     */
    public function setValue(mixed $value): void;
}