<?php

namespace NitsujCodes\PDFDataTable\Entities\Columns;

use NitsujCodes\PDFDataTable\Entities\Columns\InteractiveColumn;
use NitsujCodes\PDFDataTable\Interfaces\IInteractiveColumn;
use NitsujCodes\PDFDataTable\Interfaces\IInteractiveElement;

class ComputedInteractiveColumn extends InteractiveColumn implements IInteractiveColumn
{
    public function __construct(
        string $label,
        private readonly \Closure $value,
        IInteractiveElement $element,
    ) {
        parent::__construct($label, '', $element);
    }

    public function getValue(array $row): string
    {
        $value = ($this->value)($row);
        $this->getInteractiveElement()->setValue($value);
        return $this->getInteractiveElement()->getValue();
    }
}