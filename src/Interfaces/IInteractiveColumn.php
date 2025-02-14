<?php

namespace NitsujCodes\PDFDataTable\Interfaces;

interface IInteractiveColumn extends IColumnDefinition
{
    /**
     * Get the current interactive element
     * @return IInteractiveElement
     */
    public function getInteractiveElement(): IInteractiveElement;
}