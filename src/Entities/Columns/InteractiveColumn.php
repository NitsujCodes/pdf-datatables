<?php

namespace NitsujCodes\PDFDataTable\Entities\Columns;

use Exception;
use NitsujCodes\PDFDataTable\Interfaces\IInteractiveColumn;
use NitsujCodes\PDFDataTable\Interfaces\IInteractiveElement;

class InteractiveColumn extends SimpleColumn implements IInteractiveColumn
{
    public function __construct(
        string $label,
        string $field,
        private readonly IInteractiveElement $element,
    ) {
        parent::__construct($label, $field);
    }

    public function hasHtmlContent(): bool
    {
        return false;
    }

    public function getInteractiveElement(): IInteractiveElement
    {
        return $this->element;
    }
}