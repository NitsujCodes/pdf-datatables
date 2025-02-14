<?php

namespace NitsujCodes\PDFDataTable\Interfaces;

interface IColumnDefinition
{
    /**
     * Get value from current row
     * @param array $row
     * @return string
     */
    public function getValue(array $row) : string;

    /**
     * Get column label
     * @return string
     */
    public function getLabel() : string;

    /**
     * TRUE if the column is expected to contain HTML content, FALSE if not
     * @return bool
     */
    public function hasHtmlContent() : bool;
}