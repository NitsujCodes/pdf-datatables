<?php

namespace NitsujCodes\PDFDataTable\Entities\Columns;

use NitsujCodes\PDFDataTable\Contracts\InteractiveColumnInterface;
use NitsujCodes\PDFDataTable\Contracts\InteractiveElementInterface;

class InteractiveColumn extends SimpleColumn implements InteractiveColumnInterface
{
    public function __construct(
        string                                       $label,
        string                                       $field,
        private readonly InteractiveElementInterface $element,
    ) {
        parent::__construct($label, $field, []);
    }

    public function hasHtmlContent(): bool
    {
        return false;
    }

    public function getInteractiveElement(): InteractiveElementInterface
    {
        return $this->element;
    }
}