<?php

namespace NitsujCodes\PDFDataTable\Entities\Columns;

use NitsujCodes\PDFDataTable\Contracts\InteractiveColumnInterface;
use NitsujCodes\PDFDataTable\Contracts\InteractiveElementInterface;

class ComputedInteractiveColumn extends InteractiveColumn implements InteractiveColumnInterface
{
    public function __construct(
        string                      $label,
        private readonly \Closure   $value,
        InteractiveElementInterface $element,
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