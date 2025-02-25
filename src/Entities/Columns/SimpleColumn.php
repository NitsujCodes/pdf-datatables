<?php

namespace NitsujCodes\PDFDataTable\Entities\Columns;

use NitsujCodes\PDFDataTable\Contracts\ColumnDefinitionInterface;
use NitsujCodes\PDFDataTable\Exceptions\ColumnFieldNotFoundException;

class SimpleColumn implements ColumnDefinitionInterface
{
    public function __construct(
        private readonly string $label,
        private readonly string $field,
        private readonly array  $headerStyleOptions = [],
        private readonly array  $bodyStyleOptions = [],
        private readonly bool   $hasHtmlContent = false,
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

    public function getHeaderStyleOptions(): array
    {
        return $this->headerStyleOptions;
    }

    public function getBodyStyleOptions(): array
    {
        return $this->bodyStyleOptions;
    }
}