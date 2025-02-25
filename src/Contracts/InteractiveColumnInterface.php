<?php

namespace NitsujCodes\PDFDataTable\Contracts;

interface InteractiveColumnInterface extends ColumnDefinitionInterface
{
    /**
     * Get the current interactive element
     * @return InteractiveElementInterface
     */
    public function getInteractiveElement(): InteractiveElementInterface;
}