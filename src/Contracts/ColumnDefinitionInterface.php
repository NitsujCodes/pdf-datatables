<?php

namespace NitsujCodes\PDFDataTable\Contracts;

interface ColumnDefinitionInterface
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
     * Get Header Style Options
     * @return array
     */
    public function getHeaderStyleOptions() : array;

    /**
     * Get Body Style Options
     * @return array
     */
    public function getBodyStyleOptions() : array;

    /**
     * TRUE if the column is expected to contain HTML content, FALSE if not
     * @return bool
     */
    public function hasHtmlContent() : bool;
}