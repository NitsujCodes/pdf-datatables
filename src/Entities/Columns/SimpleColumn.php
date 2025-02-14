<?php

namespace NitsujCodes\PDFDataTable\Entities\Columns;

use NitsujCodes\PDFDataTable\Exceptions\ColumnFieldNotFoundException;
use NitsujCodes\PDFDataTable\Interfaces\IColumnDefinition;

class SimpleColumn implements IColumnDefinition
{
    public function __construct(
        private readonly string $label,
        private readonly string $field,
        private readonly bool $hasHtmlContent = false,
    ) {}

    /**
     * @throws ColumnFieldNotFoundException
     */
    public function getValue(array $row): string
    {
        if (!isset($row[$this->field])) {
            throw new ColumnFieldNotFoundException("Field {$this->field} not found in row");
        }

        return $row[$this->field];
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function hasHtmlContent(): bool
    {
        return $this->hasHtmlContent;
    }
}